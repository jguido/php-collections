<?php


namespace Tests\Collection\Fixture\Faker;


class Faker
{
    /**
     * Returns a random number between 0 and 9
     *
     * @return integer
     */
    public static function randomDigit()
    {
        return mt_rand(0, 9);
    }

    /**
     * Returns a random number between 1 and 9
     *
     * @return integer
     */
    public static function randomDigitNotNull()
    {
        return mt_rand(1, 9);
    }

    /**
     * Generates a random digit, which cannot be $except
     *
     * @param int $except
     * @return int
     */
    public static function randomDigitNot($except)
    {
        $result = self::numberBetween(0, 8);
        if ($result >= $except) {
            $result++;
        }
        return $result;
    }

    /**
     * Returns a random integer with 0 to $nbDigits digits.
     *
     * The maximum value returned is mt_getrandmax()
     *
     * @param integer $nbDigits Defaults to a random number between 1 and 9
     * @param boolean $strict   Whether the returned number should have exactly $nbDigits
     * @example 79907610
     *
     * @return integer
     */
    public static function randomNumber($nbDigits = null, $strict = false)
    {
        if (!is_bool($strict)) {
            throw new \InvalidArgumentException('randomNumber() generates numbers of fixed width. To generate numbers between two boundaries, use numberBetween() instead.');
        }
        if (null === $nbDigits) {
            $nbDigits = static::randomDigitNotNull();
        }
        $max = pow(10, $nbDigits) - 1;
        if ($max > mt_getrandmax()) {
            throw new \InvalidArgumentException('randomNumber() can only generate numbers up to mt_getrandmax()');
        }
        if ($strict) {
            return mt_rand(pow(10, $nbDigits - 1), $max);
        }

        return mt_rand(0, $max);
    }

    /**
     * Return a random float number
     *
     * @param int       $nbMaxDecimals
     * @param int|float $min
     * @param int|float $max
     * @example 48.8932
     *
     * @return float
     */
    public static function randomFloat($nbMaxDecimals = null, $min = 0, $max = null)
    {
        if (null === $nbMaxDecimals) {
            $nbMaxDecimals = static::randomDigit();
        }

        if (null === $max) {
            $max = static::randomNumber();
            if ($min > $max) {
                $max = $min;
            }
        }

        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }

        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $nbMaxDecimals);
    }

    /**
     * Returns a random number between $int1 and $int2 (any order)
     *
     * @param integer $int1 default to 0
     * @param integer $int2 defaults to 32 bit max integer, ie 2147483647
     * @example 79907610
     *
     * @return integer
     */
    public static function numberBetween($int1 = 0, $int2 = 2147483647)
    {
        $min = $int1 < $int2 ? $int1 : $int2;
        $max = $int1 < $int2 ? $int2 : $int1;
        return mt_rand($min, $max);
    }

    /**
     * Returns a random letter from a to z
     *
     * @return string
     */
    public static function randomLetter()
    {
        return chr(mt_rand(97, 122));
    }

    /**
     * Returns a random ASCII character (excluding accents and special chars)
     */
    public static function randomAscii()
    {
        return chr(mt_rand(33, 126));
    }

    /**
     * Returns randomly ordered subsequence of $count elements from a provided array
     *
     * @param  array            $array           Array to take elements from. Defaults to a-f
     * @param  integer          $count           Number of elements to take.
     * @param  boolean          $allowDuplicates Allow elements to be picked several times. Defaults to false
     * @throws \LengthException When requesting more elements than provided
     *
     * @return array New array with $count elements from $array
     */
    public static function randomElements(array $array = array('a', 'b', 'c'), $count = 1, $allowDuplicates = false)
    {
        $allKeys = array_keys($array);
        $numKeys = count($allKeys);

        if (!$allowDuplicates && $numKeys < $count) {
            throw new \LengthException(sprintf('Cannot get %d elements, only %d in array', $count, $numKeys));
        }

        $highKey = $numKeys - 1;
        $keys = $elements = array();
        $numElements = 0;

        while ($numElements < $count) {
            $num = mt_rand(0, $highKey);

            if (!$allowDuplicates) {
                if (isset($keys[$num])) {
                    continue;
                }
                $keys[$num] = true;
            }

            $elements[] = $array[$allKeys[$num]];
            $numElements++;
        }

        return $elements;
    }

    /**
     * Returns a random element from a passed array
     *
     * @param  array $array
     * @return mixed
     */
    public static function randomElement($array = array('a', 'b', 'c'))
    {
        if (!$array) {
            return null;
        }
        $elements = static::randomElements($array, 1);

        return $elements[0];
    }
}
