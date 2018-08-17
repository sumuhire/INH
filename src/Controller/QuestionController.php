<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class QuestionController extends Controller
{
    public function question(Request $request){
    //     $manager = $this->getDoctrine()->getManager();
    //     $question= new Question();
    //     $questionForm = $this->createForm(QuestionFormType::class, $question, ['standalone' => true]);
    //     $questionForm->handleRequest($request);
        
    //     if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            
    //         $manager->persist($question);
    //         $manager->flush();
            
    //     }
        
    //     return $this->render(
    //         'Default/question.html.twig',
    //         [
    //             'questionForm' => $questionForm->createView()
    //         ]
    //     );
    
    }
    
}