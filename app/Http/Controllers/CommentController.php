<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Initialise the model's middleware through the constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('can:update,comment')->only('update');
        $this->middleware('can:delete,comment')->only('destroy');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body'                      =>  ['required', 'string', 'min:2'],
            'commentable_type'   =>  ['required', Rule::in(\App\Models\Comment::COMMENTABLE_MODELS)],
            'commentable_id'       =>  ['required', 'numeric', $request->has('commentable_type') &&$request->has('commentable_id') && $request->commentable_id
                ? Rule::in([( $entity = ($request->commentable_type)::find($request->commentable_id) )->id])
                : []
            ],
            'parent_id'                =>  ['nullable', Rule::in(Comment::all()->pluck('id')->all())]
        ]);

        if ( $validator->fails() ) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $comment = $entity->comments()->create(
            array_merge($validator->validated(), ['user_id' => Auth::user()->id ])
        );

        // check if the comment has a MENTION, then send a notification
        if( $request->has('mentions') ){
           // $this->sendNotifications($request->mentions, $comment);
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

    /**
     * @param array $mentions
     * @param Comment $comment
     * @return void
     */
    protected function sendNotifications(array $mentions, Comment $comment): void
    {
        foreach (\App\Models\User::whereIn('id', $mentions)->where('id', '!=', Auth::id())->get() as $userMentioned)
        {
//            $userMentioned->notify(new \App\Notifications\CommentUserMentioned($comment));
        }
    }
}
