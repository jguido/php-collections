<?php


namespace Tests\Collection\Fixture;


use Tests\Collection\Fixture\Faker\Person;

class SimpleDataset
{
    /**
     * @var array
     */
    private $dataset;
    public function __construct($nb = 10)
    {
        $person = new Person();
        foreach (range(1, $nb) as $i) {
            $this->dataset[] = new Set($person::randomNumber(8).$i, $person->name(), $person::numberBetween(18,60));
        }
    }

    /**
     * @return array
     */
    public function getDataset()
    {
        return $this->dataset;
    }
}

class Set
{
    private $key;
    private $name;
    private $age;
    /**
     * @var array
     */
    private $children;

    public function __construct($key, $name, $age)
    {
        $this->key = $key;
        $this->name = $name;
        $this->age = $age;
        $this->children = [];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    public function addChild(Set $set)
    {
        $this->children[] = $set;
    }

    public function getChildren()
    {
        return $this->children;
    }
}