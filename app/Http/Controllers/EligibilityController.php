<?php

namespace App\Http\Controllers;

use App\Models\Eligibility;
use Illuminate\Http\Request;

class EligibilityController extends Controller
{
    public function cycle(Request $request)
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $order = ['unchecked', 'eligible', 'not_eligible'];

        $record = Eligibility::firstOrCreate(
            ['wallet_id' => $validated['wallet_id'], 'project_id' => $validated['project_id']],
            ['status' => 'unchecked', 'method' => 'manual']
        );

        $next = $order[(array_search($record->status, $order) + 1) % count($order)];

        $record->update([
            'status' => $next,
            'method' => 'manual',
            'checked_at' => now(),
        ]);

        return $record;
    }
}