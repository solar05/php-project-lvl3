<?php


namespace App;

const STATE_INIT = 'initialized';
const STATE_PENDING = 'pending';
const STATE_FAILED = 'failed';
const STATE_COMPLETED = 'completed';


class Domain
{
    protected $domainUrl;
    protected $state;
    protected $id;

    public function __construct($domainUrl)
    {
        $this->domainUrl = $domainUrl;
        $this->state = STATE_INIT;
    }

    public function getUrl()
    {
        return $this->domainUrl;
    }

    public function getCurrentState()
    {
        return $this->state;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    public function pending()
    {
        if ($this->state === 'initialized' || $this->state === 'failed') {
            $this->state = STATE_PENDING;
        } else {
            throw new \Exception('Transit is forbidden');
        }
    }

    public function failed()
    {
        if ($this->state === 'pending') {
            $this->state = STATE_FAILED;
        } else {
            throw new \Exception('Transit is forbidden');
        }
    }

    public function completed()
    {
        if ($this->state === 'pending') {
            $this->state = STATE_COMPLETED;
        } else {
            throw new \Exception('Transit is forbidden');
        }
    }
}
