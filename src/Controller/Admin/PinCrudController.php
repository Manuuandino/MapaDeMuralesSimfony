<?php

namespace App\Controller\Admin;

use App\Entity\Pin;
use App\Entity\PinImage;
use App\Form\PinImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),

            // Primera imagen para listado/admin
            ImageField::new('image')
                ->setUploadDir('public/uploads/pins')
                ->setBasePath('uploads/pins')
                ->setUploadedFileNamePattern('[contenthash].[extension]'),

            // MULTIPLES IMÁGENES editable
            CollectionField::new('images')
                ->setEntryType(PinImageType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->allowAdd()
                ->allowDelete(),
        ];
    }

    // ----------------- CREAR -----------------
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Pin) return;

        $entityInstance->setUser($this->getUser());
        $entityInstance->setCreatedAt(new \DateTime());

        $this->handleUploadedImages($entityInstance, $entityManager);

        parent::persistEntity($entityManager, $entityInstance);
    }

    // ----------------- EDITAR -----------------
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handleUploadedImages($entityInstance, $entityManager);

        parent::updateEntity($entityManager, $entityInstance);
    }

    // ----------------- FUNCION AUXILIAR -----------------
    private function handleUploadedImages(Pin $pin, EntityManagerInterface $em)
    {
        // Obtener archivos del formulario
        $form = $this->getContext()->getRequest()->files->get('Pin')['images'] ?? [];

        foreach ($form as $file) {
            if ($file instanceof UploadedFile) { // ⚡ procesar solo archivos nuevos
                $filename = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('uploads_directory'), $filename);

                $pinImage = new PinImage();
                $pinImage->setFilename($filename);
                $pin->addImage($pinImage);
                $em->persist($pinImage);

                // Primera imagen como destacada
                if (!$pin->getImage()) {
                    $pin->setImage($filename);
                }
            }
        }
    }
}
