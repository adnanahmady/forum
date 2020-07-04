<div class="col-4">
    @include('threads._search', [
    'title' => 'search',
    'value' => request('s'),
    'search' => 'search'
     ])
    @if(count($userVisitedThreads))
        @include('threads._visited_threads', [
        'threads' => $userVisitedThreads,
         'title' => __('top 5 most visited threads by you'),
         'card' => 'success'
         ])
    @endif
    @if(count($visitedThreads))
        @include('threads._visited_threads', [
        'threads' => $visitedThreads,
         'title' => 'top 5 most visited threads'
         ])
    @endif
</div>
