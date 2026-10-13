<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'address', 'label'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function eligibility() {
        return $this->hasMany(Eligibility::class);
    }
}
