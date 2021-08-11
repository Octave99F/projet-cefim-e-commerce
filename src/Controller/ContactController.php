<?php

namespace App\Controller;

use App\Entity\Formulary;
use App\Form\ContactType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function new(Request $request, CategoryRepository $repo, MailerInterface $mailer): Response
    {
        $formulary = new Formulary();
        $formulary->setPostDate(new \DateTime('now'));
        $form = $this->createForm(ContactType::class, $formulary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formulary);
            $entityManager->flush();

            // lance event send contact       
            $mail = (new TemplatedEmail())
            ->from('miaou@miaou.fr')
            ->to('45ddc0c3a5-04ca83@inbox.mailtrap.io')
            ->subject('Formulaire de contact')
            ->htmlTemplate('mailer/contact.html.twig')
            ->context([
                'firstname' => $formulary->getFirstname(),
                'lastname' => $formulary->getLastname(),
                'object' => $formulary->getObject(),
                'content' => $formulary->getContent(),
                'mail' => $formulary->getEmail(),
                'phone' => $formulary->getPhone()
            ]);

            $mailer->send($mail);

            return $this->redirectToRoute('contact_confirmation');
        }

        return $this->render('contact/index.html.twig', [
            'categories' => $repo->findAllCategories(),
            'categories_products' => $repo->findAllCategoriesWithProducts(),
            'formContact' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact-confirmation", name="contact_confirmation")
     */
    public function confirm(CategoryRepository $repo): Response
    {
        return $this->render('contact/confirmation.html.twig', [
            'categories' => $repo->findAllCategories(),
            'categories_products' => $repo->findAllCategoriesWithProducts(),
        ]);
    }
}