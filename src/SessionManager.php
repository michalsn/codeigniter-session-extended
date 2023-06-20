<?php

namespace Michalsn\CodeIgniterSessionExtended;

use Michalsn\CodeIgniterSessionExtended\Models\SessionModel;

/**
 * Session manager
 */
class SessionManager
{
    protected SessionModel $model;

    public function __construct()
    {
        $this->model = model(SessionModel::class);
    }

    public function list(?int $userId = null): array
    {
        if ($userId !== null) {
            $this->model->where('user_id', $userId);
        }

        return $this->model
            ->orderBy('timestamp', 'desc')
            ->findAll();
    }

    public function delete(string $id, ?int $userId = null): bool
    {
        if ($userId !== null) {
            $this->model->where('user_id', $userId);
        }

        return $this->model->delete($id);
    }
}
