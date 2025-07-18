<?php

namespace App\Http\Controllers\Chat;

use App\Events\GlobalMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use ConsoleTVs\Profanity\Facades\Profanity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class SendMessage extends Controller
{
    public function create(SendMessageRequest $request)
    {
        $validated = $request->validated();

        $key = 'send-message:'.Auth::id();

        RateLimiter::hit($key);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => ['Too many messages. Please wait before sending again.']
            ], 429);
        }

        $cleaned = Profanity::blocker($validated['message'])->filter();

        $message = Auth::user()->messages()->create(['message' => $cleaned]);

        broadcast(new GlobalMessage($message));

        return response()->json(201);
    }
}
