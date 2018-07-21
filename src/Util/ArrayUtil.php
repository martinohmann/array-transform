<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Util;

final class ArrayUtil
{
    /**
     * @param array $array
     * @param string $separator
     * @param string $prefix
     * @return array
     */
    public static function flatten(array $array, string $separator = '.', string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $keyPrefix = (empty($prefix) ? '' : $prefix.$separator).$key;

            if (!\is_array($value)) {
                $result[$keyPrefix] = $value;
            } else {
                $result = \array_merge(
                    $result,
                    static::flatten($value, $separator, $keyPrefix)
                );
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @param string $separator
     * @return array
     */
    public static function unflatten(array $array, string $separator = '.'): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $keyParts = \explode($separator, $key);

            $arrRef = &$result;

            foreach ($keyParts ?: [] as $keyPart) {
                $arrRef = &$arrRef[$keyPart];
            }

            $arrRef = $value;

            unset($arrRef);
        }

        return $result;
    }
}
