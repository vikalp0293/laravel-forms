<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use URL;
use Auth;
use DB;
use Helpers;
use Modules\User\Entities\User;
use Modules\Trademarks\Entities\Trademarks;
use Modules\Design\Entities\Design;
use Modules\Domain\Entities\Domain;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function __construct() {

        /* Execute authentication filter before processing any request */
        // $this->middleware(['auth','2fa']);
        $this->middleware(['auth']);

        if (\Auth::check()) {
            return redirect('/');
        }

    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('dashboard::index');
    }

}
