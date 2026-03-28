<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    protected $fillable = [
        'user_id', 'mood', 'journal', 'grateful', 'improve', 'todos', 'tags',
    ];

    protected $casts = [
        'todos' => 'array',
        'tags'  => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
