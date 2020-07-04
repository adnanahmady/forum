<div class="card mb-4" v-if="editing" v-cloak>
    <div class="card-header d-flex">
        <input type="text" id="title" class="form-control mr-3" v-model="form.title">

        @can('delete', $thread)
        <span class="ml-auto">
            <form action="{{ $thread->path() }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn px-4 btn-danger">
                    {{ __('delete') }}
                </button>
            </form>
        </span>
        @endcan
    </div>

    <div class="card-body">
        <wysiwyg v-model="form.body"></wysiwyg>
    </div>

    <div class="card-footer d-flex">
        <button class="btn btn-outline-primary btn-md px-4 mr-auto" @click="update">
            <i class="fa fa-check" aria-hidden="true"></i>
            {{ ucfirst(__('update')) }}
        </button>
        <button class="btn btn-outline-danger btn-md px-4" @click="resetForm">
            <i class="fa fa-times" aria-hidden="true"></i>
            {{ ucfirst(__('cancel')) }}
        </button>
    </div>
</div>
<div class="card mb-4" v-cloak v-else>
    <div class="card-header d-flex">
        <img src="{{ $thread->creator->avatar_path }}"
             alt="{{ $thread->creator->name }}"
             style="width: 30px; height: 30px;"
             class="mr-2 img-fluid">
        <span class="mr-auto">
            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
            <strong>{{ __('asked') }}</strong>
            <span v-text="title"></span>
        </span>
    </div>

    <div class="card-body">
        <span style="white-space: pre-line;" v-html="body"></span>
    </div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-outline-success btn-md px-4" @click="editing = true">
            <i class="fa fa-edit" aria-hidden="true"></i>
            {{ ucfirst(__('edit')) }}
        </button>
    </div>
</div>
