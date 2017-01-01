<?php

namespace Collection\Domain;

interface CollectionInterface
{
    const SORT_ASCENDING  = 'ascending';
    const SORT_DESCENDING = 'descending';
    public function all();
    public function add($set);
    public function get($key);
    public function delete($key);
    public function filter(array $filterData);
    public function sort($key, $direction = self::SORT_ASCENDING);
    public function groupBy($key);
    public function first();
    public function last();
}