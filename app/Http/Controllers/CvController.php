<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class CvController
 * @package App\Http\Controllers
 */
class CvController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('cv');
    }
}
