<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use App\Form\UserSettingsFormType;
use App\DTO\UserSearch;
use App\Form\PasswordFormType;

class AccountController extends Controller {

    public function editAccount(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this->getUser();

        $form = $this->createForm(UserSettingsFormType::class, $user, ['standalone' => false]);
        $form->handleRequest($request);
        
        ## check username availability
        $userSearch = new UserSearch();
        $term = $form["username"]->getData();
        
        $findUser;
        if($term != $user->getUsername()) {

            $userSearch->setSearch($term);
            $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByUsername($userSearch);
        }

        if (empty($findUser)) {
        
            if ($form->isSubmitted() && $form->isValid()) {

            #get password
            $password = $user->getPassword();
            $user->setPassword($password);

            #get email and name to form a proper email
            $email = $user->getEmail();
            $name = $user->getFirstname();

            #send email to notfify about the account changes
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
        }
           

        return new Response($this->render("User/account_settings.html.twig", ["userForm" => $form->createView()]));
    }

    public function changePassword(Request $request, UserInterface $user, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        $user = $this->getUser();

        $form = $this->createForm(PasswordFormType::class, $user, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $form["password"]->getData());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }    

        return new Response($this->render("User/passwordForm.html.twig", ["passwordForm" => $form->createView()]));

    }

    public function displayAccount(Request $request, UserInterface $user) {


    }

}

?>