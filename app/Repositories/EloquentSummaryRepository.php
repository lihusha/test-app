<?php

namespace App\Repositories;

use App\Models\Club;
use App\Models\Game;
use App\Transformers\ClubTransformer;
use App\Transformers\GameTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Manager as FractalManager;

/**
 * Class EloquentSummaryRepository
 * @package App\Repositories
 */
class EloquentSummaryRepository
{
    /**
     * @var FractalCollection
     */
    public $fractalCollection;

    /**
     * @var FractalManager
     */
    public $fractalManager;

    /**
     * @var ClubTransformer
     */
    protected $clubTransformer;

    /**
     * @var GameTransformer
     */
    protected $gameTransformer;

    /**
     * EloquentSummaryRepository constructor.
     * @param  FractalCollection  $fractalCollection
     * @param  FractalManager  $fractalManager
     * @param  ClubTransformer  $clubTransformer
     * @param  GameTransformer  $gameTransformer
     */
    public function __construct(
        FractalCollection $fractalCollection,
        FractalManager $fractalManager,
        ClubTransformer $clubTransformer,
        GameTransformer $gameTransformer
    ) {
        $this->fractalCollection = $fractalCollection;
        $this->fractalManager    = $fractalManager;
        $this->clubTransformer   = $clubTransformer;
        $this->gameTransformer   = $gameTransformer;
    }

    /**
     * @param  int  $weekNumber
     * @return array|null
     */
    public function getSummaryByWeek(int $weekNumber): ?array
    {
        $summaryData = Club::query()
            ->with([
                'domesticGames' => function (HasMany $query) use ($weekNumber) {
                    $query
                        ->select('id', 'host_id', 'is_finished')
                        ->where('is_finished', true)
                        ->where('week_number', '<=', $weekNumber);
                },
                'awayGames' => function (HasMany $query) use ($weekNumber) {
                    $query
                        ->select('id', 'guest_id', 'is_finished')
                        ->where('is_finished', true)
                        ->where('week_number', '<=', $weekNumber);
                },
                'domesticGames.goals' => function (HasMany $query) {
                    $query->select('id', 'game_id', 'club_id');
                },
                'awayGames.goals' => function (HasMany $query) {
                    $query->select('id', 'game_id', 'club_id');
                },
            ])->get();

        $this->fractalCollection->setData($summaryData);
        $this->fractalCollection->setTransformer($this->clubTransformer);

        $resultData = $this->fractalManager
            ->createData($this->fractalCollection)->toArray();

        return collect(data_get($resultData, 'data'))
            ->sortByDesc('points')
            ->toArray();
    }

    /**
     * @param  int  $weekNumber
     * @return array|mixed
     */
    public function getWeekData(int $weekNumber): array
    {
        $weekData = Game::query()
            ->with([
                'host' => function (HasOne $query) {
                    $query->select('id', 'name', 'thumbnail_path');
                },
                'guest' => function (HasOne $query) {
                    $query->select('id', 'name', 'thumbnail_path');
                },
                'goals' => function (HasMany $query) {
                    $query->select('id', 'game_id', 'club_id');
                },
            ])
            ->where('week_number', $weekNumber)
            ->select('id', 'host_id', 'guest_id', 'week_number', 'is_finished')
            ->get();

        $this->fractalCollection->setData($weekData);
        $this->fractalCollection->setTransformer($this->gameTransformer);

        $resultData = $this->fractalManager
            ->createData($this->fractalCollection)->toArray();

        return data_get($resultData, 'data');
    }

    /**
     * @return int
     */
    public function getTotalWeeks(): int
    {
        return Game::query()
            ->select(DB::raw('count(id)'), 'week_number')
            ->groupBy('week_number')
            ->get()
            ->count();
    }
}
