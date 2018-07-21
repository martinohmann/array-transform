<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Transformer;

use ArrayTransform\Mapping\MappingInterface;
use ArrayTransform\Rule\RuleInterface;
use ArrayTransform\Util\ArrayUtil;

class Transformer implements TransformerInterface
{
    /**
     * @var MappingInterface
     */
    private $mapping;

    /**
     * @param MappingInterface $mapping
     */
    public function __construct(MappingInterface $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(array $data): array
    {
        return $this->doTransform($data, $this->mapping->getRules());
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform(array $data): array
    {
        return $this->doTransform($data, $this->mapping->getReverseRules());
    }

    /**
     * @param array $data
     * @param RuleInterface[] $rules
     * @return array
     */
    private function doTransform(array $data, array $rules): array
    {
        $result = [];
        $separator = $this->mapping->getKeySeparator();

        $data = ArrayUtil::flatten($data, $separator);

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            if (!empty($rule->getTargetKey())) {
                $result[$rule->getTargetKey()] = $rule->resolveValue($data);
            }
        }

        return ArrayUtil::unflatten($result, $separator);
    }
}
