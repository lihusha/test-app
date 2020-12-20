<?php

namespace App\Http\Controllers;

use App\Services\SimulatorService;
use Illuminate\Http\RedirectResponse;

/**
 * Class PlayController
 * @package App\Http\Controllers
 */
class PlayController extends Controller
{
    /**
     * @param  SimulatorService  $simulator
     * @return RedirectResponse
     */
    public function play(SimulatorService $simulator)
    {
        $week = $simulator->hasGames() ? $simulator->simulateNextWeek() : null;

        return redirect(route('summary.index', [
            'week' => is_int($week) ? $week : 1
        ]));
    }
}
