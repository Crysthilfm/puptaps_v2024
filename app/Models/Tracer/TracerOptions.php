<?php

namespace App\Models\Tracer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TracerOptions extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    public $timestamps = false;
    protected $table = 'tbl_tracer_options';
    protected $primaryKey = "option_id";
    protected $fillable = ['question_id','option_text'];
}
