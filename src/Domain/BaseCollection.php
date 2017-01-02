<?php


namespace Collection\Domain;


abstract class BaseCollection implements CollectionInterface
{
    use Collectionnable;
    /**
     * @var array
     */
    protected $dataset;

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
    public function all() :array
    {
        return $this->dataset;
    }

    /**
     * @param $set
     * @return CollectionInterface
     */
    public function add($set) :CollectionInterface
    {
        $this->dataset[$this->getAnEmptyIndex()] = $set;

        return $this;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return array_key_exists($key, $this->dataset) ? $this->dataset[$key] : null;
    }

    /**
     * @param $key
     * @return CollectionInterface
     */
    public function delete($key) :CollectionInterface
    {
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
        usort($this->dataset, function ($a, $b) use ($key, $direction) {
            $getter = 'get' . ucfirst($key);
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
     * @throws \Exception
     * @deprecated Experimental, should not be used in production environment
     */
    public function groupBy($key) :array
    {
        Throw new \Exception("Not yet implemented");
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

    abstract function __clone();


}