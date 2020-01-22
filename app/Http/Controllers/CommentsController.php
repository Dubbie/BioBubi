<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Elment egy megjegyzést
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

    /**
     * Frissít egy megjegyzést.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request) {
        $validated_data = $request->validate([
            'edit_comment_id' => 'required',
            'edit_message' => 'required',
        ]);

        $comment = Comment::find($validated_data['edit_comment_id']);

        // Nézzük meg, hogy az övé-e az a megjegyzés
        if ($comment->user_id != Auth::id()) {
            return redirect(url()->previous())->with([
                'error' => 'Ez a megjegyzés nem tartozik a felhasználódhoz.'
            ]);
        }

        $comment->message = $validated_data['edit_message'];
        $comment->save();

        return redirect(url()->previous())->with([
            'success' => 'Megjegyzés sikeresen frissítve.'
        ]);
    }

    public function delete($id) {
        $comment = Comment::find($id);

        if ($comment->user_id != Auth::id()) {
            return redirect(url()->previous())->with([
                'error' => 'Ez a megjegyzés nem tartozik a felhasználódhoz.'
            ]);
        }

        $comment->delete();
        return redirect(url()->previous())->with([
            'success' => 'Megjegyzés sikeresen törölve.'
        ]);
    }
}
