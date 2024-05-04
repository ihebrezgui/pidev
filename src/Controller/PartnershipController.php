<?php

namespace App\Controller;

use App\Entity\Partnership;
use App\Form\PartnershipType;
use App\Repository\PartnershipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

#[Route('/partnership')]
class PartnershipController extends AbstractController
{
    #[Route('/', name: 'app_partnership_index', methods: ['GET'])]
    public function index(PartnershipRepository $partnershipRepository): Response
    {
        return $this->render('partnership/index.html.twig', [
            'partnerships' => $partnershipRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partnership_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partnership = new Partnership();
        $form = $this->createForm(PartnershipType::class, $partnership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partnership);
            $entityManager->flush();

            return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partnership/new.html.twig', [
            'partnership' => $partnership,
            'form' => $form,
        ]);
    }

    #[Route('/{idp}', name: 'app_partnership_show', methods: ['GET'])]
    public function show(Partnership $partnership): Response
    {
        return $this->render('partnership/show.html.twig', [
            'partnership' => $partnership,
        ]);
    }

    #[Route('/{idp}/edit', name: 'app_partnership_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partnership $partnership, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartnershipType::class, $partnership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partnership/edit.html.twig', [
            'partnership' => $partnership,
            'form' => $form,
        ]);
    }

    #[Route('/{idp}', name: 'app_partnership_delete', methods: ['POST'])]
    public function delete(Request $request, Partnership $partnership, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partnership->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($partnership);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/age-statistics-par', name: 'app_partnership_age_statistics_par')]
    public function showAgeStatistics(PartnershipRepository $partnershipRepository): Response
{
    $ageCounts = $partnershipRepository->countByAge();
    $averageAge = $partnershipRepository->averageAge();

    return $this->render('partnership/age_stat_partner.html.twig', [
        'ageCounts' => $ageCounts,
        'averageAge' => $averageAge,
    ]);
}

#[Route('/partnershipfront', name: 'app_partnership_front', methods: ['GET', 'POST'])]
public function new1(Request $request, EntityManagerInterface $entityManager): Response
{
    $partnership = new partnership();
    $form = $this->createForm(partnershipType::class, $partnership);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($partnership);
        $entityManager->flush();

        $this->sendEmail("partnership Notif","nouvelle demande de partnership est Ajouter");
        return $this->redirectToRoute('app_partnership_front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('partnership/partnershipfront.html.twig', [
        'partnership' => $partnership,
        'form' => $form,
    ]);
}
function sendEmail($subject, $message)
    {
        $transport = new EsmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername("mehergames29@gmail.com");
        $transport->setPassword("lbegsfgqzexxhrbc");
    
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from("pidev@gmail.com")
            ->to("mohamedaziz.chaabi@esprit.tn")
            ->subject($subject)
            ->text($message);
    
        $mailer->send($email);
    }

}
