<?php

namespace App\Transformers;

use App\Models\Club;
use App\Models\Game;
use League\Fractal\TransformerAbstract;

/**
 * Class ClubTransformer
 * @package App\Transformers
 */
class ClubTransformer extends TransformerAbstract
{
    /**
     * @param  Club  $club
     * @return array
     */
    public function transform(Club $club): array
    {
        return [
            'name'            => $club->name,
            'thumbnail_path'  => $club->thumbnail_path,
            'played'          => $club->domesticGames->count() +
                $club->awayGames->count(),
            'won'             => $this->getWonGames($club),
            'lost'            => $this->getLostGames($club),
            'drawn'           => $this->getDrawnGames($club),
            'goals_for'       => $this->getGoalsFor($club),
            'goals_against'   => $this->getGoalsAgainst($club),
            'goal_difference' => $this->getGoalsFor($club) -
                $this->getGoalsAgainst($club),
            'points'          => $this->getPoints($club),
        ];
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getGoalsFor(Club $club): int
    {
        return $club
            ->getGames()
            ->pluck('goals')
            ->collapse()
            ->where('club_id', $club->id)
            ->count();
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getGoalsAgainst(Club $club): int
    {
        return $club
            ->getGames()
            ->pluck('goals')
            ->collapse()
            ->where('club_id', '!=', $club->id)
            ->count();
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getWonGames(Club $club): int
    {
        $games = $club->getGames();

        return $games->transform(function (Game $item) use ($club) {
            return
                $item->goals->where('club_id', $club->id)->count() >
                $item->goals->where('club_id', '!=', $club->id)->count();

        })
            ->filter(function ($item) {
                return $item;
            })
            ->count();
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getLostGames(Club $club): int
    {
        $games = $club->getGames();

        return $games->transform(function (Game $item) use ($club) {
            return
                $item->goals->where('club_id', $club->id)->count() <
                $item->goals->where('club_id', '!=', $club->id)->count();
        })
            ->filter(function ($item) {
                return $item;
            })
            ->count();
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getDrawnGames(Club $club): int
    {
        $games = $club->getGames();

        return $games->transform(function (Game $item) use ($club) {
            return
                $item->goals->where('club_id', $club->id)->count() ==
                $item->goals->where('club_id', '!=', $club->id)->count();
        })
            ->filter(function ($item) {
                return $item;
            })
            ->count();
    }

    /**
     * @param  Club  $club
     * @return int
     */
    protected function getPoints(Club $club): int
    {
        return $this->getWonGames($club) * 3 + $this->getDrawnGames($club);
    }
}
