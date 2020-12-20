<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Goal;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class SimulatorService
 * @package App\Services
 */
class SimulatorService
{
    /**
     * @return mixed
     */
    public function simulateNextWeek()
    {
        DB::beginTransaction();

        $games = Game::query()
            ->where('week_number', function (Builder $query) {
                $query
                    ->select(DB::raw('min(week_number) as aggregate'))
                    ->from('games')
                    ->where('is_finished', false)
                    ->min('week_number');
            })->get();

        /** @var Game $game */
        foreach ($games as $game) {
            $this->simulateGameGoals($game);
            $this->simulateGameGoals($game, 'guest');
        }

        Game::query()
            ->whereIn('id', $games->pluck('id'))->update([
                'is_finished' => true,
            ]);
        DB::commit();

        return $games->pluck('week_number')->unique()->first();
    }

    /**
     * @return bool
     */
    public function hasGames(): bool
    {
        return Game::query()->where('is_finished', false)->count();
    }

    /**
     * @param  Game  $game
     * @param  string  $source
     * @return int
     */
    protected function simulateGameGoals(Game $game, string $source = 'host'): int
    {
        $amount = rand(0, 7);

        if ($amount == 0) {
            return 0;
        }

        Goal::factory()->count($amount)->create([
            'game_id' => $game->id,
            'club_id' => $source == 'host' ? $game->host_id : $game->guest_id,
        ]);

        return $amount;
    }
}
