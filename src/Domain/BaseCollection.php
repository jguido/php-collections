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
                if (is_object($set)) {
                    $key = spl_object_hash($set);
                } elseif (is_array($set)) {
                    if (count($set) > 0) {
                        $key = spl_object_hash($set[0]);
                    } else {
                        $key = false;
                    }
                } else {
                    $key = $set;
                }
                if ($key && $set) {
                    $this->add($set, $key);
                }
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
     * @param $key
     * @return CollectionInterface
     */
    public function add($set, $key) :CollectionInterface
    {
        $key = !empty($key) ? $key : $this->getAnEmptyIndex();
        if (!array_key_exists($key, $this->dataset)) {
            $this->dataset[$key] = $set;
        } else {
            if (!is_array($this->dataset[$key])) {
                $tmp = $this->dataset[$key];
                $this->dataset[$key] = [];
                $this->dataset[$key][] = $tmp;
            }
            $this->dataset[$key][] = $set;
        }

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
                    $tmpDataset[$keyOfSet][] = $set;
                }
            }
            $this->dataset = $tmpDataset;
        }

        return $this;
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
     * @param array $array
     * @return array
     */
    protected function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });

        return $return;
    }


}