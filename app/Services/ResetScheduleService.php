<?php

namespace App\Services;

use App\Models\Club;
use App\Models\Game;
use App\Models\Goal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class ResetScheduleService
 * @package App\Services
 */
class ResetScheduleService
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();
        Goal::query()->truncate();
        Game::query()->truncate();
        Schema::enableForeignKeyConstraints();

        DB::beginTransaction();

        try {
            $clubs = Club::query()->get()->pluck('id')->toArray();

            $firstHalfSeasonData = $this->getScheduleData($clubs);
            $lastHalfSeasonData  = $this->getScheduleData($clubs, false);
            $gamesData           = [];
            foreach (array_merge($firstHalfSeasonData, $lastHalfSeasonData) as $key => $games) {
                foreach ($games as $game) {
                    $gamesData[] = [
                        'host_id'     => data_get($game, 'host'),
                        'guest_id'    => data_get($game, 'guest'),
                        'week_number' => $key + 1,
                    ];
                }
            }
            foreach (array_chunk($gamesData, 100) as $items) {
                Game::query()->insert($items);
            }
            DB::commit();

            return 1;
        } catch (\Throwable $e) {
            DB::rollBack();

            return 0;
        }
    }

    /**
     * @param  array  $clubs
     * @param  bool  $firstHalfSeason
     * @return array
     * @throws \Throwable
     */
    protected function getScheduleData(
        array $clubs,
        bool $firstHalfSeason = true
    ): array {
        throw_if(
            count($clubs) % 2 != 0,
            new \DomainException('Amount of clubs must be even number')
        );

        $guests = array_splice($clubs, (count($clubs) / 2));
        $hosts   = $clubs;
        for ($i = 0; $i < count($hosts) + count($guests) - 1; $i++) {
            for ($j = 0; $j < count($hosts); $j++) {
                if ($firstHalfSeason) {
                    $scheduleData[$i][$j]['host']  = $hosts[$j];
                    $scheduleData[$i][$j]['guest'] = $guests[$j];
                } else {
                    $scheduleData[$i][$j]['guest']  = $hosts[$j];
                    $scheduleData[$i][$j]['host'] = $guests[$j];
                }
            }
            if (count($hosts) + count($guests) - 1 > 2) {
                $spliced = array_splice($hosts, 1, 1);
                array_unshift($guests, array_shift($spliced));
                array_push($hosts, array_pop($guests));
            }
        }

        return $scheduleData;
    }
}
