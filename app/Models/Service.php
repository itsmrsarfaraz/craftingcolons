<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['position', 'title', 'description', 'badges', 'quest_type', 'info', 'is_active'];
    protected $casts = ['badges' => 'array', 'is_active' => 'boolean'];
}
