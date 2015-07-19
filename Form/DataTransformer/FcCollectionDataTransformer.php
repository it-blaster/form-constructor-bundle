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

                if (is_array($row)) {
                    foreach ($row as $cell) {
                        if (!empty($cell)) {
                            $is_empty = false;
                        }
                    }
                } else {
                    if (!empty($row)) {
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
