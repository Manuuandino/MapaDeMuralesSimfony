<?php
namespace App\Controller;

use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapaController extends AbstractController
{
    #[Route('/mapa', name: 'mapa')]
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findAll();

        // Convertir a array simple y filtrar pines sin coordenadas
        $pinsData = array_filter(array_map(fn($pin) => [
            'id' => $pin->getId(),
            'title' => $pin->getTitle(),
            'description' => $pin->getDescription(),
            'lat' => $pin->getLatitude(),
            'lng' => $pin->getLongitude(),
            'image' => $pin->getImages()->first()?->getFilename() ?? null,
        ], $pins), fn($p) => $p['lat'] !== null && $p['lng'] !== null);

        return $this->render('mapa/mapa.html.twig', [
            'pins' => $pinsData,
        ]);
    }
}
