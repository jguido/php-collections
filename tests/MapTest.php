<?php


namespace Tests\Collection;


use Collection\Map;
use Tests\Collection\Fixture\Builder\DatasetBuilder;
use Tests\Collection\Fixture\Set;
use Tests\Collection\Fixture\SimpleDataset;

class MapTest extends \PHPUnit_Framework_TestCase
{
    use DatasetBuilder;

    public function testMapShouldReturnTheDatasetGivenInConstructor()
    {
        $dataset = self::buildOneValidDataset();
        $map = new Map($dataset);
        self::assertEquals($dataset, $map->all());
    }

    public function testShouldCreateDataStructureWithArrayOfIdentifiable()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        self::assertContains(spl_object_hash($datasetData[0]), array_keys($map->all()));
    }

    public function testAddingAddingANotIdentifiableDataToMapShouldWork()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $map->add($simpleSet);
        self::assertContains($simpleSet, array_values($map->all()));
    }

    public function testAddingAddingAnIdentifiableDataToMapShouldWork()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $map->add($set);
        self::assertContains(spl_object_hash($set), array_keys($map->all()));
    }

    public function testGetMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $map->add($simpleSet);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $map->add($set);
        self::assertEquals($set, $map->get($set));
        self::assertEquals($simpleSet, $map->get(0));
    }

    public function testDeleteMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $map->add($simpleSet);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $map->add($set);
        self::assertEquals($set, $map->get($set));
        self::assertCount(12, $map->all());
        $map->delete($set);
        self::assertCount(11, $map->all());
        self::assertNull($map->get($set));

        $map->delete(0);
        self::assertCount(10, $map->all());
        self::assertNull($map->get(0));
    }

    public function testFilterByMethodShouldReturnTheSetCorrespondingToTheGivenFilterSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $map = new Map($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $map->add($simpleSet);
        $sets = new SimpleDataset(1);
        $set = $sets->getDataset()[0];
        $set1 = new Set('test', 'test', 20);
        $map->add($set);
        $map->add($set1);

        self::assertContains($set1, $map->filter(['age' => function($a){return $a->getAge() === 20;}]));
    }

    public function testSortingShouldSortAscendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new Set('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new Set('elder', 'elder', 99);
        $dataset[] = $elder;

        $map = new Map($dataset);
        $map->sort('age', Map::SORT_ASCENDING);
        self::assertEquals($baby, $map->first());
        self::assertEquals($elder, $map->last());
    }

    public function testSortingShouldSortDescendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new Set('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new Set('elder', 'elder', 99);
        $dataset[] = $elder;

        $map = new Map($dataset);
        $map->sort('age', Map::SORT_DESCENDING);
        self::assertEquals($baby, $map->last());
        self::assertEquals($elder, $map->first());
    }

    public function testGroupByMethodShouldReturnAnArrayIndexedByTheKeyGiven()
    {
        $map = self::buildMapOfPerson();
        $groupedMap = $map->groupBy('age');
        self::assertCount(7, $groupedMap);
        self::assertTrue($groupedMap['20'] instanceof Map);
        self::assertTrue($groupedMap['30'] instanceof Map);
        self::assertTrue($groupedMap['40'] instanceof Map);
        self::assertTrue($groupedMap['50'] instanceof Map);
        self::assertTrue($groupedMap['60'] instanceof Map);
        self::assertTrue($groupedMap['70'] instanceof Map);
        self::assertTrue($groupedMap['80'] instanceof Map);

        self::assertCount(11, $groupedMap['20']->all());
        self::assertCount(13, $groupedMap['30']->all());
        self::assertCount(6 , $groupedMap['40']->all());
        self::assertCount(7 , $groupedMap['50']->all());
        self::assertCount(1 , $groupedMap['60']->all());
        self::assertCount(1 , $groupedMap['70']->all());
        self::assertCount(1 , $groupedMap['80']->all());



    }

}