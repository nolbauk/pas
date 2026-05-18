<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreprocessingResult extends Model
{
    protected $fillable = [
        'dataset_id',
        'original_text',
        'processed_text'
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }
}