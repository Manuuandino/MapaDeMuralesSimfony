<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapaController extends AbstractController
{
    #[Route('/mapa', name: 'mapa')]
    public function index(): Response
    {
        // No hay pines precargados
        $pins = [];

        return $this->render('mapa/mapa.html.twig', [
            'pins' => $pins,
        ]);
    }
}

