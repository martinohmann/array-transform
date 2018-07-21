<?php
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
