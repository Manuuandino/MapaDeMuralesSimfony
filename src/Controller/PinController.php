<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\PinImage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PinController extends AbstractController
{
    #[Route('/pins', name: 'pin')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $pins = $doctrine->getRepository(Pin::class)->findAll();

        return $this->render('pin/pin.html.twig', [
            'pins' => $pins,
        ]);
    }

    #[Route('/pins/new', name: 'pins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $pin = new Pin();

        if ($request->isMethod('POST')) {

            // Rellenar campos del pin
            $pin->setTitle($request->request->get('title'));
            $pin->setDescription($request->request->get('description'));
            $pin->setLatitude((float) $request->request->get('latitude'));
            $pin->setLongitude((float) $request->request->get('longitude'));
            $pin->setUser($this->getUser()); // Requiere que estés logueado

            $artistaId = $request->request->get('artista_id');
            if ($artistaId) {
                $artista = $em->getRepository(Artista::class)->find($artistaId);
                $pin->setArtista($artista);
            }


            // Manejar múltiples imágenes
            $files = $request->files->get('images', []);
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $newFilename = uniqid() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('pins_images_directory'), $newFilename);

                    $pinImage = new PinImage();
                    $pinImage->setFilename($newFilename);
                    $pin->addImage($pinImage);
                }
            }

            // Guardar en DB
            $em = $doctrine->getManager();
            $em->persist($pin);
            $em->flush();

$this->addFlash('success', 'Pin creado correctamente');
return $this->redirectToRoute('mapa');

        }

        return $this->render('mapa');
    }

    #[Route('/pins/{id}', name: 'pins_show', methods: ['GET'])]
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', [
            'pin' => $pin,
        ]);
    }
}
