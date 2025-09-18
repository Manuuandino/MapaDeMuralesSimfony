<?php

namespace App\Controller;

use App\Entity\PinImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GaleriaController extends AbstractController{

    #[Route('/galeria', name: 'galeria')]
    public function gallery(EntityManagerInterface $em): Response
    {
        $imagenes = $em->getRepository(PinImage::class)->findAll();
    
        return $this->render('gallery/galeria.html.twig', [
            'imagenes' => $imagenes,
        ]);
    }
}
