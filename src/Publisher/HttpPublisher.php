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

use Bima\Client\Type\Enum\EventType;
use Bima\Client\Type\SqlEvent;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class HttpPublisher extends Publisher
{
    /**
     * @var Client
     */
    private $http;

    /**
     * @var string
     */
    private $projectId;

    /**
     * @var string
     */
    private $token;

    /**
     * Constructor.
     *
     * @param string $endpoint
     * @param string $projectId
     * @param string $token
     * @param float  $timeout
     */
    public function __construct($endpoint, $projectId, $token, $timeout = 60 * 5)
    {
        $this->http = new Client(['base_uri' => $endpoint, 'timeout' => $timeout]);
        $this->projectId = $projectId;
        $this->token = $token;
    }

    /**
     * @param SqlEvent $event
     */
    public function publish(SqlEvent $event): void
    {
        $this->http->post(
            sprintf('projects/%s/sql-events', $this->projectId),
            [
                RequestOptions::HEADERS => ['Authorization' => sprintf('Bearer %s', $this->token)],
                RequestOptions::JSON    => $this->prepare($event),
            ]
        );
    }
}
