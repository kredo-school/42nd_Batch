<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'title', 'category', 'period', 'target', 'current', 'unit', 'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute(): int
    {
        if ($this->target <= 0) return 0;
        return min(100, (int) round($this->current / $this->target * 100));
    }
}
