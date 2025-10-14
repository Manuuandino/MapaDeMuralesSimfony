<?php

namespace App\Controller;

use App\Entity\Artista;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArtistaController extends AbstractController
{
    // Endpoint para crear un artista nuevo desde el modal
    #[Route('/artistas/create', name: 'artistas_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $nombre = trim($request->request->get('nombre', ''));
        if (!$nombre) {
            return new JsonResponse(['error' => 'Nombre requerido'], 400);
        }

        // Evitamos duplicados
        $existing = $em->getRepository(Artista::class)->findOneBy(['nombre' => $nombre]);
        if ($existing) {
            return new JsonResponse(['id' => $existing->getId(), 'nombre' => $existing->getNombre()]);
        }

        $artista = new Artista();
        $artista->setNombre($nombre);
        $em->persist($artista);
        $em->flush();

        return new JsonResponse(['id' => $artista->getId(), 'nombre' => $artista->getNombre()]);
    }

    // Endpoint opcional para listar todos los artistas (si querés AJAX)
    #[Route('/artistas/list', name: 'artistas_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $artistas = $em->getRepository(Artista::class)->findBy([], ['nombre' => 'ASC']);
        $data = array_map(fn($a) => ['id' => $a->getId(), 'nombre' => $a->getNombre()], $artistas);
        return new JsonResponse($data);
    }
}
