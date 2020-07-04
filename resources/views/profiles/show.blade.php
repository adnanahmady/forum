@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="page-header border-bottom pb-3 mb-5">
            <avatar-component
                :user="{{ $profileUser }}"
                avatar-route="{{ route('avatar', $profileUser) }}"></avatar-component>
        </h1>


        <div class="row">
            <div class="col">
                @forelse($activities as $date => $subject)
                    <div class="h3 border-bottom pb-2 mb-3">
                        {{ $date }}
                    </div>
                    @foreach($subject as $activity)
                        @if(view()->exists("activities._{$activity->type}"))
                            @include("activities._{$activity->type}")
                        @endif
                    @endforeach
                @empty
                    <p>
                        {{ __('you have no threads yet') }}
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
