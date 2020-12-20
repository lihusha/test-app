<?php

namespace App\Http\Controllers;

use App\Services\SimulatorService;
use App\Repositories\EloquentSummaryRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class SummaryController extends Controller
{
    /**
     * @var EloquentSummaryRepository
     */
    protected $repository;

    /**
     * @var SimulatorService
     */
    protected $simulator;

    /**
     * SummaryController constructor.
     * @param  EloquentSummaryRepository  $repository
     * @param  SimulatorService  $simulator
     */
    public function __construct(
        EloquentSummaryRepository $repository,
        SimulatorService $simulator
    ) {
        $this->repository = $repository;
        $this->simulator  = $simulator;
    }

    /**
     * @param  Request  $request
     * @param  EloquentSummaryRepository  $repository
     * @param  SimulatorService  $simulator
     * @return View|RedirectResponse
     */
    public function index(Request $request)
    {
        $week = $this->getValidatedWeekNumber($request);
        if (is_null($week)) {
            return redirect()->route('summary.index');
        }

        $summary = new LengthAwarePaginator(
            $this->repository->getSummaryByWeek($week),
            $this->repository->getTotalWeeks(), 1, $week
        );
        $summary->setPageName('week');

        return view('summary', [
            'summary'       => $summary,
            'formattedWeek' => (new \NumberFormatter(
                config('app.locale'), \NumberFormatter::ORDINAL)
            )->format($week),
            'weekData'      => $this->repository->getWeekData($week),
            'hasGames'      => $this->simulator->hasGames(),
        ]);
    }

    /**
     * @param  Request  $request
     * @return int|null
     */
    protected function getValidatedWeekNumber(Request $request): ?int
    {
        $week = (int) $request->input('week', 1);
        $totalWeeks = $this->repository->getTotalWeeks();

        if (($totalWeeks > 0 && $week > $totalWeeks) || $week < 1) {
            return null;
        }

        return $week;
    }
}
