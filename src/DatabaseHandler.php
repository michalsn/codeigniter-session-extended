<?php

namespace Michalsn\CodeIgniterSessionExtended;

use CodeIgniter\Session\Handlers\DatabaseHandler as BaseDatabaseHandler;

/**
 * Base database session handler
 *
 * Do not use this class. Use database specific handler class.
 */
class DatabaseHandler extends BaseDatabaseHandler
{
    /**
     * Writes the session data to the session storage.
     *
     * @param string $id   The session ID
     * @param string $data The encoded session data
     */
    public function write($id, $data): bool
    {
        if ($this->lock === false) {
            return $this->fail();
        }

        if ($this->sessionID !== $id) {
            $this->rowExists = false;
            $this->sessionID = $id;
        }

        if ($this->rowExists === false) {
            $insertData = [
                'id'         => $this->idPrefix . $id,
                'ip_address' => $this->ipAddress,
                'data'       => $this->prepareData($data),
                'user_id'    => user_id(),
                'user_agent' => substr(service('request')->getUserAgent()->getAgentString(), 0, 255),
            ];

            if (! $this->db->table($this->table)->set('timestamp', 'now()', false)->insert($insertData)) {
                return $this->fail();
            }

            $this->fingerprint = md5($data);
            $this->rowExists   = true;

            return true;
        }

        $builder = $this->db->table($this->table)->where('id', $this->idPrefix . $id);

        if ($this->matchIP) {
            $builder = $builder->where('ip_address', $this->ipAddress);
        }

        $updateData = [];

        if ($this->fingerprint !== md5($data)) {
            $updateData['data'] = $this->prepareData($data);
        }

        if (! $builder->set('timestamp', 'now()', false)->update($updateData)) {
            return $this->fail();
        }

        $this->fingerprint = md5($data);

        return true;
    }
}
