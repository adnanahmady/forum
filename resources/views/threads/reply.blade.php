<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="{{ $reply->replyId() }}" class="card">
        <div class="card-header d-flex">
            <span class="mr-auto">
                <a href="{{ route('profile', $reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a>
                <strong>answered</strong>
                {{ $reply->created_at->diffForHumans() }}
            </span>
            @auth
                <span class="ml-auto">
                    <favorite :reply="{{ $reply }}"></favorite>
                </span>
            @endauth
        </div>
        <div class="card-body">
            <div v-if="edit">
                <textarea class="form-control mb-2" v-model="body"></textarea>
                <button class="btn btn-primary btn-xm mr-2" @click="updateReply()">{{ __('update') }}</button>
                <button class="btn btn-link btn-xm" @click="setEdit()">{{ __('cancel') }}</button>
            </div>
            <span v-else="edit" style="white-space: pre-line;" v-text="body"></span>
        </div>
        @can('delete', $reply)
        <div class="card-footer d-flex">
            <button class="btn btn-success btn-xm mr-2" @click="setEdit()">{{ __('edit') }}</button>
            <button class="btn btn-danger btn-xm" @click="deleteReply()">{{ __('delete') }}</button>
        </div>
        @endcan
    </div>
</reply>
