<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerStatus extends Model
{
    const STATUS_NEW = 1;

    const STATUS_REGISTERED = 2;

    const STATUS_REFUSED = 3;

    const STATUS_UNAVAILABLE = 4;

    protected $table = 'customer_status';

    public $timestamps = false;

    protected $fillable = ['id', 'status'];
}
