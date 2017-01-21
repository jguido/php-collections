<?php


namespace Tests\Collection;


use Collection\Domain\CollectionInterface;
use Collection\Map;
use Tests\Collection\Fixture\Builder\DatasetBuilder;
use Tests\Collection\Fixture\Set as TestSet;
use Tests\Collection\Fixture\SimpleDataset;

class MapTest extends \PHPUnit_Framework_TestCase
{
    use DatasetBuilder;

    public function testShouldCreateDataStructureWithArrayOfData()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        self::assertContains(spl_object_hash($datasetData[0]), array_keys($map->all()));
    }

    public function testAddingAddingAnArrayOfDataDataToMapShouldWork()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $map->add($set, spl_object_hash($set));
        self::assertContains(spl_object_hash($set), array_keys($map->all()));
    }

    public function testGetMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $sets = new SimpleDataset(10);
        $set = $sets->getDataset()[0];
        $set1 = $sets->getDataset()[1];
        $set2 = $sets->getDataset()[2];
        $set3 = $sets->getDataset()[3];
        $map->add($set, spl_object_hash($set));
        $map->add($set1, spl_object_hash($set1));
        $map->add($set2, spl_object_hash($set1));
        $map->add($set3, spl_object_hash($set1));
        self::assertEquals($set, $map->get($set));
        self::assertContains($set1, $map->get($set1));
    }

    public function testDeleteMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $map->add($set, spl_object_hash($set));
        self::assertEquals($set, $map->get($set));
        self::assertCount(11, $map->all());
        $map->delete($set);
        self::assertCount(10, $map->all());
        self::assertNull($map->get($set));

        $map->delete(0);
        self::assertCount(10, $map->all());
        self::assertNull($map->get(0));
    }

//    public function testFilterWithBigSetOfData()
//    {
//        $dataset = new SimpleDataset(1000000);
//        $datasetData = $dataset->getDataset();
//        $map = new Map($datasetData);
//        $filter = [];
//        $method = function() use ($map, &$filter){
//
//            $set1 = new TestSet('test', 'test', 66);
//            $set2 = new TestSet('test', 'test', 67);
//            $set3 = new TestSet('test', 'test', 68);
//            $set4 = new TestSet('test', 'test', 69);
//            $set5 = new TestSet('test', 'test', 70);
//            $map->add($set1);
//            $map->add($set2);
//            $map->add($set3);
//            $map->add($set4);
//            $map->add($set5);
//            $filter = $map->filter(['age' => function ($a) {
//                return $a->getAge() > 65;
//            }]);
//        };
//        Monitor::monitor($method);
//
//        self::assertCount(5, $filter);
//    }

    public function testFilterByMethodShouldReturnTheSetCorrespondingToTheGivenFilterSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);

        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $set1 = new TestSet('test', 'test', 20);
        $map->add($set, spl_object_hash($set));
        $map->add($set1, spl_object_hash($set1));

        self::assertEquals($set1, $map->filter(['age' => function($a){return $a->getAge() === 20;}])->get(spl_object_hash($set1)));
    }

    public function testSortingShouldSortAscendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new TestSet('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new TestSet('elder', 'elder', 99);
        $dataset[] = $elder;

        $map = new Map($dataset);
        $sorted = $map->sort('age', Map::SORT_ASCENDING);
        self::assertEquals($baby, $sorted->first());
        self::assertEquals($elder, $sorted->last());
    }

    public function testSortingShouldSortDescendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new TestSet('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new TestSet('elder', 'elder', 99);
        $dataset[] = $elder;

        $map = new Map($dataset);
        $sorted = $map->sort('age', Map::SORT_DESCENDING);
        self::assertEquals($baby, $sorted->last());
        self::assertEquals($elder, $sorted->first());
    }

    public function testGroupByShouldReturnGroupedMap()
    {
        $map = self::buildMapOfPerson();
        $grouped = $map->groupBy('age');

        self::assertInstanceOf(Map::class, $grouped);
        self::assertCount(11, $grouped->get('20'));
        self::assertCount(7, $grouped->get('50'));
        self::assertCount(1, $grouped->get('80'));
    }

    public function testA()
    {
        $map = new Map();
        $map->add(new TestSet('baby', 'baby', 1), 'baby');
        $map->add(new TestSet('child', 'child', 10), 'child');
        $map->add(new TestSet('man', 'man', 20), 'man');
        $map->add(new TestSet('dad', 'dad', 30), 'family');
        $map->add(new TestSet('mom', 'mom', 30), 'family');
        $map->add(new TestSet('elder', 'elder', 100), 'elder');
        $grouped = $map->groupBy('age');

        print_r($grouped);
    }

    public function testMapShouldReturnArrayOfInt()
    {
        $map = self::buildMapOfPerson();
        $intMapped = $map->map(function($el){return $el->getAge();});
        print_r($intMapped);
        foreach ($intMapped as $mapped) {
            self::assertTrue(is_integer($mapped));
        }
    }

    public function testMapShouldReturnArrayOfName()
    {
        $map = new Map();
        $map->add(new TestSet('baby', 'baby', 1), 'baby');
        $map->add(new TestSet('child', 'child', 10), 'child');
        $map->add(new TestSet('man', 'man', 20), 'man');
        $map->add(new TestSet('dad', 'dad', 30), 'family');
        $map->add(new TestSet('mom', 'mom', 30), 'family');
        $map->add(new TestSet('elder', 'elder', 100), 'elder');
        $function = function($el)  {
            return $el->getName();
        };
        $sort = $map->sort('age', CollectionInterface::SORT_ASCENDING);
        self::assertEquals('baby', $sort->first()->getName());

        $filter = $sort->filter(['age' => function ($a) {
            return $a->getAge() <= 20;
        }]);
        foreach ($filter->all() as $el) {
            self::assertNotEquals(new TestSet('mom', 'mom', 30), $el);
        }

        $mapped = $filter->map($function);
        self::assertEquals('baby', $mapped[0]);
        self::assertCount(3, $mapped);
    }

}