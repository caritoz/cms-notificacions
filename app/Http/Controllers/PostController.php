<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Initialise the model's middleware through the constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('can:update,post')->only('update');
        $this->middleware('can:delete,post')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'filters' => Request::all(['search', 'trashed']),
            'posts' => Post::query()
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->orderByDesc('updated_at')
                ->filter(Request::only(['search', 'trashed']))
                ->paginate(6)
                ->withQueryString()
                ->through(fn ($post) => PostResource::make($post)),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        Post::create( array_merge($request->validated(), ['user_id' => Auth::user()->id ]) );

        return redirect(route('posts.index'))->with('success', 'Post created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post->load(['user','comments'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update(
            $request->validated()
        );

        return Redirect::back()->with('success', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete(); // check comments

        return redirect(route('posts.index'))->with('success', 'Post deleted.');
    }
}
