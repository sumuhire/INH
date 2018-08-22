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

    public function Question(Request $request){

        $user = $this->getUser();

        $manager = $this->getDoctrine()->getManager();

        $question= new Question();

        $questionForm = $this->createForm(
            QuestionFormType::class, 
            $question, 
            [
                'standalone' => true,
        
            ]);
        
        $questionForm->handleRequest($request);

        $question->setUser($user);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($question);
            $manager->flush();
            
        };

        return $this->render(
            'Question/question.html.twig',
            array(
                'questionForm' => $questionForm->createView()
            )
        );

    }


    public function homepage(Request $request){

        $user = $this->getUser();

        $userDepartment = $user->getDepartment();

        $userQuestions = $user->getQuestions();
        
        
        $question = new Question();
        
        $question->setUser($user);
        
        if(isset($user) && !empty($user)){

            $all = $manager->getRepository(Question::class)->findAllByQuestionDate();

            if(!empty($userDepartment)){

                $toAnswer = $manager->getRepository(Question::class)->findByDepartmentByQuestionDate($userDepartment);

            }else{

                
                ]
            );
            
        $questionForm->handleRequest($request);

        $manager = $this->getDoctrine()->getManager();

<<<<<<< HEAD
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
=======
            if(!empty($userQuestions)){

                $asked = $manager->getRepository(Question::class)->findByQuestionDate($user);

            }else{
>>>>>>> 9fb7224085b66a292c3ec2e471128ea7c569e673
                
                
                $manager->persist($question);
                $manager->flush();
                
        };

        $dto = new QuestionSearch();

        $searchForm = $this->createForm(QuestionSearchFormType::class, $dto, ['standalone' => true]);
        
        $searchForm->handleRequest($request);

        if(!empty($userDepartment)){

            $toAnswer = $manager->getRepository(Question::class)->findBy(
                [
                    'targetDepartment'=> $userDepartment
                ]
            );
        }

        if(!empty($userQuestions)){

            $asked = $manager->getRepository(Question::class)->findBy(
                [
                    'user'=> $user
                ]
            );
        }

        
        return $this->render(
            'Default/homepage.html.twig',
            array(
<<<<<<< HEAD
                'questionForm' => $questionForm->createView(),
=======
                'allQuestions' => $all,
                'askedQuestions' => $asked,
                'questions' => $toAnswer,
>>>>>>> 9fb7224085b66a292c3ec2e471128ea7c569e673
                'searchForm' => $searchForm->createView(),
                'askedQuestions' => $asked,
                'questions' => $toAnswer
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