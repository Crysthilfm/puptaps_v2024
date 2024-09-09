<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Courses extends Model implements Auditable
{ 
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_courses';

    protected $fillable = [
        'course_id',
        'course_description'
    ];
}
