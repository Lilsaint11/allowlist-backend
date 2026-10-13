<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    protected $table = 'eligibility'; // <-- add this line

    protected $fillable = ['wallet_id', 'project_id', 'status', 'method', 'checked_at'];
    protected $casts = ['checked_at' => 'datetime'];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}