<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'lawyer_id',
        'status_title',
        'description',
        'private_notes',
        'status_date',
    ];

    protected function casts(): array
    {
        return [
            'status_date' => 'date',
        ];
    }

    public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function documents()
    {
        return $this->hasMany(CaseDocument::class, 'status_log_id');
    }
}
