<?php
namespace App\Controller\Api;

use App\Entity\Pin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PinController extends AbstractController
{
    #[Route('/api/pins', name: 'api_pins', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $pins = $em->getRepository(Pin::class)->findAll();

        // Transformar la entidad a un array simple para el JSON
    $data = array_map(fn(Pin $pin) => [
        'id' => $pin->getId(),
        'title' => $pin->getTitle(),
        'lat' => $pin->getLatitude(),   // <-- aquí cambió
        'lng' => $pin->getLongitude(),  // <-- aquí cambió también si tu método es getLongitude()
        'description' => $pin->getDescription(),
    ], $pins);

        return $this->json($data);
    }
}
