<div class="card badge-{{ @$card ?: 'light' }} mb-4">
    <div class="card-header">
        <strong>{{ ucfirst(__($title)) }}</strong>
    </div>
    <div class="card-body">
        <div class="list-group">
            @foreach($threads as $thread)
                <div class="list-group-item list-group-item-light">
                    <a href="{{ $thread->path }}" class="list-group-link">
                        {{ $thread->title }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
