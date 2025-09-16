<?php

namespace App\Controller\Admin;

use App\Entity\Pin;
use App\Form\PinImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PinCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return Pin::class; }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),

            CollectionField::new('images')
                ->setEntryType(PinImageType::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->allowAdd()
                ->allowDelete(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Pin) {
            $this->handleImages($entityInstance);
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Pin) {
            $this->handleImages($entityInstance);
        }

        parent::updateEntity($em, $entityInstance);
    }

    private function handleImages(Pin $pin)
    {
        foreach ($pin->getImages() as $pinImage) {
            $file = $pinImage->getUploadedFile();
            if ($file instanceof UploadedFile) {
                $newFilename = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('pins_images_directory'), $newFilename);
                $pinImage->setFilename($newFilename);
            }
        }
    }

}
