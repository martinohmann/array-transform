<?php

use ArrayTransform\Mapping\MappingFactory;
use ArrayTransform\Transformer\Transformer;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

try {
    $mappingFile = dirname(__FILE__) . '/example-mapping.yaml';

    $factory = new MappingFactory();
    $mapping = $factory->createMappingFromFile($mappingFile);

    $transformer = new Transformer($mapping);

    $result = $transformer->reverseTransform([
        'somekey' => 100,
    ]);

    var_dump($result);

    $result = $transformer->transform([
        'some' => [
            'key' => 20,
        ],
    ]);

    var_dump($result);
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
