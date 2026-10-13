<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Wallet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        return [
            'wallets' => Wallet::where('user_id', $userId)->get(),
            'projects' => Project::where('user_id', $userId)->with('eligibility')->get(),
        ];
    }
}