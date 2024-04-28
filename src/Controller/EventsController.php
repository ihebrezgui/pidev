<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\Events1Type;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\Png;

#[Route('/events')]
class EventsController extends AbstractController
{
    #[Route('/', name: 'app_events_index', methods: ['GET'])]
    public function index(Request $request, EventsRepository $eventsRepository): Response
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery !== null) {
            $events = $eventsRepository->findBySearchQuery($searchQuery);
        } else {
            $events = $eventsRepository->findAllOrderedByDate();
        }

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/new', name: 'app_events_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Events();
        $form = $this->createForm(Events1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_events_show', methods: ['GET'])]
    public function show(Events $event): Response
    {
        $qrCodePath = $this->generateQrCode($event->getDescription());

        return $this->render('events/show.html.twig', [
            'event' => $event,
            'qrCodePath' => $qrCodePath,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_events_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Events1Type::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_events_delete', methods: ['POST'])]
    public function delete(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId_event(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
    }
 
    #[Route('/statistics', name: 'events_statistics', methods: ['GET'])]
    public function eventsStatistics(EventsRepository $eventsRepository): Response
    {
        $eventsByMonth = $eventsRepository->countEventsByMonth();
    
        return $this->render('events/statistics.html.twig', [
            'eventsByMonth' => $eventsByMonth,
        ]);
    }
    private function generateQrCode(string $description): string
    {
        $renderer = new ImageRenderer(new Png());
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($description);

        $tempFile = tempnam(sys_get_temp_dir(), 'qr_code');
        file_put_contents($tempFile, $qrCode);

        return '/qr_codes/' . basename($tempFile);
    }
}
