<?php

namespace App\Controller\Admin;

use App\Entity\Pin;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Doctrine\ORM\EntityManagerInterface;

class PinCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pin::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            ImageField::new('imagePath')
                ->setBasePath('uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setRequired(false),
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }

    // Aquí va persistEntity
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Pin) return;

        $entityInstance->setUser($this->getUser());
        $entityInstance->setCreatedAt(new \DateTime());

        parent::persistEntity($entityManager, $entityInstance);
    }
}
