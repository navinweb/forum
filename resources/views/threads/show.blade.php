@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $thread->title }}</div>
                    <div class="card-body">
                        <article>
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                            <div class="body">{{ $thread->body }}</div>
                        </article>
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}

                @if(auth()->check())
                    <form action="{{ $thread->path() . '/replies' }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" class="form-control" placeholder="Body"></textarea>
                        </div>
                        <button type="submit" class="btn btn-default">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this
                        discussion.</p>
                @endif

            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <article>
                            <p class="body">This thread was published {{ $thread->created_at->diffForHumans() }} by <a
                                        href="#">{{ $thread->creator->name }}</a> and currently
                                has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count ) }}
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
