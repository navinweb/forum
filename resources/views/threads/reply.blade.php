<reply :attributes="{{$reply}}" inline-template>
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
                    <form
                            method="post" action="/replies/{{ $reply->id }}/favorites"
                    >
                        {{ csrf_field() }}

                        <button class="btn btn-default" {{ $reply->isFavorite() ? 'disabled' : ''}}>
                            {{ $reply->favorites_count }} {{ str_plural('star', $reply->favorites_count) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <textarea class="form-control" v-model="body">{{ $reply->body }}</textarea>
            </div>

            <div v-else>
                <div class="body">{{ $reply->body }}</div>
            </div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-xs" @click="editing = true">Edit</button>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <form method="POST" action="/replies/{{$reply->id}}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form>
            </div>
        @endcan
    </div>
</reply>