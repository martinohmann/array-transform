<?php declare(strict_types=1);

namespace ArrayTransform\Mapping;

use ArrayTransform\Rule\RuleFactoryInterface;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Exception\MappingException;

class MappingFactory implements MappingFactoryInterface
{
    /**
     * @var RuleFactoryInterface
     */
    private $ruleFactory;

    /**
     * @param RuleFactoryInterface $ruleFactory
     */
    public function __construct(RuleFactoryInterface $ruleFactory = null)
    {
        $this->ruleFactory = $ruleFactory ?? new RuleFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function createMapping(array $config): MappingInterface
    {
        if (!isset($config['array_transform'])) {
            throw new MappingException('config is missing the "array_transform" field');
        }

        if (!\is_array($config['array_transform'])) {
            throw new MappingException('config field "array_transform" has to be an array');
        }

        $mapping = new Mapping();

        foreach ($config['array_transform'] as $key => $keyConfig) {
            if ($key === '_global') {
                $this->applyGlobalConfig($mapping, $keyConfig);
                continue;
            }

            if ($key === '_defaults') {
                // @TODO: implement me
                continue;
            }

            if (!\is_string($key)) {
                throw new MappingException(
                    \sprintf(
                        'expected key to be of type string, found "%s"',
                        \gettype($key)
                    )
                );
            }

            if (!\is_array($keyConfig)) {
                throw new MappingException(
                    \sprintf(
                        'expected field "%s" to be an array, found "%s"',
                        $key,
                        \gettype($keyConfig)
                    )
                );
            }

            $rule = $this->ruleFactory->createRule($key, $keyConfig);

            $mapping->addRule($rule);
        }

        return $mapping;
    }

    /**
     * @param Mapping $mapping
     * @param array $config
     * @return void
     */
    private function applyGlobalConfig(Mapping $mapping, array $config)
    {
        if (isset($config['keySeparator'])) {
            $mapping->setKeySeparator((string) $config['keySeparator']);
        }
    }
}
