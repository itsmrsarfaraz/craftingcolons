<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['position', 'title', 'kind', 'categories', 'tagline', 'details', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
