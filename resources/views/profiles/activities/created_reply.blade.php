<div class="card">
    <div class="card-header">
        <div class="level">
            <span class="flex">
               {{ $profileUser->name }} replied to thread
            </span>
        </div>
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $activity->subject->body }}</div>
        </article>
    </div>
</div>
