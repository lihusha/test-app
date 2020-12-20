<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Club
 * @package App\Models
 */
class Club extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'thumbnail_path'];

    /**
     * @return HasMany
     */
    public function domesticGames(): HasMany
    {
        return $this->hasMany(Game::class, 'host_id');
    }

    /**
     * @return HasMany
     */
    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'guest_id');
    }

    /**
     * @return HasMany
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * @return Collection
     */
    public function getGames(): Collection
    {
        return $this->domesticGames->merge($this->awayGames);
    }
}
