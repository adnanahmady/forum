@component('activities._activity')
    @slot('title')
        <strong class="mr-2">
            {{ __('you') }}
            {{ $activity->action() }}
        </strong>
        <span class="mr-auto">
            <a href="{{ $activity->subject->thread->path() }}">
            "{{ $activity->subject->thread->title }}"
            </a>
        </span>
    @endslot

    @slot('date')
        {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{$activity->subject->body}}
    @endslot
@endcomponent
