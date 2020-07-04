@component('activities._activity')
    @slot('title')
        <strong class="mr-2">
            {{ __('you') }}
            {{ $activity->action() }}
        </strong>
        <a href="{{ $activity->subject->path() }}" class="mr-auto">
            {{ $activity->subject->title }}
        </a>
    @endslot

    @slot('date')
        {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{$activity->subject->body}}
    @endslot
@endcomponent
