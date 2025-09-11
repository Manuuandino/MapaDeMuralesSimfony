<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidImageUploadValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof UploadedFile) {
            return;
        }

        $allowedMimeTypes = [
            'image/png',
            'image/webp',
            'image/jpeg',
        ];

        $allowedExtensions = [
            'png',
            'webp',
            'jpg',
            'jpeg',
        ];

        $mimeType = $value->getMimeType();
        $extension = strtolower($value->getClientOriginalExtension());

        if (!in_array($mimeType, $allowedMimeTypes, true) || !in_array($extension, $allowedExtensions, true)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}