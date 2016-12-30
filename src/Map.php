<?php


namespace Collection;


class Map
{
    const SORT_ASCENDING  = 'ascending';
    const SORT_DESCENDING = 'descending';
    /**
     * @var array
     */
    private $dataset;

    /**
     * Map constructor.
     * @param array|null $dataset
     */
    public function __construct(array $dataset = null)
    {
        $this->dataset = [];
        if (is_array($dataset) && count($dataset) > 0) {
            foreach ($dataset as $set) {
                $this->add($set);
            }
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->dataset;
    }

    /**
     * @param $set
     * @return $this
     */
    public function add($set)
    {
        if (is_object($set)) {
            $this->dataset[spl_object_hash($set)] = $set;
        } else {
            $this->dataset[$this->getAnEmptyIndex()] = $set;
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
        return array_key_exists($key, $this->dataset) ? $this->dataset[$key] : null;
    }

    /**
     * @param $key
     * @return $this
     */
    public function delete($key)
    {
        if (is_object($key)) {
            $key = spl_object_hash($key);
        }
        if (count($this->dataset) > 0) {
            $tmpDataset = [];
            foreach ($this->dataset as $keyOfSet => $set) {
                if ($key !== $keyOfSet) {
                    $tmpDataset[$keyOfSet] = $set;
                }
            }
            $this->dataset = $tmpDataset;
        }

        return $this;
    }

    /**
     * @param array $filterData
     * @return array
     */
    public function filter(array $filterData)
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
     */
    public function sort($key, $direction = self::SORT_ASCENDING)
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
    }

    /**
     * @param $key
     * @return array
     */
    public function groupBy($key)
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

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->dataset);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->dataset);
    }

    /**
     * @param int $index
     * @return int
     */
    private function getAnEmptyIndex($index = 0)
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
    private final function applyFilterTo(array $filteredDataset, $key, $filter)
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
    private final function compare($a, $b, $direction = self::SORT_ASCENDING)
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
    private function buildSubset($key, $set)
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
