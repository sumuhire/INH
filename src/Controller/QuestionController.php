<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Question;
use App\Form\CommentFormType;
use App\Form\QuestionFormType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use JMS\Serializer\SerializerBuilder;

class QuestionController extends Controller
{
    public function questionAnswer(Question $question, Request $request){

         /*
        * Get User id
        */

        $user = $this->getUser();
        $email = $user->getEmail();
        
        $manager = $this->getDoctrine()->getManager();
        

        /*
        ** Comment form instantiation
        */

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment, ['standalone' => true]);

        $commentForm->handleRequest($request);

         /*
        * Get question id
        */

        $question->getId();

         /*
        * Set user & question IDs
        */

        $comment->setUser($user)->setQuestion($question);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            //Register data if validated form


            $manager->persist($comment);
            $manager->flush();
            $this->sendMail("Email/answer.html.twig", $email, $comment, $question);                            
        }

        
        $comments=$manager->getRepository(Comment::class)->findByCommentsDate($question);
        // findBy(
        //     [
        //         'question'=> $question
        //     ]
        // );

       

        return $this->render(
            'Question/detail.html.twig',
            [
                'comments' => $comments,
                'question' => $question,
                'commentForm' => $commentForm->createView()
            ]
        );

    }

    public function sendMail(string $reason, string $email, Comment $comment, Question $question)
    {

        $transport = new \Swift_SmtpTransport("localhost:1025");
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Someone answered on your post'))
            ->setFrom('support@inh.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    $reason,
                    ["comment" => $comment, "question" => $question]
                ),
                'text/html'
            )
            /* ->addPart(
                $this->renderView(
                    $reason
                ),
                'text/plain'
            ); */;

        $mailer->send($message);
    }    
 
     

     public function delete(Question $question, Request $request) {

        $user =  $this->getUser();
        $id = $user->getId();
        $author = $question->getUser();

        $roles = $user->getRoles();
        $role = $roles[0];


        if($id == $author->getId() || $role == "ROLE_ADMIN") {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush(); 
            return new Response($this->redirectToRoute("homepage"));
        }
        return new Response($this->redirectToRoute("homepage"));
     }

   public function deleteComment(Comment $comment, Request $request) {

        $user = $this->getUser();
        $id = $user->getId();
        $author = $comment->getUser();

        $commentId = $comment->getId();
        $comment_save = $this->getDoctrine()->getManager()->getRepository(Comment::class)->find($commentId);
        $questionId = $comment_save->getQuestion();

        $roles = $user->getRoles();
        $role = $roles[0];

        if ($id == $author->getId() || $role == "ROLE_ADMIN") {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();

            return $this->questionAnswer($questionId, $request);
        }
        return new Response($this->redirectToRoute("homepage"));
   }
}