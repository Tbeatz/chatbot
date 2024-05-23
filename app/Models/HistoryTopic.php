<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTopic extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function history_date(){
        return $this->belongsTo(HistoryDate::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
