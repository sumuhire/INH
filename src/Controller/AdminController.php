<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Form\InviteFormType;
use App\Entity\Invite;

class AdminController extends Controller {

    public function userInvite(Request $request, \Swift_Mailer $mailer) {

        $invite = new Invite();

        # $inviteID = $inviteRepository->findBy();
        # check email and id in database
        $form = $this->createForm(InviteFormType::class, $invite, ['standalone' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $random = random_int(10000, 1999999);

            $invite->setHash($random);

            $email = $form->get("email")->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invite);
            $entityManager->flush();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom("support@inh.com")
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'Email/invite.html.twig',
                        ["invite" => $invite]
                    ),
                    'text/html'
                )

                ->addPart(
                    $this->renderView(
                        'Email/invite.txt.twig',
                        ["invite" => $invite]
                    ),
                    'text/plain'
                );

            $mailer->send($message);


            return $this->redirectToRoute('invite');
        }

        return new Response($this->renderView(
            'Admin/inviteForm.html.twig',
            ["inviteForm" => $form->createView()]
        ));
        # send email with link to signup with hash
        # form submit post
        # random hash with link
        # signup route with hash
        # in signup function compare table entry with request hash
    }
}

?>