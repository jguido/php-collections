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
     * @param $key
     * @return CollectionInterface
     */
    public function add($set, $key) :CollectionInterface;

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
     * @return CollectionInterface
     */
    public function filter(array $filterData) :CollectionInterface;

    /**
     * @param \Closure $map
     * @return array
     */
    public function map(\Closure $map) : array;

    /**
     * @param $key
     * @param string $direction
     * @return CollectionInterface
     */
    public function sort($key, $direction = self::SORT_ASCENDING) :CollectionInterface;

    /**
     * @param $key
     * @return CollectionInterface
     */
    public function groupBy($key) :CollectionInterface;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @return CollectionInterface
     */
    public function __clone();
}
