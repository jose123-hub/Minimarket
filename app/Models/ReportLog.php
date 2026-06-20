<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
    protected $table = 'report_logs';

    protected $fillable = [
        'report_type',
        'parameters',
        'generated_at',
        'format',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}