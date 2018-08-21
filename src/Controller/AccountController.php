<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Entity\User;
use App\Form\UserSettingsFormType;
use App\DTO\UserSearch;
use App\Form\PasswordFormType;
use App\Form\ProfilePictureFormType;

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
           

        return new Response($this->render("User/account_settings.html.twig", ["settings" => $form->createView()]));
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

    public function changePicture(Request $request, UserInterface $user) {

        $user = $this->getUser();

        $form = $this->createForm(ProfilePictureFormType::class, $user, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form["picture"]->getData();
            $filename = $this->generateUniqueFileName() . "." . $file->guessExtension();

            $file->move(
                $this->getParameter('picture_directory'),
                $filename
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }
        
        return new Response($this->render("Debug/debug.html.twig", ["picture_form" => $form->createView()]));
    }

    public function deleteAccount(Request $request, UserInterface $user) {

        $user = $this->getUser();
        $user->getPassword();
        
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($user);
        $manager->flush();

        return new Response($this->redirectToRoute("login"));
    }

    public function displayAccount(Request $request, UserInterface $user) {

        $user = $this->getUser();
        $test = exec('whoami');
        return new Response($this->render("User/profile.html.twig", ["user" => $user, "test" => $test]));
    }

    public function visitAccount(Request $request, User $user, UserInterface $user2)
    {   
        $user2 = $this->getUser();
        $username = $user->getUsername();

        if($user2->getUsername() != $username) {

            ## $findUser = ->getRepository(User::class)->findByUsername($term);
            $query =    $this->getDoctrine()->getManager()->createQuery(
                        "SELECT u
                        FROM App\Entity\User u
                        WHERE u.username = :username")
                        ->setParameter("username", $username);
            $findUser = $query->execute();

            return new Response($this->render("User/visiting_profile.html.twig", ["user" => $findUser]));
        }

        
        return new Response($this->redirectToRoute("profile"));
       

        
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

}

?>