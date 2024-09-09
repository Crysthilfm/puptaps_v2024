<?php

namespace App\Models\Tracer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TracerAnswers extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = "tbl_tracer_answers";
    protected $primaryKey = "answer_id";
    protected $fillable = [
        "alumni_id",
        "question_id",
        "answer",
        "tracer_version_id",
    ];
    public $timestamps  = false;
}
