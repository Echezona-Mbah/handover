<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationSuggestionController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $actor = Auth::guard('nurse')->user()
            ?? Auth::guard('doctor')->user()
            ?? Auth::guard('dentist')->user();
        abort_unless($actor && $actor->hospital_id, 403);

        $query = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ])['q'];

        $names = Medication::query()
            ->where('hospital_id', $actor->hospital_id)
            ->whereNotNull('medication')
            ->where('medication', 'like', '%'.$query.'%')
            ->select('medication')
            ->distinct()
            ->orderBy('medication')
            ->limit(15)
            ->pluck('medication');

        return response()->json($names);
    }
}
