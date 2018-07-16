<?php declare(strict_types=1);

namespace ArrayTransform\Mapping;

use ArrayTransform\Exception\MappingException;

interface MappingFactoryInterface
{
    /**
     * @param array $config
     * @return MappingInterface
     * @throws MappingException
     */
    public function createMapping(array $config): MappingInterface;
}
