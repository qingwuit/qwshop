<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FullReduction extends Model
{
    use SoftDeletes;
    protected $dates = ['start_time','end_time'];
}
