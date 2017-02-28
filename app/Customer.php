<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = ['name', 'surname', 'phone', 'status_id', 'created_at', 'updated_at'];
}
