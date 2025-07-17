<?php

namespace App\Http\Controllers\Chat;

use App\Events\GlobalMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use App\Models\Message;
use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Support\Facades\Auth;

class SendMessage extends Controller
{
    public function create(SendMessageRequest $request)
    {
        $validated = $request->validated();

        if (empty($validated['message'])) {
            return response()->json([
                'error' => 'Message cannot be empty',
                'data' => $validated,
            ], 400);
        }

        $cleaned = Profanity::blocker($validated['message'])->filter();

        $message = Auth::user()->messages()->create(['message' => $cleaned]);

        broadcast(new GlobalMessage($message));

        return response()->json(201);
    }
}
