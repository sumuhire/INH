<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Form\InviteFormType;
use App\Entity\Invite;
use App\Entity\User;
use App\Entity\Role;
use App\DTO\UserSearch;
use App\Form\UserSearchFormType;

class AdminController extends Controller {


    private $users; 

    public function userInvite(Request $request, \Swift_Mailer $mailer) {

        $invite = new Invite();

        # $inviteID = $inviteRepository->findBy();
        # check email and id in database
        $form = $this->createForm(InviteFormType::class, $invite, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $random = random_int(10000, 1999999);

            $invite->setHash($random);

            $email = $form->get("email")->getData();

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


            return $this->redirectToRoute('invite');
        }

        return new Response($this->renderView(
            'Admin/inviteForm.html.twig',
            ["inviteForm" => $form->createView()]
        ));
    }

    public function listInvites() {

        $inviteRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Invite::class);
        $invites = $inviteRepository->findAll();

        return new Response($this->render("Admin/Lists/inviteList.html.twig", ["invites" => $invites]));
    }

    public function userList(Request $request, $roleChange) {

        $term = new UserSearch();
        $term2 = new UserSearch();
        $searchForm = $this->createForm(UserSearchFormType::class, $term, ['standalone' => true]);

        $searchForm->handleRequest($request);
        $terms = $term->getSearch();
        $termsSplit = explode(" ", $terms);
        $found_users = "";
        if(isset($termsSplit[1])) {
            $term->setSearch($termsSplit[0]);
            $term2->setSearch($termsSplit[1]);
        }
        else {
            $term->setSearch($terms);
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
        
        return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "searchForm" => $searchForm->createView(), "role" => $roleChange]));
        
    }

    public function makeAdmin(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $admin = $roleRepository->find(1);

        if($user->getRoles() != $admin) {

            $user->setRoles($admin);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email = $user->getEmail();
            $reason = "Email/Account/admin.html.twig";
            $this->sendMail($reason, $email);

           return $this->userList($request, true);
        }        
        
        $this->userList($request, "unable");
    }

    public function makeUser(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $normal = $roleRepository->find(2);

        if ($user->getRoles() != $normal) {

            $user->setRoles($normal);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email = $user->getEmail();
            $reason = "Email/Account/noAdmin.html.twig";
            $this->sendMail($reason, $email);

            return $this->userList($request, true);
        }
        return $this->userList($request, "unable");
    }

    public function makeInactive(User $user, Request $request) {

        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $inactive = $roleRepository->find(3);
        $admin = $roleRepository->find(1);

        if ($user->getRoles() != $admin && $user->getRoles() != $inactive ) {

            $user->setRoles($inactive);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $reason = "Email/Account/inactive.html.twig";
            $email = $user->getEmail();
            $this->sendMail($reason, $email);
            return $this->userList($request, true);
        }
        return $this->userList($request, "unable");
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

}

?>