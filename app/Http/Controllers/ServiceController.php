<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

/**
 * Class ServiceController
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * @return string
     */
    public function resetSchedule(): string
    {
        Artisan::call('schedule:reset');

        return redirect()->route('summary.index');
    }
}
