<?php

namespace App\Http\Controllers\Chat;

use App\Events\GlobalMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class SendMessage extends Controller
{


    public function create(Request $request) {
        $request->validate([
            'message' => ['required', 'string', 'max:255'],
        ]);

        broadcast(new GlobalMessage($request->message))->toOthers();

        return response()->json(['status' => 200, 'data' => $request->message]);
    }

}
