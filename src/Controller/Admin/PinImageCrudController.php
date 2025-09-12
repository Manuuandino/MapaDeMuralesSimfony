<?php

namespace App\Controller\Admin;

use App\Entity\PinImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PinImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string {
        return PinImage::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            AssociationField::new('pin'), // Relación con Pin
            ImageField::new('filename')
                ->setBasePath('uploads/pins') // carpeta donde se guardan las imágenes
                ->setUploadDir('public/uploads/pins')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
        ];
    }
}
