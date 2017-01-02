<?php

namespace Collection\Domain;

interface CollectionInterface
{
    const SORT_ASCENDING  = 'ascending';
    const SORT_DESCENDING = 'descending';

    /**
     * @return array
     */
    public function all() :array;

    /**
     * @param $set
     * @return CollectionInterface
     */
    public function add($set) :CollectionInterface;

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @return CollectionInterface
     */
    public function delete($key) :CollectionInterface;

    /**
     * @param array $filterData
     * @return array
     */
    public function filter(array $filterData) :array;

    /**
     * @param $key
     * @param string $direction
     * @return array
     */
    public function sort($key, $direction = self::SORT_ASCENDING) :array;

    /**
     * @param $key
     * @return array
     */
    public function groupBy($key) :array;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();
}