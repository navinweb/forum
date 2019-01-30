{{--EDITING--}}
<div class="card" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <div class="btn-group-sm">
                <button class="btn btn-sm btn-primary" @click="update" v-show="editing">Update</button>
                <button class="btn btn-sm btn-dark" @click="editing = true" v-show="! editing">Edit</button>
                <button class="btn btn-sm btn-outline-dark" @click="resetForm">Cancel</button>
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
                <a href="{{ $thread->path() }}" v-text="title"></a>
            </span>
        </div>
    </div>

    <div class="card-body" v-html="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-sm btn-dark" @click="editing = true">Edit</button>
    </div>
</div>