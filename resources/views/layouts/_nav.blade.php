<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Browse') }}
                    </a>
                    <div class="dropdown-menu" style="max-height: 500px; overflow: auto"
                         aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('/threads') }}">{{ __('All Threads') }}</a>

                        @auth
                            <a class="dropdown-item" href="{{ url('/threads?by=' . auth()->user()->name) }}">{{ __('My Threads') }}</a>
                        @endauth
                        <a class="dropdown-item" href="{{ url('/threads?replies=1') }}">{{ __('Most Replied Threads') }}</a>
                        <a class="dropdown-item" href="{{ url('/threads?replies') }}">{{ __('Least Replied Threads') }}</a>
                        <a class="dropdown-item" href="{{ url('/threads?unanswered=1') }}">{{ __('Unanswered Threads') }}</a>
                    </div>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/threads/create') }}">{{ __('New Thread') }}</a>
                </li>
                @endauth

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Channels') }}
                    </a>
                    <div class="dropdown-menu" style="max-height: 500px; overflow: auto"
                         aria-labelledby="navbarDropdown">
                        @foreach($channels as $channel)
                            <a href="{{ url('/threads/' . $channel->slug) }}" class="dropdown-item">
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <notifications></notifications>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile', auth()->user()) }}">
                                {{ __('My Profile') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
