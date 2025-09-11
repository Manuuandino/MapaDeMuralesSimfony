<?php

namespace App\Controller;

use App\Entity\Pin;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;



class PinController extends AbstractController
{
#[Route('/pins', name: 'pin_create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em): Response
    {
        // 1️⃣ Crear el Pin
        $pin = new Pin();
        $pin->setTitle($request->request->get('title'));
        $pin->setDescription($request->request->get('description'));
        $pin->setLatitude((float) $request->request->get('latitude'));
        $pin->setLongitude((float) $request->request->get('longitude'));
        $pin->setUser($this->getUser());
        $pin->setCreatedAt(new \DateTime());

        // 2️⃣ Subir múltiples imágenes al disco
        $imagesFiles = $request->files->get('images'); // name="images[]" en el modal
        $savedImages = [];
        if ($imagesFiles) {
            foreach ($imagesFiles as $imageFile) {
                if ($imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                    $savedImages[] = $newFilename;
                }
            }
        }

        // 3️⃣ Guardar en la DB solo la primera imagen (opcional)
        if (!empty($savedImages)) {
            $pin->setImage($savedImages[0]); // si querés mostrar algo en el admin
        }

        $em->persist($pin);
        $em->flush();

        return $this->redirectToRoute('mapa');
    }
}