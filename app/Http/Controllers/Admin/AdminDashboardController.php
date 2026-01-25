<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SchoolYear;

//SHOW ADMIN DASHBOARD

class AdminDashboardController extends Controller
{
    public function index()
    {
        $activeSy = SchoolYear::activeYear();

        return view('admin.dashboard', [
            'activeSy' => $activeSy,
            'orgCount' => Organization::count(),
            'syCount' => SchoolYear::count(),
        ]);
    }
}
