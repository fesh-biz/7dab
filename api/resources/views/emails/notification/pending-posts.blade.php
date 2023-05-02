<h1>Pending Posts</h1>

@if ($pendingPosts->count() > 0)
    <p>There are {{ $pendingPosts->count() }} pending posts:</p>

    <ul>
        @foreach ($pendingPosts as $post)
            <li>{{ $post->title }}</li>
        @endforeach
    </ul>
@else
    <p>There are no pending posts.</p>
@endif