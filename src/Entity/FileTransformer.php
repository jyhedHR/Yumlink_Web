<?php

namespace App\Entity;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;

class FileTransformer implements DataTransformerInterface
{
    
    public function transform($file)
    {
        // Transform the file path string to a File object
        return new File($file);
    }

    public function reverseTransform($file)
    {
        // Transform the File object back to a file path string
        return $file->getPathname();
    }
}
