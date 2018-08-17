<?php
namespace App\Controller;
use App\Entity\Comment;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionController extends Controller
{
    public function questionAnswer(Question $question, Comment $comment, Request $request){

        /*
        ** Question & comment are set as default
        */



        $manager = $this->getDoctrine()->getManager();



    }
}