<?php

namespace Michalsn\CodeIgniterSessionExtended\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;
use Config\Session;
use Michalsn\CodeIgniterSessionExtended\DatabaseHandler;

class Uninstall extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Session Extended';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'se:uninstall';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Remove previously added fields from the session database table';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'se:uninstall';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $session = config('Session');

        if (! $session instanceof Session) {
            CLI::error('Session config file not found.');

            return;
        }

        if ($session->driver !== DatabaseHandler::class) {
            CLI::error('Incorrect Database Handler.');

            return;
        }

        $DBGroup = $session->DBGroup ?? config(Database::class)->defaultGroup;
        $forge   = Database::forge($DBGroup);

        $fields = ['user_id', 'user_agent'];

        CLI::write(sprintf(
            'Removing fields from the session table: %s.',
            implode(', ', $fields)
        ));

        $forge->dropColumn($session->savePath, $fields);

        CLI::write('Session table updated.', 'green');
    }
}
