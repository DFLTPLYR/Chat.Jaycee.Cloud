<?php

namespace App\Http\Controllers\Chat;

use App\Events\GlobalMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\SendMessageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Blaspsoft\Blasp\Facades\Blasp;

class SendMessage extends Controller
{
    public function create(SendMessageRequest $request)
    {
        $validated = $request->validated();

        $key = 'send-message:'.Auth::id();

        RateLimiter::hit($key);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'errors' => [
                    'messages' => ['Too many messages. Please wait before sending again.'],
                ],
            ], 429);
        }

        $cleaned = Blasp::check($validated['message'])->getCleanString();

        $message = Auth::user()->messages()->create(['message' => $cleaned]);

        broadcast(new GlobalMessage($message));

        return response()->json(201);
    }
}
