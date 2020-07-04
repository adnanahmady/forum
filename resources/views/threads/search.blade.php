@extends('layouts.app')

@section('content')
    <scan inline-template>
        <div class="container">
            <ais-instant-search :search-client="searchClient" index-name="threads">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <ais-hits>
                                    <div slot-scope="{ items }">
                                        <div v-for="item of items">
                                            <div class="card mb-4">
                                                <div class="card-header justify-content-between d-flex">
                                                    <div class="flex align-baseline">
                                                        <a :href="item.path" class="h5 mr-auto">
                                                            <ais-highlight attribute="title" :hit="item" />
                                                        </a>
                                                        <h6>
                                                            asked by:
                                                            <span v-text="item.creator.name"></span>
                                                        </h6>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <ais-highlight attribute="body" :hit="item">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </ais-hits>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <footer class="footer">
                                    <ais-pagination/>
                                </footer>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card mb-4">
                            <strong class="card-header">Search</strong>
                            <div class="card-body">
                                <ais-search-box :autofocus="true" class="mb-4"/>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <strong>Filter by Channel</strong>
                            </div>
                            <div class="card-body">
                                <ais-refinement-list :show-more="true" attribute="channel.name"/>
                            </div>
                        </div>
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
                    <ais-configure query="{{ request('s') }}"/>
            </ais-instant-search>
        </div>
    </scan>
@endsection
