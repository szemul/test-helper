<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Helper;

class ArrayCleanupHelper
{
    /**
     * @param array<string,mixed> $array
     * @param string[]            $keysToRemove
     *
     * @return array<string,mixed>
     */
    public function removeKeysFromArray(array $array, array $keysToRemove): array
    {
        foreach (array_intersect(array_keys($array), $keysToRemove) as $key) {
            unset($array[$key]);
        }

        return $array;
    }
}
