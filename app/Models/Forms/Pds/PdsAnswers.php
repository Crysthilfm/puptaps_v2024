<?php

namespace App\Models\Forms\Pds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PdsAnswers extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $table = "form_pds_answers";
    protected $primaryKey = "answer_id";
    protected $fillable = [
        "alumni_id",
        "question_id",
        "answer",
    ];
}
