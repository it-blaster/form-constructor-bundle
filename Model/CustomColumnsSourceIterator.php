<?php
namespace Fenrizbes\FormConstructorBundle\Model;

use ArrayObject;
use Exporter\Source\SourceIteratorInterface;

class CustomColumnsSourceIterator implements SourceIteratorInterface {

    protected $custom_columns;
    protected $iterator;

    public function __construct($custom_columns)
    {
        $this->custom_columns = new ArrayObject($custom_columns);
        $this->iterator = $this->custom_columns->getIterator();
    }

    public function current()
    {
        return $this->iterator->current();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }
}