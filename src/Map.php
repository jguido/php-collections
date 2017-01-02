<?php


namespace Collection;


use Collection\Domain\{Collection, CollectionInterface};

class Map extends Collection {

    /**
     * @param $set
     * @return $this|CollectionInterface
     */
    public function add($set) :CollectionInterface
    {
        if (is_object($set)) {
            $this->dataset[spl_object_hash($set)] = $set;
        } else {
            parent::add($set);
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
     * @return array
     */
    public function filter(array $filterData) :array
    {
        $filteredDataset = $this->all();
        if (count($filterData) > 0) {
            foreach ($filterData as $key => $filter) {
                $filteredDataset = $this->applyFilterTo($filteredDataset, $key, $filter);
            }
        }


        return $filteredDataset;
    }

    /**
     * @param $key
     * @param string $direction
     * @return array
     */
    public function sort($key, $direction = self::SORT_ASCENDING) :array
    {
        usort($this->dataset, function($a, $b) use ($key, $direction){
            $getter = 'get'.ucfirst($key);
            if (method_exists($a, $getter) && method_exists($b, $getter)) {
                return $this->compare($a->$getter(), $b->$getter(), $direction);
            } else if (is_array($a) && is_array($b) && array_key_exists($key, $a) && array_key_exists($key, $b)) {
                return $this->compare($a[$key], $b[$key], $direction);
            } else {
                return $this->compare($a, $b, $direction);
            }
        });

        return $this->dataset;
    }

    /**
     * @param $key
     * @return array
     * @deprecated Experimental, should not be used in production environment
     */
    public function groupBy($key) :array
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }
        $groupedDataset = [];
        if (count($this->dataset) > 0) {
            foreach ($this->dataset as $keyOfDataset => $set) {
                if (is_object($set)) {
                    $getter = 'get' . ucfirst($key);
                    if (method_exists($set, $getter)) {
                        if (is_array($set->$getter()) && count($set->$getter()) > 0) {
                            foreach ($set->$getter() as $subSet) {
                                $groupedDataset[spl_object_hash($subSet)][] = new Map($subSet);
                            }
                        } else {
                            $groupedDataset[$set->$getter()][] = $set;
                        }
                    }

                } else if (is_array($set) && array_key_exists($key, $set)) {
                    $groupedDataset[$set[$key]][] = $set;
                }
            }
            $tmpGroupedDataset = [];
            foreach ($groupedDataset as $a => $dataset) {
                if ($dataset instanceof Map) {
                    $tmpGroupedDataset[$a] = $dataset;
                } else {
                    $tmpGroupedDataset[$a] = new Map($dataset);
                }
            }
            $groupedDataset = $tmpGroupedDataset;
        }

        return $groupedDataset;
    }

}
