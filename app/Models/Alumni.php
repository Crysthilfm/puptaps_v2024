<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Alumni extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $primaryKey = 'alumni_id';
    protected $table = 'tbl_alumni';
    public $timestamps = false;

    protected $fillable = [
        'stud_number',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'course_id',
        'batch',
        'sex',
        'birthday',
        'age',
        'religion',
        'civil_status',
        'city_address',
        'provincial_address',
        'email',
        'number',
        'user_pfp',
        'profile_status',
        'tracer_updated_at',
    ];
}
