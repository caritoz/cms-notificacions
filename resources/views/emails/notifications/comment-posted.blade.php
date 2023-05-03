@component('mail::message')
# You've got comments

<strong>{{$comment->user->name}}</strong> has written a comment on your post <strong>{{$comment->commentable->name}}</strong>.

@component('mail::button', ['url' => route('posts.edit', $comment->commentable->id)])
View comment
@endcomponent

{{--If you want these notifications to stop, you can edit them in the <a href="{{ route('notifications.index') }}">notifications settings</a> on the {{ config('app.name') }} app.--}}

@component('mail::subcopy', ['url' => ''])
    If you're having trouble clicking the "View comment" button, copy and paste the URL below into your web browser: <span class="break-all"><a href="{{ route('posts.edit', $comment->commentable->id) }}" target="_blank">{{ route('posts.edit', $comment->commentable->id) }}</a></span>
@endcomponent

@endcomponent
