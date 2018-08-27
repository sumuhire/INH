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

        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute("homepage");
        }
        
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

            $email = $question->getUser()->getEmail();
            $this->sendMail("Email/answer.html.twig", $email, $comment, $question);
            
            $comment = new Comment();
            $commentForm = $this->createForm(CommentFormType::class, $comment, ['standalone' => true]);
            
            return $this->redirectToRoute("questionAnswer", ["question" => $question->getId()]);
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
        $mailer = $this->get('mailer'); 
        $message = (new \Swift_Message('Someone answered on your post'))
            ->setFrom('ineedhelp.wf3@gmail.com')
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
        $question = $comment->getQuestion();
        $comment_save = $this->getDoctrine()->getManager()->getRepository(Comment::class)->find($commentId);
        $questionId = $comment_save->getQuestion();

        $roles = $user->getRoles();
        $role = $roles[0];

        if ($id == $author->getId() || $role == "ROLE_ADMIN") {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();

            $serializer = $this->getSerializer();

            #return $this->questionAnswer($questionId, $request);
            return $this->redirectToRoute("questionAnswer", array("question" => $question->getId()) );
        }
        return new Response($this->redirectToRoute("homepage"));
   }

    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }
}