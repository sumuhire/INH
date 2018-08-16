<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Form\InviteFormType;
use App\Entity\Invite;
use App\Entity\User;
use App\Entity\Role;

class AdminController extends Controller {

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

    public function userList(Request $request) {

        
        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);
        $users = $userRepository->findAll();

        return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => false]));
    }

    public function makeAdmin(User $user, Request $request) {

        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);
        $users = $userRepository->findAll();
        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $admin = $roleRepository->find(1);
        if($user->getRoles() != $admin) {

            $user->setRoles($admin);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => true]));
        }        
        
        return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => false] ));
    }

    public function removeAdmin(User $user, Request $request) {

        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);
        $users = $userRepository->findAll();
        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $normal = $roleRepository->find(2);
        if ($user->getRoles() != $normal) {

            $user->setRoles($normal);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => true]));
        }
    }

    public function deactivateUser(User $user, Request $request) {

        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);
        $users = $userRepository->findAll();
        $roleRepository = $this->getDoctrine()->getManager()->getRepository(Role::class);
        $inactive = $roleRepository->find(3);
        $admin = $roleRepository->find(1);

        if ($user->getRoles() != $admin && $user->getRoles() != $inactive ) {

            $user->setRoles($inactive);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => true]));
        } 
        else {
            return new Response($this->render("Admin/Lists/userList.html.twig", ["users" => $users, "role" => "unable"]));
        }
    }
}

?>