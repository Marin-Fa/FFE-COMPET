<?php

namespace App\Form;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayTransformer implements DataTransformerInterface
{
    /**
     * @param \DateTime $array
     * @return int
     * @throws \Exception
     */
    public function transform($array)
    {
        return $array[0];
    }

    public function reverseTransform($string)
    {
        return array($string);
    }
}