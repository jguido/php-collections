<?php


namespace Tests\Collection;


use Collection\Set;
use Tests\Collection\Fixture\Builder\DatasetBuilder;
use Tests\Collection\Fixture\Set as TestSet;
use Tests\Collection\Fixture\SimpleDataset;

class SetTest extends \PHPUnit_Framework_TestCase
{
    use DatasetBuilder;

    public function testMapShouldReturnTheDatasetGivenInConstructor()
    {
        $dataset = self::buildOneValidDataset();
        $set = new Set($dataset);
        self::assertEquals($dataset, $set->all());
    }

    public function testShouldCreateDataStructureWithArrayOfIdentifiable()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        self::assertEquals($datasetData[0], $set->all()[0]);
    }

    public function testAddingAddingANotIdentifiableDataToMapShouldWork()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $set->add($simpleSet);
        self::assertContains($simpleSet, array_values($set->all()));
    }

    public function testAddingAddingAnIdentifiableDataToMapShouldWork()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        $sets = new SimpleDataset(1);
        $setData = $sets->getDataset()[0];
        $set->add($setData);
        $key = count($set->all()) - 1;
        self::assertEquals($setData, $set->get($key));
    }

    public function testGetMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $set->add($simpleSet);
        $key1 = count($set->all()) - 1;
        $sets = new SimpleDataset(1);
        $setData = $sets->getDataset()[0];
        $set->add($setData);
        $key2 = count($set->all()) - 1;
        self::assertEquals($setData, $set->get($key2));
        self::assertEquals($simpleSet, $set->get($key1));
    }

    public function testDeleteMethodShouldReturnCorrespondingSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $set->add($simpleSet);
        $sets = new SimpleDataset(1);
        $setData = $sets->getDataset()[0];
        $set->add($setData);
        $key = count($set->all()) - 1;
        self::assertEquals($setData, $set->get($key));
        self::assertCount(12, $set->all());
        $set->delete($key);
        self::assertCount(11, $set->all());
        self::assertNull($set->get($key));

        $set->delete(0);
        self::assertCount(10, $set->all());
        self::assertNull($set->get(0));
    }

    public function testFilterByMethodShouldReturnTheSetCorrespondingToTheGivenFilterSet()
    {
        $dataset = new SimpleDataset(10);
        $datasetData = $dataset->getDataset();
        $set = new Set($datasetData);
        $simpleSet = self::buildOneValidDataset()[0];
        $set->add($simpleSet);
        $sets = new SimpleDataset(1);
        $setData = $sets->getDataset()[0];
        $set1 = new TestSet('test', 'test', 20);
        $set->add($setData);
        $set->add($set1);

        self::assertContains($set1, $set->filter(['age' => function($a){return $a->getAge() === 20;}]));
    }

    public function testSortingShouldSortAscendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new TestSet('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new TestSet('elder', 'elder', 99);
        $dataset[] = $elder;

        $set = new Set($dataset);
        $set->sort('age', Set::SORT_ASCENDING);
        self::assertEquals($baby, $set->first());
        self::assertEquals($elder, $set->last());
    }

    public function testSortingShouldSortDescendingByAgeTheDataset()
    {
        $simpleDataset = new SimpleDataset(10);
        $dataset = $simpleDataset->getDataset();
        $baby = new TestSet('baby', 'baby', 1);
        $dataset[] = $baby;
        $elder = new TestSet('elder', 'elder', 99);
        $dataset[] = $elder;

        $set = new Set($dataset);
        $set->sort('age', Set::SORT_DESCENDING);
        self::assertEquals($baby, $set->last());
        self::assertEquals($elder, $set->first());
    }

}