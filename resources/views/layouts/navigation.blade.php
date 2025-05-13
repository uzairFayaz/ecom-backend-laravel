<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-xl font-bold text-gray-800">Home</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px p-4 sm:ml-10 sm:flex">
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    @if (!Auth::check() || !Auth::user()->isAdmin())
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                            {{ __('Cart') }}
                        </x-nav-link>
                        <x-nav-link :href="route('wishlist.index')" :active="request()->routeIs('wishlist.index')">
                            {{ __('Wishlist') }}
                        </x-nav-link>
                    @endif
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard.index')">
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>
            <!-- User Menu -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-8">
                @auth
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Explicitly render a form for logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out text-start">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 bg-indigo-500">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
