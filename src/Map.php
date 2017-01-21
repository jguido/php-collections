<?php


namespace Collection;


use Collection\Domain\{
    BaseCollection, CollectionInterface
};

class Map extends BaseCollection  {

    /**
     * @param $set
     * @return $this|CollectionInterface
     */
    public function add($set, $key) :CollectionInterface
    {
        if (is_object($key)) {
            $this->dataset[spl_object_hash($key)] = $set;
        } else {
            parent::add($set, $key);
        }

        return $this;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }
        return parent::get($key);
    }

    /**
     * @param $key
     * @return $this|CollectionInterface
     */
    public function delete($key) :CollectionInterface
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }

        parent::delete($key);

        return $this;
    }

    /**
     * @param array $filterData
     * @return CollectionInterface
     */
    public function filter(array $filterData) :CollectionInterface
    {
        $filteredDataset = $this->all();
        if (count($filterData) > 0) {
            foreach ($filterData as $key => $filter) {
                $filteredDataset = $this->applyFilterTo($filteredDataset, $key, $filter);
            }
        }


        return new Map($filteredDataset);
    }

    /**
     * @param $key
     * @param string $direction
     * @return CollectionInterface
     */
    public function sort($key, $direction = self::SORT_ASCENDING) :CollectionInterface
    {
        $sortedData = (clone $this)->all();

        if (count($sortedData) <= 0) {
            return new Map($sortedData);
        }
        $data = $this->flatten($sortedData);
        usort($data, function($a, $b) use ($key, $direction){
            $getter = 'get'.ucfirst($key);
            if (method_exists($a, $getter) && method_exists($b, $getter)) {
                return $this->compare($a->$getter(), $b->$getter(), $direction);
            } else if (is_array($a) && is_array($b) && array_key_exists($key, $a) && array_key_exists($key, $b)) {
                return $this->compare($a[$key], $b[$key], $direction);
            } else {
                return $this->compare($a, $b, $direction);
            }
        });

        return new Map($data);
    }

    /**
     * @param $key
     * @return CollectionInterface
     */
    public function groupBy($key) :CollectionInterface
    {
        $groupedMap = new Map();
        if (count($this->dataset) <= 0) {
            return $groupedMap;
        }

        foreach ($this->dataset as $hash => $set) {
            if (is_array($set)) {
                array_map(function ($el) use ($key, &$groupedMap) {
                    $getter = 'get' . ucfirst($key);
                    if (is_object($el) && method_exists($el, $getter)) {
                        $groupedMap->add($el, $el->$getter());
                    }
                }, $set);
            } else {
                $getter = 'get' . ucfirst($key);
                if (is_object($set) && method_exists($set, $getter)) {
                    $groupedMap->add([$set], $set->$getter());
                }
            }
        }

        return $groupedMap;
    }

    /**
     * @return CollectionInterface
     */
    public function __clone()
    {
        return new Map($this->dataset);
    }

    /**
     * @param \Closure $map
     * @return array
     */
    public function map(\Closure $map): array
    {
        $data = $this->flatten($this->all());
        return array_map($map, $data);
    }
}
