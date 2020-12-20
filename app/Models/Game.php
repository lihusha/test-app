<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Game
 * @package App\Models
 */
class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['host_id', 'guest_id', 'week_number', 'is_finished'];

    /**
     * @return HasOne
     */
    public function host(): HasOne
    {
        return $this->hasOne(Club::class, 'id', 'host_id');
    }

    /**
     * @return HasOne
     */
    public function guest(): HasOne
    {
        return $this->hasOne(Club::class, 'id', 'guest_id');
    }

    /**
     * @return HasMany
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }
}
