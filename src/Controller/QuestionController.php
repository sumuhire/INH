<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
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

     public function delete(UserInterface $user, Question $question,Request $request) {

        $user =  $this->getUser();
        $id = $question->getId();
        $author = $question->getUser();
        $roles = $user->getRoles();
        $role = $roles[0];

        if($id == $author->getId() || $role == "ROLE_ADMIN") {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush(); 
        }


     }
    
}