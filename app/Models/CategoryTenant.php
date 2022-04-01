<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTenant extends Model
{
     use SoftDeletes;
     protected $table = 'tenant_category';
}