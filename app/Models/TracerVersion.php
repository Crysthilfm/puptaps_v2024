<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerVersion extends Model
{
    use HasFactory;

    protected $table = 'tbl_tracer_form_version';
    protected  $primaryKey = 'tracer_version_id';
}
