<div class="card">
    <div class="card-header">
        <a href="{{ $reply->owner->id }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>
    </div>
</div>
