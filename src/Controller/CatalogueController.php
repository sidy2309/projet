<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\Game1Type;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalogue")
 */
class CatalogueController extends AbstractController
{
    /**
     * @Route("/", name="catalogue_index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    
    /**
     * @Route("/{id}", name="catalogue_show", methods={"GET"})
     */
    public function show(Game $game): Response
    {
        return $this->render('catalogue/show.html.twig', [
            'game' => $game,
        ]);
    }

    
}
