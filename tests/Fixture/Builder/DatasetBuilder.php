<?php


namespace Tests\Collection\Fixture\Builder;


use Collection\Map;
use Tests\Collection\Fixture\Faker\Person;
use Tests\Collection\Fixture\Set;

trait DatasetBuilder
{
    /**
     * @return Map
     */
    private static function buildMapOfPerson()
    {
        $person1 = new Set("persone1", "persone1", 20);
        $person2 = new Set("persone2", "persone2", 20);
        $person3 = new Set("persone3", "persone3", 20);
        $person4 = new Set("persone4", "persone4", 20);
        $person5 = new Set("persone5", "persone5", 20);
        $person6 = new Set("persone6", "persone6", 20);
        $person7 = new Set("persone7", "persone7", 20);
        $person8 = new Set("persone8", "persone8", 20);
        $person9 = new Set("persone9", "persone9", 20);
        $person10 = new Set("persone10", "persone10", 20);
        $person11 = new Set("persone11", "persone11", 30);
        $person11->addChild(new Set('child111', 'child1', 10));
        $person11->addChild(new Set('child112', 'child2', 6));

        $person12 = new Set("persone12", "persone12", 30);
        $person13 = new Set("persone13", "persone13", 30);
        $person14 = new Set("persone14", "persone14", 30);
        $person14->addChild(new Set('child141', 'child1', 10));
        $person14->addChild(new Set('child112', 'child2', 6));

        $person15 = new Set("persone15", "persone15", 30);
        $person16 = new Set("persone16", "persone16", 30);
        $person17 = new Set("persone17", "persone17", 30);
        $person18 = new Set("persone18", "persone18", 30);
        $person18->addChild(new Set('child111', 'child1', 10));
        $person18->addChild(new Set('child112', 'child2', 6));

        $person19 = new Set("persone19", "persone19", 30);
        $person20 = new Set("persone20", "persone20", 30);
        $person21 = new Set("persone21", "persone21", 30);
        $person22 = new Set("persone22", "persone22", 30);
        $person23 = new Set("persone23", "persone23", 30);
        $person24 = new Set("persone24", "persone24", 20);
        $person24->addChild(new Set('child111', 'child1', 10));
        $person24->addChild(new Set('child112', 'child2', 6));

        $person25 = new Set("persone25", "persone25", 40);
        $person26 = new Set("persone26", "persone26", 40);
        $person27 = new Set("persone27", "persone27", 40);
        $person28 = new Set("persone28", "persone28", 40);
        $person29 = new Set("persone29", "persone29", 40);
        $person30 = new Set("persone30", "persone30", 40);
        $person30->addChild(new Set('child111', 'child1', 10));
        $person30->addChild(new Set('child112', 'child2', 6));

        $person31 = new Set("persone31", "persone31", 50);
        $person32 = new Set("persone32", "persone32", 50);
        $person33 = new Set("persone33", "persone33", 50);
        $person33->addChild(new Set('child111', 'child1', 10));
        $person33->addChild(new Set('child112', 'child2', 6));

        $person34 = new Set("persone34", "persone34", 50);
        $person35 = new Set("persone35", "persone35", 50);
        $person36 = new Set("persone36", "persone36", 50);
        $person36->addChild(new Set('child361', 'child1', 20));
        $person36->addChild(new Set('child362', 'child2', 15));

        $person37 = new Set("persone37", "persone37", 50);
        $person38 = new Set("persone38", "persone38", 60);
        $person39 = new Set("persone39", "persone39", 70);
        $person40 = new Set("persone40", "persone40", 80);
        $map = new Map();
        $map->add($person1);
        $map->add($person2);
        $map->add($person3);
        $map->add($person4);
        $map->add($person5);
        $map->add($person6);
        $map->add($person7);
        $map->add($person8);
        $map->add($person9);
        $map->add($person10);
        $map->add($person11);
        $map->add($person12);
        $map->add($person13);
        $map->add($person14);
        $map->add($person15);
        $map->add($person16);
        $map->add($person17);
        $map->add($person18);
        $map->add($person19);
        $map->add($person20);
        $map->add($person21);
        $map->add($person22);
        $map->add($person23);
        $map->add($person24);
        $map->add($person25);
        $map->add($person26);
        $map->add($person27);
        $map->add($person28);
        $map->add($person29);
        $map->add($person30);
        $map->add($person31);
        $map->add($person32);
        $map->add($person33);
        $map->add($person34);
        $map->add($person35);
        $map->add($person36);
        $map->add($person37);
        $map->add($person38);
        $map->add($person39);
        $map->add($person40);

        return $map;
    }

    /**
     * @return array
     */
    private static function buildOneValidDataset()
    {
        $dataset = [];
        $person = new Person();
        foreach (range(0, 10) as $i) {
            $dataset[] = $person->name();
        }

        return $dataset;
    }
}