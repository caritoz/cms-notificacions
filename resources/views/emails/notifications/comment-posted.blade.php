@component('mail::message')
# You've got comments

Comments on a post you've been writing <strong>{{$comment->commentable->name}}</strong> by <strong>{{$comment->user->name}}</strong>.

@component('mail::button', ['url' => route('posts.edit', $comment->commentable->id)])
View comment
@endcomponent

{{--If you want these notifications to stop, you can edit them in the <a href="{{ route('notifications.index') }}">notifications settings</a> on the {{ config('app.name') }} app.--}}

@component('mail::subcopy', ['url' => ''])
    If you're having trouble clicking the "View comment" button, copy and paste the URL below into your web browser: <span class="break-all"><a href="{{ route('posts.edit', $comment->commentable->id) }}" target="_blank">{{ route('posts.edit', $comment->commentable->id) }}</a></span>
@endcomponent

@endcomponent
