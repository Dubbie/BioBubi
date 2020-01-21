<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request) {
        $validated_data = $request->validate([
            'customer_id' => 'required',
            'message' => 'required',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->customer_id = intval($validated_data['customer_id']);
        $comment->message = $validated_data['message'];
        $comment->save();

        return redirect(url()->previous())->with([
            'success' => 'Megjegyzés sikeresen hozzáadva.'
        ]);
    }
}
