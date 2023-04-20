<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'body'                      =>  ['required', 'string', 'min:2'],
            'commentable_type'   =>  ['required', Rule::in(\App\Models\Comment::COMMENTABLE_MODELS)],
            'commentable_id'       =>  ['required', 'numeric', Rule::in([( $entity = ($request->commentable_type)::find($request->commentable_id) )->id])],
            'parent_id'                =>  ['nullable', Rule::in(Comment::all()->pluck('id')->all())]
        ]);

        $comment = $entity->comments()->create(
            array_merge($validatedData, ['user_id' => Auth::user()->id ])
        );

        // check if the comment has a MENTION, then send a notification
        if( $request->has('mentions') ){
//            $this->sendNotifications($request->mentions, $comment);
        }

        return Redirect::back()->with('success', 'Comment sent.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update( $request->validate([
            'body'  => ['required', 'string', 'min:2'],
        ]) );

        return Redirect::back()->with('success', 'Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return Redirect::back()->with('success', 'Comment deleted.');
    }
}
