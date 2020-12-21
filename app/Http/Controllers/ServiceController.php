<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function resetSchedule()
    {
        Artisan::call('schedule:reset');

        return redirect()->route('summary.index');
    }
}
