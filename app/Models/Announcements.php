<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Announcements extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public $timestamps = false;
    protected $table = 'tbl_announcement';
    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'announcement_image',
        'announcement_title',
        'announcement_text',
        'created_at',
    ];
}
