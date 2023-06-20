<?php

namespace Michalsn\CodeIgniterSessionExtended\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use Config\Database;
use Config\Session;
use Michalsn\CodeIgniterSessionExtended\Entities\SessionEntity;

class SessionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = '';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = SessionEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        $session       = config(Session::class);
        $this->DBGroup = $session->DBGroup ?? config(Database::class)->defaultGroup;
        $this->table   = $session->savePath;

        parent::__construct($db, $validation);
    }
}
