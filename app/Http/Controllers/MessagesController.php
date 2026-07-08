<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessagesController extends Controller
{
    public function show(Conversation $conversation): View
    {
        abort_unless(in_array(Auth::id(), $conversation->participants()), 403);

        return view('messages.show', ['conversation' => $conversation]);
    }
}
