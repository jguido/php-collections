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
            return $this->postFilter(array_filter($filteredDataset, function ($el) use ($key, $filter) {
                $getter = 'get' . ucfirst($key);

                if (method_exists($el, $getter)) {

                    return $filter($el);
                } else {
                    return false;
                }
            }));
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
     * @param array $dataset
     * @return array
     */
    private function postFilter(array $dataset)
    {
        $postFilterDataset = [];
        if (count($dataset) <= 0) {
            return [];
        }

        foreach ($dataset as $hash => $set) {
            if (count($set) > 0) {
                $postFilterDataset[$hash] = $set;
            }
        }

        return $postFilterDataset;
    }

    /**
     * @param $a
     * @param $b
     * @param $direction
     * @return int
     */
    protected function compare($a, $b, $direction = self::SORT_ASCENDING): int
    {
        if ($a == $b) {
            return 0;
        }
        switch ($direction) {
            default:
            case self::SORT_ASCENDING:
                return $a < $b ? -1 : 1;
                break;
            case self::SORT_DESCENDING:
                return $a < $b ? 1 : -1;
                break;
        }
    }
}