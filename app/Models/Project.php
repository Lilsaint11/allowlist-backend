<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'name', 'mint_date', 'source_url'];
    protected $casts = ['mint_date' => 'datetime'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function eligibility() {
        return $this->hasMany(Eligibility::class);
    }

    protected static function booted()
{
    static::creating(function ($project) {
        $project->share_id = (string) \Illuminate\Support\Str::uuid();
    });
}
}
