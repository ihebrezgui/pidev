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
use Endroid\QrCode\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\Png;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/events')]
class EventsController extends AbstractController
{
    #[Route('/', name: 'app_events_index', methods: ['GET'])]
    public function index(Request $request, EventsRepository $eventsRepository, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery !== null) {
            $events = $eventsRepository->Searchevent($searchQuery);
        } else {
            $events = $eventsRepository->findAllOrderedByDate();
        }
        
        $query = $eventsRepository->createQueryBuilder('f')->getQuery();
        $events =$paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('back/events/index.html.twig', [
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

        return $this->renderForm('back/events/new.html.twig', [
            'event' => $event,
            'form' => $form,
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

        return $this->renderForm('back/events/edit.html.twig', [
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
    
        return $this->render('back/events/statistics.html.twig', [
            'eventsByMonth' => $eventsByMonth,
        ]);
    }
   
   
    #[Route('/show_front', name: 'app_events_index_front', methods: ['GET'])]
    public function search(Request $request, EventsRepository $eventsRepository, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery !== null) {
            $events = $eventsRepository->findBySearchQuery($searchQuery);
        } else {
            $events = $eventsRepository->findAllOrderedByDate();
        }

        // Pagination
        $query = $eventsRepository->createQueryBuilder('f')->getQuery();
        $events = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );


        return $this->render('front/show_events.html.twig', [
            'events' => $events,
        ]);
   
}
#[Route('/video-url', name: 'app_video_url', methods: ['GET'])]
public function getVideoUrl(): JsonResponse
{
    // Replace 'your-video-url' with the actual URL of your video
    $videoUrl = 'https://www.youtube.com/watch?v=3OUdeW4HmgE';

    return new JsonResponse(['url' => $videoUrl]);
}

#[Route('/calendar', name: 'app_calendar')]
    public function calendar  (EventsRepository $eventRepository): Response
    {
        $events = $eventRepository->findAllOrderedByDate();

        return $this->render('front/calendar.html.twig', [
            'events' => $events,
        ]);
    }
  
}
