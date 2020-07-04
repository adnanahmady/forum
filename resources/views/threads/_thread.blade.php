<div class="card mb-4">
    <div class="card-header justify-content-between d-flex">
        <div class="flex align-baseline">
            <h5>
                <a href="{{ $thread->path() }}" class="mr-auto">
                    @if ($thread->hasNewUpdate())
                        <strong>{{ $thread->title }}</strong>
                    @else
                        {{ $thread->title }}
                    @endif
                </a>
            </h5>
            <h6>
                asked by:
                <a href="{{ route('profile', $thread->creator) }}">
                    {{ $thread->creator->name }}
                </a>
            </h6>
        </div>
        <strong class="ml-auto my-auto">
            {{ $thread->replies_count }}
            {{ str_plural('reply', $thread->replies_count) }}
        </strong>
    </div>

    <div class="card-body" >{!! $thread->body  !!}</div>

    <div class="card-footer">
        {{ trans_choice('messages.visit', $thread->visits()->count(), ['value' => $thread->visits()->count()]) }}
    </div>
</div>
