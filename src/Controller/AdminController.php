<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use App\Entity\Department;
use App\Entity\Invite;
use App\Entity\Role;
use App\Entity\User;

use App\Form\DepartmentFormType;
use App\Form\InviteFormType;
use App\Form\UserSearchFormType;

use App\DTO\InviteSearch;
use App\DTO\UserSearch;
use App\Entity\Report;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class AdminController extends Controller {

    

    public function userInvite(Request $request, \Swift_Mailer $mailer) {

         # check if chosen email already exists
        $invite = new Invite();

        $form = $this->createForm(InviteFormType::class, $invite, [
            'standalone' => true,
            'method' => 'POST'
            ]);
        $form->handleRequest($request);
        
        $emailList = $form->get("email")->getData();

        $emails = explode(",", $emailList);

        foreach($emails as $email){

            $email_compare2 = new UserSearch();
            $email_compare2->setSearch($email);
            $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($email_compare2);

            $email_compare = new InviteSearch();
            $email_compare->setSearch($email);
            $findInvite = $this->getDoctrine()->getManager()->getRepository(Invite::class)->findByEmail($email_compare);

            # check if email already exists in Database
            if(empty($findInvite) && empty($findUser)) {

                if ($form->isSubmitted() && $form->isValid()) {

                    $random = random_int(10000, 1999999);
                    $invite = new Invite();
                    $invite->setHash($random);
                    $invite->setEmail($email);
                    #$email = $form->get("email")->getData();

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($invite);
                    $entityManager->flush();

                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom("support@inh.com")
                        ->setTo($email)
                        ->setBody(
                            $this->renderView(
                                'Email/invite.html.twig',
                                ["invite" => $invite]
                            ),
                            'text/html'
                        )

                        ->addPart(
                            $this->renderView(
                                'Email/invite.txt.twig',
                                ["invite" => $invite]
                            ),
                            'text/plain'
                        );

                    $mailer->send($message);

                }
            }
            # give user information that email exists
        }



        // return new Response($this->renderView(
        //     'Admin/inviteForm.html.twig',
        //     ["inviteForm" => $form->createView(), "warning" => "user or invite already exists"]
        // ));

        return $form;
    }


    public function deleteInvite(Invite $invite, Request $request) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($invite);
        $entityManager->flush();

        return $this->redirectToRoute("inviteList");      

    }


    public function listInvites() {
        $inviteRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Invite::class);
        $invites = $inviteRepository->findAll();
        
        return new Response($this->render("Admin/Lists/inviteList.html.twig", ["invites" => $invites]));

    }


    # make the lastSearch term persist trough the execution
    public function userList(Request $request) {

        $roleChange = $request->get("change");
        
        if(!isset($roleChange)) {
            $roleChange = 2;
        }
        
        $term = new UserSearch();
        $term2 = new UserSearch();
        $searchForm = $this->createForm(UserSearchFormType::class, $term, ['standalone' => true]);
        $searchForm->handleRequest($request);
        $terms = $term->getSearch();
        $termsSplit = explode(" ", $terms);

        if(isset($termsSplit[1])) {
            $term->setSearch($termsSplit[0]);
            $term2->setSearch($termsSplit[1]);
            $lastSearchTerm = $term->getSearch() . " " . $term2->getSearch();
        }
        else {
            $term->setSearch($terms);
            $lastSearchTerm = $term->getSearch();
        }
        
        if (filter_var($terms, FILTER_VALIDATE_EMAIL)) {
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByEmail($term);
            
        } else if (isset($termsSplit[1])) {
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByName($term, $term2);
            
        } else if(!isset($termsSplit[1])){
            $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findByUsername($term);
            $users += $this->getDoctrine()->getManager()->getRepository(User::class)->findByFirstName($term);
            $users += $this->getDoctrine()->getManager()->getRepository(User::class)->findByLastName($term);
        }
        else  {
            $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
            $users = $userRepository->findAll(); 
        }
        
        // return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "searchForm" => $searchForm->createView(), "role" => $roleChange]));
        
        return [
            $users,
            $searchForm,
            $roleChange
        ];
         
        
    }


    public function reportList(Request $request) {

        $reports = $this->getDoctrine()->getManager()->getRepository(Report::class)->findAll();

        // return new Response($this->render("Admin/Lists/reportList.html.twig", ["reports" => $reports]));

        return $reports;
    }

    public function departmentList(Request $request) {


        $department = new Department();
        $departmentForm = $this->createForm(DepartmentFormType::class, $department);
        $departmentForm->handleRequest($request);
        
        $departments = $this->getDoctrine()->getManager()->getRepository(Department::class)->findBy([], ['label' => 'ASC']);

        if ($departmentForm->isSubmitted() && $departmentForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();
        }

        // return new Response($this->render("Admin/Lists/departmentList.html.twig", ["departments" => $departments, "d_form" => $departmentForm->createView()]));

        return [
            $departments,
            $departmentForm
        ];
    
    }

    public function makeAdmin(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $role = $roleRepository->find(1);

        if($user->getRoles()[0] != $role) {

            $this->flushUser($user, $role);

            $email = $user->getEmail();
            $reason = "Email/Account/admin.html.twig";
            $this->sendMail($reason, $email);

            return $this->redirectToRoute("userList", ["change" => 0]);
        }

        return $this->redirectToRoute("userList", ["change" => 1]);
    }

    public function makeUser(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $role = $roleRepository->find(2);

        if ($user->getRoles()[0] != $role) {

            $this->flushUser($user, $role);

            $email = $user->getEmail();
            $reason = "Email/Account/noAdmin.html.twig";
            $this->sendMail($reason, $email);


            return $this->redirectToRoute("userList", ["change" => 3]);
        }
        return $this->redirectToRoute("userList", ["change" => 1]);
    }


    public function makeInactive(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $inactive = $roleRepository->find(3);
        $role = $roleRepository->find(1);

        if ($user->getRoles()[0] != $role && $user->getRoles() != $inactive ) {

            $this->flushUser($user, $inactive);

            $reason = "Email/Account/inactive.html.twig";
            $email = $user->getEmail();
            $this->sendMail($reason, $email);


            return $this->redirectToRoute("userList", ["change" => 4]);
        }
        return $this->redirectToRoute("userList", ["change" => 1]);
    }

    public function sendMail(string $reason, string $email) {
        
        $transport = new \Swift_SmtpTransport("localhost:1025");
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('support@inh.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    $reason
                ),
                'text/html'
            )
        ->addPart(
            $this->renderView(
                $reason
            ),
            'text/plain'
        );
        $mailer->send($message);
    }



    public function userDetail(User $user, Request $request) {
        return new Response($this->render("Admin/Lists/userDetail.html.twig", ["user" => $user]));
    }

    public function admin(Request $request, \Swift_Mailer $mailer) {

        $userList=$this->userList($request);
        $invite=$this->userInvite($request, $mailer);
        $department=$this->departmentList($request);
        $reports=$this->reportList($request);

        return $this->render(
            'Admin/dashboard.html.twig',
            [
                'users' => $userList[0],
                'searchForm' => $userList[1]->createView(),
                'roleChange' => $userList[2],
                'inviteForm'=> $invite->createView(),
                'departments'=> $department[0],
                'departmentForm'=> $department[1]->createView(),
                'reports' => $reports
                // "warning" => "user or invite already exists"

            ]
        );
    
    }

    public function departmentUserList(Department $department, Request $request){

        $userList=$this->userList($request);
        
        /*
        * Get question id
        */

        $department->getLabel();

        return $this->render(
            'Admin/departmentUserList.html.twig',
            [
                'users' => $userList[0],
                'department' => $department

            ]
        );

        $user->setRoles($role);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        
        $department=$manager->getRepository(Department::class)->findAll();

        return new Response($this->render("Admin/Lists/userDetail.html.twig", ["user" => $user , "department" => $department ]));
    }

    // public function countUserDepartment()
    // {
    //     $user = $this->getDoctrine()->getRepository(User::class);
    //     $department = $this->getDoctrine()->getRepository(Department::class)->FindByDepartment();
    //     $NbrUser =$department->getLabel($department);
      

    //     return $this->render(
    //         'Admin/dashboard.html.twig',
    //         [
    //             'depart'=>$department,
    //             'cound'=>$countUd
    //         ]
    //     );
    // }

}


?>
