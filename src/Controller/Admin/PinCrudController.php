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
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Validator\ValidImageUpload;
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
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),

            ImageField::new('image')
            ->setUploadDir('public/uploads/pins')
            ->setBasePath('uploads/pins') 
            ->setUploadedFileNamePattern('[contenthash].[extension]')
            ->setHtmlAttribute('accept', 'image/png, image/webp, image/jpeg')
            ->setFormTypeOptions([
                'constraints' => [
                    new ValidImageUpload()
                ]
            ])
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
