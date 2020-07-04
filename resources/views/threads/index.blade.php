@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                @forelse ($threads as $thread)
                    @include('threads._thread')
                @empty
                    <p>
                        {{ __('there are no threads') }}
                    </p>
                @endforelse

                {{ $threads->render() }}
            </div>
            @include ('threads._sidebar')
        </div>
    </div>
@endsection
