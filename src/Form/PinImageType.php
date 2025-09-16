<?php

namespace App\Form;

use App\Entity\PinImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PinImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uploadedFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Imagen',
            ]);

        // EventListener para asignar UploadedFile a la entidad existente
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            $pinImage = $event->getData();
            $form = $event->getForm();

            $file = $form->get('uploadedFile')->getData();
            if ($file) {
                $pinImage->setUploadedFile($file);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PinImage::class,
        ]);
    }
}
