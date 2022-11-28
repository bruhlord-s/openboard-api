<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class);
    }
}
