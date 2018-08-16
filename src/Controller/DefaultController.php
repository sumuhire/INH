<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;
use App\Entity\Role;

use App\Form\UserFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Invite;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller{

    public function signup(Invite $invite, Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        # check if invite corresponds to a db entry in Invite

        $inviteRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Invite::class);
        $invite_compare = $inviteRepository->find($invite->getId());
        $new_email = $invite_compare->getEmail();

        if($invite_compare){       
        
            $user = new User();
            $form = $this->createForm(UserFormType::class, $user, ['standalone' => false]);
            $form->get("email")->setData($new_email);
            $user->setEmail($new_email);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $roleRepository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Role::class);
                $role = $roleRepository->find(2);

                $user->setRoles($role);
                    
                $name = $user->getFirstname();
                $email = $user->getEmail();

                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                
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
                    
                    # add user to dtb
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    # remove invite
                    $entityManager->remove($invite_compare);
                    $entityManager->flush();
                    
                    return $this->redirectToRoute('login');
                }
        }
            return $this->render(
            'Default/signup.html.twig',
            array('form' => $form->createView(), "task" => $invite->getId())
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