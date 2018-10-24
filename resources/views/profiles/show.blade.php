@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @foreach($profileUser->threads as $thread)
            <div class="card">
                <div class="card-header">{{ $thread->title }}</div>
                <div class="card-body">
                    <article>
                        <a href="{{$thread->creator->id}}">{{ $thread->creator->name }}</a> posted:
                        <div class="body">{{ $thread->body }}</div>
                    </article>
                </div>
            </div>
        @endforeach
    </div>
@endsection
