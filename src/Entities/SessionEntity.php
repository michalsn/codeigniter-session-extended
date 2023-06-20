<?php

namespace Michalsn\CodeIgniterSessionExtended\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\HTTP\UserAgent;

class SessionEntity extends Entity
{
    protected $attributes = [
        'id', 'ip_address', 'timestamp', 'data', 'user_id', 'user_agent',
    ];
    protected $dates = ['timestamp'];
    protected $casts = ['user_id' => 'int'];
    protected UserAgent $ua;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);

        $this->ua = new UserAgent();
        $this->ua->parse($this->attributes['user_agent']);
    }

    public function platform()
    {
        return $this->ua->getPlatform();
    }

    public function browser()
    {
        return sprintf('%s (%s)', $this->ua->getBrowser(), $this->ua->getVersion());
    }
}
