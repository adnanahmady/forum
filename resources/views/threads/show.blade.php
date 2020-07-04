@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/at.js/css/jquery.atwho.min.css') }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col col-md-8">
                    @include('threads._question')

                    <replies
                        @removed="repliesCount--"
                        @added-item="repliesCount++"
                    ></replies>
                </div>
                <div class="col col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Question Info') }}
                        </div>
                        <div class="card-body">
                            <div>
                                @lang('this question has been asked by :author at :date and has :replies :plural', [
                                    'author' => "<strong>{$thread->creator->name}</strong>",
                                    'date' => "<strong>{$thread->created_at->diffForHumans()}</strong>",
                                    'replies' => "<strong v-text=\"repliesCount\"></strong>",
                                    'plural' => str_plural('reply', $thread->replies_count)
                                ])
                            </div>
                            <subscribe-button
                                :active="{{ $thread->isSubscribed ? 'true' : 'false' }}"
                            ></subscribe-button>
                            <lock-button @on-lock="handleLock" :thread="{{ $thread }}"></lock-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
