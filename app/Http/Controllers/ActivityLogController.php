<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     * The table is strictly read-only for audit purposes.
     */
    public function index()
    {
        $activities = Activity::with('causer')->latest()->paginate(50);
        return view('activity.index', compact('activities'));
    }
}
