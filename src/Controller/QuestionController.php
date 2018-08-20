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

class QuestionController extends Controller
{

    public function questionAnswer(User $user, Question $question, Request $request){

         /*
        * Get User id
        */

        $user = $this->getUser();

        /*
        * Get question id
        */

        $question = $this->getquestion();

        // if(!$question){
        //     throw new NotFoundHttpException();
        //     return $this->redirectToRoute('errors');
        // }

        
        $manager = $this->getDoctrine()->getManager();
        
        /*
        ** Comment form instantiation
        */

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment, ['standalone' => true]);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            //Register data if validated form
            $manager->persist($comment);
            $manager->flush();
            
        }

        $comment->setUser($user)->setQuestion($question);

        return $this->render(
            'question/detail.html.twig',
            [
                'question' => $question,
                'commentForm' => $commentForm->createView()
            ]
        );




        





        

    }
}