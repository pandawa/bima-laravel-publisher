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

use Bima\Client\Listener\SendQueryEvent;
use Illuminate\Database\Events\QueryExecuted;
use Pandawa\Component\Module\AbstractModule;
use Pandawa\Component\Module\Provider\EventProviderTrait;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class BimaClientModule extends AbstractModule
{
    use EventProviderTrait;

    protected function build(): void
    {
        $this->publishes(
            [
                __DIR__.'/Resources/bima.php' => $this->app['path.config'].DIRECTORY_SEPARATOR.'bima.php',
            ],
            'bima'
        );
    }

    protected function init(): void
    {
        $this->app->singleton(PublisherManager::class, fn($app) => new PublisherManager($app));
        $this->configure();;
    }

    /**
     * @return array
     */
    protected function listens(): array
    {
        return [
            QueryExecuted::class => [
                SendQueryEvent::class,
            ],
        ];
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/bima.php', 'bima'
        );
    }
}
