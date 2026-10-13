<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mint_date' => 'nullable|date',
            'source_url' => 'nullable|string|max:255',
        ]);

        $project = Project::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($project, 201);
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mint_date' => 'nullable|date',
            'source_url' => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy(Request $request, Project $project)
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted']);
    }

    // WalletController
    public function trashed(Request $request)
    {
        return Project::onlyTrashed()
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function restore(Request $request, $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);

        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $project->restore();

        return response()->json($project);
    }

    public function forceDelete(Request $request, $id)
{
    $project = Project::onlyTrashed()->findOrFail($id);

    if ($project->user_id !== $request->user()->id) {
        abort(403);
    }

    $project->forceDelete();

    return response()->json(['message' => 'Project permanently deleted']);
}
}