<?php declare(strict_types=1);
/*
 * This file is part of the array-transform package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArrayTransform\Key;

class TypedKey
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var null|string
     */
    private $type;

    /**
     * @param string $name
     * @param null|string $type
     */
    public function __construct(string $name, ?string $type = null)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasType(): bool
    {
        return !empty($this->type);
    }
}
