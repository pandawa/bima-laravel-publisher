<?php
/**
 * This file is part of the Pandawa package.
 *
 * (c) 2019 Pandawa <https://github.com/pandawa>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bima\Client\Type;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class SqlEvent
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $bindings;

    /**
     * Constructor.
     *
     * @param string $sql
     * @param array  $bindings
     */
    public function __construct(string $sql, array $bindings = [])
    {
        $this->sql = $sql;
        $this->bindings = $bindings;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }
}
