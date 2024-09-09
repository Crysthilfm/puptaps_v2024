<?php

namespace App\Models\Forms\Pds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PdsQuestions extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $table = "form_pds_questions";
    protected $primaryKey = "question_id";
    protected $fillable = [
        "category_id",
        "question_text",
        "question_type",
    ];
}
