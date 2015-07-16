<?php

namespace Fenrizbes\FormConstructorBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class FcCollectionDataTransformer implements DataTransformerInterface
{
    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data)
    {
        $result = array();

        if (is_array($data)) {
            foreach ($data as $row) {
                $is_empty = true;

                foreach ($row as $cell) {
                    if (!empty($cell)) {
                        $is_empty = false;
                    }
                }

                if (!$is_empty) {
                    $result[] = $row;
                }
            }
        }

        return $result;
    }
}
