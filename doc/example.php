<?php

use ArrayTransform\Loader\YamlLoader;
use ArrayTransform\Mapping\MappingFactory;
use ArrayTransform\Transformer\Transformer;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload';

try {
    $loader = new YamlLoader();
    $config = $loader->load(dirname(__FILE__) . '/example-mapping.yaml');

    $factory = new MappingFactory();

    $mapping = $factory->createMapping($config);

    $transformer = new Transformer($mapping);

    $result = $transformer->transform(['foo' => 1]);

    var_dump($result);
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
