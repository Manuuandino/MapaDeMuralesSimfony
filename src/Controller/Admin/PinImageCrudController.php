<?php

namespace App\Controller\Admin;

use App\Entity\PinImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PinImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PinImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('filename')
                ->setBasePath('uploads/pins')
                ->setUploadDir('public/uploads/pins')
                ->setUploadedFileNamePattern('[contenthash].[extension]'),
            AssociationField::new('pin'),
        ];
    }
}
