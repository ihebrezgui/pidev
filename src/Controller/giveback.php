<?php

namespace App\Controller;

use App\Entity\Givedonation;
use App\Form\GivedonationType;
use App\Repository\GiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


#[Route('/giveBack')]
class giveback extends AbstractController
{

    #[Route('/', name: 'app_giveback_index', methods: ['GET'])]
    public function index(GiveRepository $giveRepository): Response
    {

        return $this->render('giveBack/index.html.twig', [
            'givebacks' => $giveRepository->findAll(),
        ]);
    }

    #[Route('/{iddonateur}', name: 'app_giveback_show', methods: ['GET'])]
    public function show(Givedonation $givedonation): Response
    {
        return $this->render('giveBack/show.html.twig', [
            'givedonation' => $givedonation,
        ]);
    }
    /*#[Route('/pie-chart-data', name: 'app_giveback_pie_chart_data', methods: ['GET'])]
    public function pieChartData(GiveRepository $giveRepository): JsonResponse
    {
        $data = [];
        $professions = $giveRepository->getProfessionsCount();
        foreach ($professions as $profession) {
            $data[] = [$profession['profession'], (int)$profession['count']];
        }
        return $this->json($data);
    }*/

}

