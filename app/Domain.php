<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

const STATE_PENDING = 'pending';
const STATE_FAILED = 'failed';
const STATE_COMPLETED = 'completed';


class Domain extends Model
{

    protected $fillable = [
        'name',
        'state',
        'status',
        'content_length',
        'body',
        'header',
        'content'
    ];

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
