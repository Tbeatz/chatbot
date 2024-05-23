<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryChat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function history_topic(){
        return $this->belongsTo(HistoryTopic::class);
    }
}
