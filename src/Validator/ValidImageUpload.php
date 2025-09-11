<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidImageUpload extends Constraint
{
    public $message = 'Solo se permiten imágenes PNG o WebP válidas.';
}