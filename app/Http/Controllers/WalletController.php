<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'label' => 'nullable|string|max:255',
        ]);
        
        $wallet = Wallet::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($wallet, 201);
    }

    public function destroy(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== $request->user()->id) {
            abort(403);
        }

        $wallet->delete();

        return response()->json(['message' => 'Wallet deleted']);
    }

    // WalletController
    public function trashed(Request $request)
    {
        return Wallet::onlyTrashed()
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function restore(Request $request, $id)
    {
        $wallet = Wallet::onlyTrashed()->findOrFail($id);

        if ($wallet->user_id !== $request->user()->id) {
            abort(403);
        }

        $wallet->restore();

        return response()->json($wallet);
    }

    public function forceDelete(Request $request, $id)
{
    $wallet = Wallet::onlyTrashed()->findOrFail($id);

    if ($wallet->user_id !== $request->user()->id) {
        abort(403);
    }

    $wallet->forceDelete(); // permanently removes the row, bypasses soft delete

    return response()->json(['message' => 'Wallet permanently deleted']);
}
}