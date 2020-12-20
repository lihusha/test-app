<?php

namespace App\Transformers;

use App\Models\Club;
use App\Models\Game;
use League\Fractal\TransformerAbstract;

/**
 * Class GameTransformer
 * @package App\Transformers
 */
class GameTransformer extends TransformerAbstract
{
    /**
     * @param  Game  $game
     * @return array|array[]
     */
    public function transform(Game $game): array
    {
        return [
            'host' => [
                'name'           => $game->host->name,
                'thumbnail_path' => $game->host->thumbnail_path,
                'goals'          => $this->getClubGoals($game, $game->host),
            ],
            'guest' => [
                'name'           => $game->guest->name,
                'thumbnail_path' => $game->guest->thumbnail_path,
                'goals'          => $this->getClubGoals($game, $game->guest),
            ],
        ];
    }

    /**
     * @param  Game  $game
     * @param  Club  $club
     * @return string
     */
    protected function getClubGoals(Game $game, Club $club): string
    {
        $count = $game->goals->where('club_id', $club->id)->count();

        return ($count == 0) && ! $game->is_finished ? '' : $count;
    }
}
