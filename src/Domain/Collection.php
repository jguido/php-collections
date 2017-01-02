<?php

namespace Collection\Domain;

class Collection extends BaseCollection
{

    /**
     * @return Collection
     */
    function __clone()
    {
        return new Collection($this->dataset);
    }
}