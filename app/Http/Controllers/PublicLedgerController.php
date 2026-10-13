<?php

namespace App\Http\Controllers;

use App\Models\Project;

class PublicLedgerController extends Controller
{
    public function show($shareId)
    {
        $project = Project::where('share_id', $shareId)
            ->with(['eligibility.wallet'])
            ->firstOrFail();

        return response()->json([
            'project' => [
                'name' => $project->name,
                'mint_date' => $project->mint_date,
                'source_url' => $project->source_url,
            ],
            'eligibility' => $project->eligibility->map(fn ($e) => [
                'wallet_label' => $e->wallet->label,
                'wallet_address' => $e->wallet->address,
                'status' => $e->status,
            ]),
        ]);
    }
}