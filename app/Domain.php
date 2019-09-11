<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $state = 'initialized';
    protected $fillable = [
        'name',
        'status',
        'state',
        'content_length',
        'body',
        'header',
        'content'
    ];

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}
