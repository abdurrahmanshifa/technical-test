<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
     use SoftDeletes;
     protected $table = 'transaction';

     function tenant()
     {
          return $this->BelongsTo('App\Models\Tenant','tenant_id');
     }

     function customer()
     {
          return $this->BelongsTo('App\Models\Customer','customer_id');
     }
}