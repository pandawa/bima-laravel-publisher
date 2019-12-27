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

namespace Bima\Client\Job;

use Bima\Client\PublisherManager;
use Bima\Client\Type\Enum\EventType;
use Bima\Client\Type\SqlEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Pandawa\Component\Ddd\AbstractModel;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class PublishEvent implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var SqlEvent
     */
    private $event;

    /**
     * Constructor.
     *
     * @param SqlEvent $event
     */
    public function __construct(SqlEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Handle publish the event.
     *
     * @param PublisherManager $publisher
     */
    public function handle(PublisherManager $publisher): void
    {
        $publisher->publish($this->event);
    }
}
