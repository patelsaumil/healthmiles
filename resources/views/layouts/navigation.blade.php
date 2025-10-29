{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Left: Logo + Nav links --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-gray-800">
                        <span class="inline-grid place-items-center w-8 h-8 bg-blue-600 text-white rounded-md">🩺</span>
                        <span>HealthMiles</span>
                    </a>
                </div>

                <div class="hidden sm:flex space-x-6 sm:ms-10">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}"
                       class="{{ request()->routeIs('contact') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">
                        Contact
                    </a>
                </div>
            </div>

            {{-- Right: Auth Buttons --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 font-medium">Hi, {{ auth()->user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-2 text-sm bg-red-500 text-white rounded-md hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="text-sm px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
