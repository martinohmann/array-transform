<?php declare(strict_types=1);

namespace ArrayTransform\Key;

use ArrayTransform\Exception\ParseException;

class KeyParser
{
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
        if (\preg_match('#^(.*)\[([a-z]+)\]$#', $key, $matches)) {
            $type = $matches[2];

            if (!\in_array($type, self::VALID_TYPES)) {
                throw new ParseException(
                    \sprintf(
                        '"%s" is not a valid type, allowed types: "%s"',
                        $type,
                        implode('", "', self::VALID_TYPES)
                    )
                );
            }

            return new TypedKey($matches[1], $type);
        }

        return new TypedKey($key);
    }
}
