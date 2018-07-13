<?php declare(strict_types=1);

namespace ArrayTransform\Key;

class KeyParser
{
    /**
     * @param string $key
     * @return Key
     */
    public function parseKey(string $key): Key
    {
        if (\preg_match('#^(.*)\[([a-z]+)\]$#', $key, $matches)) {
            return new Key($matches[1], $matches[2]);
        }

        return new Key($key);
    }
}
