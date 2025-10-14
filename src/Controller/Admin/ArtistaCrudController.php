<?php

namespace App\Controller\Admin;

use App\Entity\Artista;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArtistaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artista::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nombre', 'Nombre del artista');
        yield AssociationField::new('pins', 'Pines asociados')
            ->setFormTypeOptions(['by_reference' => false]);
    }
}


