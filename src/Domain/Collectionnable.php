<?php


namespace Collection\Domain;


trait Collectionnable
{

    /**
     * @param int $index
     * @return int
     */
    protected function getAnEmptyIndex($index = 0)
    {
        if (array_key_exists($index, $this->dataset)) {
            return $this->getAnEmptyIndex($index+1);
        }

        return $index;
    }

    /**
     * @param array $filteredDataset
     * @param $key
     * @param $filter
     * @return array
     */
    protected function applyFilterTo(array $filteredDataset, $key, $filter)
    {
        if ($filter instanceof \Closure) {
            return array_filter($filteredDataset, function ($el) use ($key, $filter) {
                $getter = 'get' . ucfirst($key);
                if (method_exists($el, $getter)) {

                    return $filter($el);
                } else {
                    return [];
                }
            });
        } else {
            return array_filter($filteredDataset, function($el)use($key, $filter){
                if (is_array($el) && array_key_exists($key, $el)) {
                    return  $el[$key] === $filter;
                } else {
                    return $el === $filter;
                }
            });
        }
    }

    /**
     * @param $a
     * @param $b
     * @param $direction
     * @return int
     */
    protected function compare($a, $b, $direction = self::SORT_ASCENDING)
    {
        if ($a == $b) {
            return 0;
        }
        switch ($direction) {
            case self::SORT_ASCENDING:
                return $a < $b ? -1 : 1;
                break;
            case self::SORT_DESCENDING:
                return $a < $b ? 1 : -1;
                break;
        }
    }

    /**
     * @param $key
     * @param $set
     * @return array
     * @todo manage subset for each tuple of data
     */
    protected function buildSubset($key, $set)
    {
        $subset = [];
        $getter = 'get' . ucfirst($key);
        if (method_exists($set, $getter)) {
            if (is_array($set->$getter()) && count($set->$getter()) > 0) {
                foreach ($set->$getter() as $subDataset) {
                    $subset[spl_object_hash($subset)][] = new Map($subDataset);
                }

                return $subset;
            } else {
                $subset[$set->$getter()][] = $set;
            }
        }

        return $subset;
    }
}