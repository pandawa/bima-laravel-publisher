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
use Illuminate\Support\ServiceProvider;
use Event;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class BimaClientServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__.'/Resources/bima.php' => $this->app['path.config'].DIRECTORY_SEPARATOR.'bima.php',
            ],
            'bima'
        );

        foreach ($this->listens() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    public function register(): void
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
