<div class="card badge-{{ @$card ?: 'light' }} mb-4">
    <div class="card-header">
        <strong>{{ ucfirst(__($title)) }}</strong>
    </div>
    <div class="card-body">
        <form action="/threads/search" method="get">
            <div class="form-group">
                <input type="search" name="s" class="form-control" value="{{ $value }}">
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    {{ ucfirst(__($search)) }}
                </button>
            </div>
        </form>
    </div>
</div>
