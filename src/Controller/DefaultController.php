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
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Invite;
use App\Entity\Question;
use App\Entity\Role;
use App\Entity\User;

use App\DTO\QuestionSearch;
use App\Form\UserFormType;
use App\DTO\UserSearch;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


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
                    $role = $roleRepository->find('33c37cac-a165-11e8-a3ab-fcfaf7543d29');

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
        

        if($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("homepage");
        }
        /*
        * Get User department
        */
        $userDepartment = $user->getDepartment();
        
        
        /*
        * Get Manager
        */
        $manager = $this->getDoctrine()->getManager();
    

        
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
            
            $question = new Question();
            $question->setUser($user);
            $questionForm = $this->createForm(
                QuestionFormType::class,
                $question,
                [
                    'standalone' => true,
                ]
            );    
        };
        
       
        
        return $this->render(
            'Default/homepage.html.twig',
            array(
                'questionForm' => $questionForm->createView(),
                "user" => $user
            )
        );
    }

    public function searchQuestion(Request $request, string $searchTerm) {

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("homepage");
        }

        $dto = new QuestionSearch();

        $dto->setSearch($searchTerm);

        $manager = $this->getDoctrine()->getManager();

        $questions = $this->getDoctrine()->getManager()->getRepository(Question::class)->findByQuestionSearch($dto);

        $serializer = $this->getSerializer();
        $data = $serializer->normalize(
            $questions,
            'json',
            array(
                'groups' => array('public')
            )
        );

        return new JsonResponse(
            $data
        );
    }

    public function requestAllQuestions() {

        $user = $this->getUser();

        $manager = $this->getDoctrine()->getManager();
        $all = $manager->getRepository(Question::class)->findAllByQuestionDate();
        
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $all,
            'json',
            array(
                'groups' => array('public')
            )
        );

        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    public function requestMyQuestion() {

        $user = $this->getUser();

        $manager = $this->getDoctrine()->getManager();
        $asked = $manager->getRepository(Question::class)->findByQuestionDate($user);

        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $asked,
            'json',
            array(
                'groups' => array('public')
            )
        );
        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    public function requestDepartmentQuestion() {

        $user = $this->getUser();

        if (!$user) {
            return $this->json([]);
        }
        
        $userDepartment = $user->getDepartment();
        $manager = $this->getDoctrine()->getManager();
        $toAnswer = $manager->getRepository(Question::class)->findByDepartmentByQuestionDate($userDepartment);

        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $toAnswer,
            'json',
            array(
                'groups' => array('public'))
        );
        return new JsonResponse(
            $data,
            200,
            [],
            true
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

    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }

    public function header(Request $request){

        /*
        * Get UserId
        */
        $user = $this->getUser();

        /*
        * Get Manager
        */
        $manager = $this->getDoctrine()->getManager();



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
        * Record data
        */ 
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
        
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($question);
        $manager->flush();
        
        };



        return $this->render(
        'header.html.twig',
            array(
                'questionFormI' => $questionForm->createView(),

            )
            );
        }
    
}
?>