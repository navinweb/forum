{{--EDITING--}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input type="text" value="{{ $thread->title }}" class="form-control">


        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10">{{ $thread->body }}</textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <div class="btn-group-sm">
                <button class="btn btn-sm btn-primary" @click="update" v-show="editing">Update</button>
                <button class="btn btn-sm btn-dark" @click="editing = true" v-show="! editing">Edit</button>
                <button class="btn btn-sm btn-outline-dark" @click="editing = false">Cancel</button>
            </div>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST">
                    {{csrf_field()}}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link ">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{--VIEWING--}}
<div class="card" v-else>
    <div class="card-header">
        <div class="level">
            <img src="{{asset($thread->creator->avatar_path)}}" alt="{{$thread->creator->name}}"
                 width="25" height="25" class="mr-1">

            <span class="flex">
                <a href="/profiles/{{$thread->creator->name}}">{{ $thread->creator->name }}</a> posted:
                <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
            </span>
        </div>
    </div>

    <div class="card-body">
        <article>
            <div class="body">{{ $thread->body }}</div>
        </article>
    </div>

    <div class="card-footer">
        <button class="btn btn-sm btn-dark" @click="editing = true">Edit</button>
    </div>
</div>