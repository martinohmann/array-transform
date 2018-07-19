<?php declare(strict_types=1);

namespace ArrayTransform\Mapping;

use ArrayTransform\Rule\RuleFactoryInterface;
use ArrayTransform\Rule\RuleFactory;
use ArrayTransform\Exception\MappingException;
use ArrayTransform\Loader\LoaderInterface;
use ArrayTransform\Loader\YamlLoader;

class MappingFactory implements MappingFactoryInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var RuleFactoryInterface
     */
    private $ruleFactory;

    /**
     * @param LoaderInterface $loader
     * @param RuleFactoryInterface $ruleFactory
     */
    public function __construct(LoaderInterface $loader = null, RuleFactoryInterface $ruleFactory = null)
    {
        $this->loader = $loader ?? new YamlLoader();
        $this->ruleFactory = $ruleFactory ?? new RuleFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function createMappingFromFile(string $fileName): MappingInterface
    {
        $config = $this->loader->load($fileName);

        return $this->createMapping($config);
    }

    /**
     * {@inheritdoc}
     */
    public function createMapping(array $config): MappingInterface
    {
        $mapping = new Mapping();

        foreach ($config as $key => $keyConfig) {
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

            if ($key == '_global') {
                $this->processGlobalConfig($mapping, $keyConfig);
            } else {
                $this->processRuleConfig($mapping, $key, $keyConfig);
            }
        }

        return $mapping;
    }

    /**
     * @param Mapping $mapping
     * @param array $config
     * @return void
     */
    private function processGlobalConfig(Mapping $mapping, array $config)
    {
        if (isset($config['keySeparator'])) {
            $mapping->setKeySeparator((string) $config['keySeparator']);
        }
    }

    /**
     * @param Mapping $mapping
     * @param string $key
     * @param array $config
     * @return void
     */
    private function processRuleConfig(Mapping $mapping, string $key, array $config)
    {
        $rule = $this->ruleFactory->createRule($key, $config);

        $mapping->addRule($rule);
    }
}
