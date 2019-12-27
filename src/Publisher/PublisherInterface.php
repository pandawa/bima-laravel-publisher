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

namespace Bima\Client\Publisher;

use Bima\Client\Type\SqlEvent;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
interface PublisherInterface
{
    public function publish(SqlEvent $event): void;
}
