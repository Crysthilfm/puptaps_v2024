<?php

namespace App\Models\Tracer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TracerQuestions extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    public $timestamps = false;
    protected $table = "tbl_tracer_questions";
    protected $primaryKey = "question_id";
    protected $fillable = [
        "category_id",
        "question_text",
        "question_type",
        "tracer_version_id",
    ];
}
