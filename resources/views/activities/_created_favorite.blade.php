@component('activities._activity')
    @slot('title')
        <strong class="mr-2">
            <a href="{{ $activity->subject->favoriteable->path() }}">
                {{ __( 'you favorited a :favorited', [
                'favorited' => $activity->subject->favorited()
                ]) }}
            </a>
        </strong>
    @endslot

    @slot('date')
        {{ $activity->created_at->diffForHumans() }}
    @endslot

    @slot('body')
        {{$activity->subject->favoriteable->body}}
    @endslot
@endcomponent
