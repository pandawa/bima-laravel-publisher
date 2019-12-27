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

namespace Bima\Client\Listener;

use Bima\Client\Job\PublishEvent;
use Bima\Client\PublisherManager;
use Bima\Client\Type\SqlEvent;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use InvalidArgumentException;
use PhpMyAdmin\SqlParser\Components\Expression;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statements\AlterStatement;
use PhpMyAdmin\SqlParser\Statements\CreateStatement;
use PhpMyAdmin\SqlParser\Statements\DeleteStatement;
use PhpMyAdmin\SqlParser\Statements\DropStatement;
use PhpMyAdmin\SqlParser\Statements\InsertStatement;
use PhpMyAdmin\SqlParser\Statements\UpdateStatement;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class SendQueryEvent
{
    /**
     * @var PublisherManager
     */
    private $publisher;

    /**
     * Constructor.
     *
     * @param PublisherManager $publisher
     */
    public function __construct(PublisherManager $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param QueryExecuted $event
     */
    public function handle(QueryExecuted $event): void
    {
        if (preg_match('/^(update|insert|delete|drop|alter|create) (.+)/', $event->sql)) {
            if ($this->shouldBePublish($event->sql, $event->connection)) {
                $sqlEvent = new SqlEvent($event->sql, $event->bindings);

                if ($queue = $this->publishWithQueue()) {
                    dispatch(new PublishEvent($sqlEvent))
                        ->onQueue($queue)
                        ->onConnection($this->publishWithConnection());

                    return;
                }

                $this->publisher->publish($sqlEvent);
            }
        }
    }

    protected function shouldBePublish(string $sql, Connection $connection): bool
    {
        if (empty($tables = config('bima.tables'))) {
            return true;
        }

        $prefix = $connection->getTablePrefix();
        $sqlTables = $this->getTables($sql);

        foreach ($tables as $table) {
            $table = $prefix . $table;

            foreach ($sqlTables as $sqlTable) {
                if ($table === $sqlTable) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $sql
     *
     * @return array
     */
    protected function getTables(string $sql): array
    {
        $parser = new Parser($sql);
        $statement = $parser->statements[0];

        if ($statement instanceof InsertStatement) {
            return [$statement->into->dest->table];
        }

        if ($statement instanceof UpdateStatement) {
            return array_map(function (Expression $expression) {
                return $expression->table;
            }, $statement->tables);
        }

        if ($statement instanceof DeleteStatement) {
            return array_map(function (Expression $expression) {
                return $expression->table;
            }, $statement->from);
        }

        if ($statement instanceof DropStatement) {
            if (null !== $statement->table) {
                return [$statement->table->table];
            }

            return [$statement->fields[0]->table];
        }

        if ($statement instanceof CreateStatement) {
            if (null !== $statement->table) {
                return [$statement->table->table];
            }

            return [$statement->name->table];
        }

        if ($statement instanceof AlterStatement) {
            if (null !== $statement->table) {
                return [$statement->table->table];
            }

            return [$statement->fields[0]->table];
        }

        throw new InvalidArgumentException('Statement is not supported for this query.');
    }

    protected function publishWithQueue()
    {
        return config('bima.queue.queue');
    }

    protected function publishWithConnection(): ?string
    {
        return config('bima.queue.connection') ?: config('queue.default');
    }
}
