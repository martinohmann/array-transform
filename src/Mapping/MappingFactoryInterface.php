<?php declare(strict_types=1);

namespace ArrayTransform\Mapping;

use ArrayTransform\Exception\MappingException;
use ArrayTransform\Exception\ParseException;

interface MappingFactoryInterface
{
    /**
     * @param array $config
     * @return MappingInterface
     * @throws MappingException
     */
    public function createMapping(array $config): MappingInterface;

    /**
     * @param string $fileName
     * @return MappingInterface
     * @throws MappingException
     * @throws ParseException
     */
    public function createMappingFromFile(string $fileName): MappingInterface;
}
