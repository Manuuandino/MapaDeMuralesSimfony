<?php
namespace App\Controller;

use App\Entity\Artista;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapaController extends AbstractController
{
    #[Route('/mapa', name: 'mapa')]
    public function index(PinRepository $pinRepository, EntityManagerInterface $em): Response
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

        // Traer todos los artistas para el select del modal
        $artistas = $em->getRepository(Artista::class)->findBy([], ['nombre' => 'ASC']);

        // Renderizamos la plantilla pasando TODO lo que necesita
        return $this->render('mapa/mapa.html.twig', [
            'pins' => $pinsData,
            'artistas' => $artistas,
        ]);
    }
}
