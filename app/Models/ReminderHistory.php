<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReminderHistory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'reminder_histories';
    protected $primaryKey = 'rh_id';

    protected $fillable = [
        'rh_id',
        'date_sent',
    ];
}
