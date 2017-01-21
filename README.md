# PHP Collections API

## Map
A Map will contain tuples of data indexed by a hash (if tuple is an object) or by a numerical index based on the next numerical free index.
### __construct
constructor can be used with an array (empty or not) or nothing
```
$map = new Map();
$map = new Map[1, 2, 3];
```
### all()
Will return the whole dataset with indexes
```
$map = new Map[1, 2, 3];
print_r($map->all());
```
while print :
```
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
)
```
### add($tuple)
Add a tuple to the dataset with a computed index
```
$map = new Map[1, 2, 3];
$map->add(5);
print_r($map->all());
```
while print :
```
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 5
)
```
### get($key)
Return the tuple by its index $key, if $key does not exists then return null
```
$map = new Map[1, 2, 3];
$map->add(5);
var_dump($map->get(0));
var_dump($map->get(5));
```
while print :
```
int(1)
NULL
```
### delete($key)
Delete the tuple by a given key, if key is object a hash will be computed.
```
$map = new Map([1, 2, 3]);
$map->add(5);
print_r($map->all());
$map->delete(0);
print_r($map->all());
$map->delete(5);
print_r($map->all());
```
while print :
```
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 5
)
Array
(
    [1] => 2
    [2] => 3
    [3] => 5
)
Array
(
    [1] => 2
    [2] => 3
    [3] => 5
)
```
#### For filter, sort methods i used an object called Set defined by:
- public function __construct($key, $name, $age)
- 3 properties: key(string), name(string) and age(int)
- Get accessor for each properties

### filter()
Filter the tuples with a given condition and return a Map.
```
$set1 = new Set("persone1", "persone1", 20);
$set2 = new Set("persone2", "persone2", 45);
$set3 = new Set("persone3", "persone3", 33);
$map = new Map();
$map->add($set1);
$map->add($set2);
$map->add($set3);
$filtered1 = $map->filter(['age' => function($a){return $a->getAge() === 20;}]);
$filtered2 = $map->filter(['age' => function($a){return $a->getAge() > 20;}]);
```


### sort()
Sort the tuples by a given key and an optional direction (default to ascending) and return a Map.
```
$set1 = new Set("persone1", "persone1", 20);
$set2 = new Set("persone2", "persone2", 45);
$set3 = new Set("persone3", "persone3", 33);
$map = new Map();
$map->add($set1);
$map->add($set2);
$map->add($set3);
$map->sort('age', Map::SORT_ASCENDING);
```

### groupBy()
Regroup dataset in map with a given condition and return a Map
```
$map->add(new TestSet('baby', 'baby', 1), 'baby');
$map->add(new TestSet('child', 'child', 10), 'child');
$map->add(new TestSet('man', 'man', 20), 'man');
$map->add(new TestSet('dad', 'dad', 30), 'family');
$map->add(new TestSet('mom', 'mom', 30), 'family');
$map->add(new TestSet('elder', 'elder', 100), 'elder');
```

while print :
```
Collection\Map Object
(
    [dataset:protected] => Array
        (
            [1] => Array
                (
                    [0] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => baby
                            [name:Tests\Collection\Fixture\Set:private] => baby
                            [age:Tests\Collection\Fixture\Set:private] => 1
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                )

            [10] => Array
                (
                    [0] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => child
                            [name:Tests\Collection\Fixture\Set:private] => child
                            [age:Tests\Collection\Fixture\Set:private] => 10
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                )

            [20] => Array
                (
                    [0] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => man
                            [name:Tests\Collection\Fixture\Set:private] => man
                            [age:Tests\Collection\Fixture\Set:private] => 20
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                )

            [30] => Array
                (
                    [0] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => dad
                            [name:Tests\Collection\Fixture\Set:private] => dad
                            [age:Tests\Collection\Fixture\Set:private] => 30
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                    [1] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => mom
                            [name:Tests\Collection\Fixture\Set:private] => mom
                            [age:Tests\Collection\Fixture\Set:private] => 30
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                )

            [100] => Array
                (
                    [0] => Tests\Collection\Fixture\Set Object
                        (
                            [key:Tests\Collection\Fixture\Set:private] => elder
                            [name:Tests\Collection\Fixture\Set:private] => elder
                            [age:Tests\Collection\Fixture\Set:private] => 100
                            [children:Tests\Collection\Fixture\Set:private] => Array
                                (
                                )

                        )

                )

        )

)
```

### map()
Apply a function to all members and return an array of data
```
$map = new Map();
$map->add(new TestSet('baby', 'baby', 1), 'baby');
$map->add(new TestSet('child', 'child', 10), 'child');
$map->add(new TestSet('man', 'man', 20), 'man');
$map->add(new TestSet('dad', 'dad', 30), 'family');
$map->add(new TestSet('mom', 'mom', 30), 'family');
$map->add(new TestSet('elder', 'elder', 100), 'elder');
$mapped = $map->map(function($el){return $el->getAge();});
print_r($mapped);
```

while print :
```
Array
(
    [0] => 1
    [1] => 10
    [2] => 20
    [3] => 30
    [4] => 30
    [5] => 100
)
```

### first()
Return the first tuple of the dataset
```
$set1 = new Set("persone1", "persone1", 20);
$set2 = new Set("persone2", "persone2", 45);
$set3 = new Set("persone3", "persone3", 33);
$map = new Map();
$map->add($set1);
$map->add($set2);
$map->add($set3);
$map->sort('age', Map::SORT_ASCENDING);
print_r($map->first());
```
will print :
```
Tests\Collection\Fixture\Set Object
(
    [key:Tests\Collection\Fixture\Set:private] => persone1
    [name:Tests\Collection\Fixture\Set:private] => persone1
    [age:Tests\Collection\Fixture\Set:private] => 20
    [children:Tests\Collection\Fixture\Set:private] => Array
        (
        )

)
```

### last()
Return the last tuple of the dataset
```
$set1 = new Set("persone1", "persone1", 20);
$set2 = new Set("persone2", "persone2", 45);
$set3 = new Set("persone3", "persone3", 33);
$map = new Map();
$map->add($set1);
$map->add($set2);
$map->add($set3);
$map->sort('age', Map::SORT_ASCENDING);
print_r($map->last());
```
will print :
```
Tests\Collection\Fixture\Set Object
(
    [key:Tests\Collection\Fixture\Set:private] => persone2
    [name:Tests\Collection\Fixture\Set:private] => persone2
    [age:Tests\Collection\Fixture\Set:private] => 45
    [children:Tests\Collection\Fixture\Set:private] => Array
        (
        )

)
```
