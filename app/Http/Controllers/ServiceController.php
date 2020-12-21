<?php

namespace App\Http\Controllers;

use App\Services\ResetScheduleService;
use Illuminate\Http\RedirectResponse;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function resetSchedule(ResetScheduleService $service)
    {
        $service->handle();

        return redirect()->route('summary.index');
    }
}
