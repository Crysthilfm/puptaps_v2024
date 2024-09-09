<?php

namespace App\Models\Forms\Sas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SasCategories extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = "form_sas_categories";
    protected $primaryKey = "category_id";
    protected $fillable = [
        "form_id",
        "category_name",
    ];
}
