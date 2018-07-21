<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Key;

use ArrayTransform\Exception\ParseException;

class KeyParser
{
    /**
     * @const string
     */
    const TYPED_KEY_PATTERN = '#^(?P<key>.*)\[(?P<type>[a-z]+)\]$#';

    /**
     * @const array
     */
    const VALID_TYPES = [
        'int',
        'integer',
        'bool',
        'boolean',
        'string',
        'float',
        'double',
        'object',
        'array',
    ];

    /**
     * @param string $key
     * @return TypedKey
     */
    public function parseKey(string $key): TypedKey
    {
        if (\preg_match(self::TYPED_KEY_PATTERN, $key, $matches)) {
            if (!\in_array($matches['type'], self::VALID_TYPES)) {
                throw new ParseException(
                    \sprintf(
                        '"%s" is not a valid type, allowed types: "%s"',
                        $matches['type'],
                        \implode('", "', self::VALID_TYPES)
                    )
                );
            }

            return new TypedKey($matches['key'], $matches['type']);
        }

        return new TypedKey($key);
    }
}
