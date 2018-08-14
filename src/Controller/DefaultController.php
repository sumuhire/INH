<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserFormType;


class DefaultController extends Controller{

    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ['standalone' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $name = $user->getFirstname();
            $email = $user->getEmail();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom("support@inh.com")
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'Email/registration.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                )
        
            ->addPart(
                $this->renderView(
                    'Email/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            );
            
            $mailer->send($message);
            
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'Default/signup.html.twig',
            array('form' => $form->createView())
        );
        
    }

    public function login(AuthenticationUtils $authUtils) {

        $error = $authUtils->getLastAuthenticationError();
        
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            'Default/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }
    
}


?>