<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\UserSettingsFormType;

class AccountController extends Controller {

    public function editAccount(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $password = $user->getPassword();

        $form = $this->createForm(UserSettingsFormType::class, $user, ['standalone' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $user->setPassword($password);

            $email = $user->getEmail();
            $name = $user->getFirstname();

            $message = (new \Swift_Message('Account Preferneces Changed'))
                ->setFrom("support@inh.com")
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'Email/Account/accountSettings.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                )

                ->addPart(
                    $this->renderView(
                        'Email/Account/accountSettings.html.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                );
                $mailer->send($message);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }    

        return new Response($this->render("User/account_settings.html.twig", ["userForm" => $form->createView()]));
    }

    public function displayAccount(Request $request, UserInterface $user) {


    }
}

?>