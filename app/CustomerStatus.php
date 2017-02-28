<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerStatus extends Model
{
    protected $table = 'customer_status';

    public $timestamps = false;

    protected $fillable = ['id', 'status'];
}
