<?php
namespace App\Controller;


use App\Form\QuestionFormType;
use App\Form\QuestionSearchFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Invite;
use App\Entity\Question;
use App\Entity\Role;
use App\Entity\User;

use App\DTO\QuestionSearch;
use App\Form\UserFormType;
use App\DTO\UserSearch;


class DefaultController extends Controller{

    public function signup(Invite $invite, Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer) {

        # check if chosen email already exists
        
        
        $userRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class);

        $inviteRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Invite::class);
            
        $invite_compare = $inviteRepository->find($invite->getId());
        $new_email = $invite_compare->getEmail();

        if($invite_compare){
        
            $user = new User();

            $user->setEmail($new_email);
            $form = $this->createForm(UserFormType::class, $user, ['standalone' => false]);
            $form->handleRequest($request);
            
            $term = $form["username"]->getData();

            $userSearch = new UserSearch();
            $userSearch->setSearch($term);

            $manager = $this->getDoctrine()->getManager();
            $findUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findByUsername($userSearch);
            

            if(empty($findUser)) {

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

                    $user->setPicture("default.png");

                    
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
                array('form' => $form->createView(), "task" => $invite->getId()));
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



    public function homepage(Request $request){

                 /*
        * Get User id
        */
        $user = $this->getUser();
        
        /*
        * Get User department
        */
        $userDepartment = $user->getDepartment();
         /*
        * Get User department
        */
        $userQuestions = $user->getQuestions();
        
        /*
        * Get Manager
        */
        $manager = $this->getDoctrine()->getManager();
        
       /*
        * Question search
        */
        $dto = new QuestionSearch();
        $searchForm = $this->createForm(QuestionSearchFormType::class, $dto, ['standalone' => true]);
        
        $searchForm->handleRequest($request);
        
        if(isset($user) && !empty($user)){
            $all = $manager->getRepository(Question::class)->findAllByQuestionDate();
            if(!empty($userDepartment)){
                $toAnswer = $manager->getRepository(Question::class)->findByDepartmentByQuestionDate($userDepartment);
            }else{
                
            }
            /*
            * Question listing for asked part based on user's department
            */
            
            if(!empty($userQuestions)){
                $asked = $manager->getRepository(Question::class)->findByQuestionDate($user);
            }else{
                
            }
        
        }else{
            /*
            * redirect to route login
            */
        
            return $this->redirectToRoute('login');
        }
        /*
        * Question form
        */
        $question= new Question();
        $question->setUser($user);
        $questionForm = $this->createForm(
            QuestionFormType::class, 
            $question, 
            [
                'standalone' => true,
        
            ]);
        
        
        
        $questionForm->handleRequest($request);
         /*
        * Set user ID
        */
        
        
         /*
        * Record data
        */ 
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($question);
            $manager->flush();
            
        };
        
       
        
        return $this->render(
            'Default/homepage.html.twig',
            array(
                'allQuestions' => $all,
                'askedQuestions' => $asked,
<<<<<<< HEAD
                'questions' => $toAnswer,
                'searchForm' => $searchForm->createView(),
                'questionForm' => $questionForm->createView()
=======
                "user" => $user,
                'questions' => $toAnswer
>>>>>>> controller changes
            )
        );
    }

    public function error(){

    

        return $this->render(
            'Error/error.html.twig',
            array(
           
                
            )
        );

    }

    public function errorInvite(){


        return $this->render(
            'Error/inviteNotFound.html.twig',
            array(
                
            )
        );

    }
    
}
?>