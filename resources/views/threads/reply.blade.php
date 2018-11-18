<div id="reply-{{$reply->id}}" class="card">
    <div class="card-header">
        <div class="level vertical-center">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner) }}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }}...
            </h5>

            <div>
                <form method="post" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field() }}

                    <button class="btn btn-default" {{ $reply->isFavorite() ? 'disabled' : ''}}>
                        {{ $reply->favorites_count }} {{ str_plural('star', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>
    </div>
</div>
