@extends('layouts.app')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                @if ($errors->any())
                    <div class="row mb-4">
                        <div class="col">
                            <ul class="list-group">
                                @foreach($errors->all() as $error)
                                    <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ __('Create Thread') }}
                    </div>
                    <form action="{{ url('/threads') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" name="title" id="title" class="form-control"
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <select name="channel_id" id="channel_id" class="dropdown form-control">
                                    <option value="">{{ __('Choose One...') }}</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" class="dropdown-item"
                                            {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <wysiwyg name="body" value="{{ old('body') }}"></wysiwyg>
                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('forum.recaptcha.key') }}"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group">
                                <button class="btn btn-primary">{{ __('Create Thread') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
