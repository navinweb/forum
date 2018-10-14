<div class="card">
    <div class="card-header">
        <div class="level vertical-center">
            <h5 class="flex">
                <a href="{{ $reply->owner->id }}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }}...
            </h5>

            <div>
                <form method="post" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field() }}

                    <button class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : ''}}>
                        {{ $reply->favorites()->count() }} {{ str_plural('star', $reply->favorites()->count()) }}
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
