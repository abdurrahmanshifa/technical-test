<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
     use SoftDeletes;
     protected $table = 'tenant';

     function category()
     {
          return $this->BelongsTo('App\Models\CategoryTenant','category_id');
     }
}