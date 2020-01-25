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

namespace Bima\Client;

use Bima\Client\Publisher\HttpPublisher;
use Bima\Client\Type\SqlEvent;
use Illuminate\Support\Manager;

/**
 * @method publish(SqlEvent $event)
 *
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class PublisherManager extends Manager
{
    public function createHttpDriver(): HttpPublisher
    {
        return new HttpPublisher(
            $this->app['config']['bima.drivers.http.endpoint'],
            $this->app['config']['bima.project_id'],
            $this->app['config']['bima.token'],
            $this->app['config']['bima.drivers.http.timeout']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultDriver(): string
    {
        if (is_null($this->app['config']['bima.driver'])) {
            return 'http';
        }

        return $this->app['config']['bima.driver'];
    }
}
