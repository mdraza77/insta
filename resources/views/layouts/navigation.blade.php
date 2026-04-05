<aside class="fixed top-0 left-0 z-40 w-64 h-screen hidden md:block border-r border-gray-800 bg-black transition-all">
    <div class="h-full px-4 py-8 overflow-y-auto flex flex-col justify-between">
        <div>
            <a href="{{ route('dashboard') }}" class="flex items-center ps-2 mb-10">
                <span class="text-2xl font-bold italic text-white tracking-tighter">Insta</span>
            </a>

            <ul class="space-y-4 font-medium">
                <li>
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="flex items-center p-3 text-white bg-gray-900 rounded-lg hover:bg-gray-900 group border-none">
                        <i
                            class="fa-solid fa-house text-xl w-7 {{ request()->routeIs('dashboard') ? 'text-purple-500' : 'text-gray-400' }}"></i>
                        <span class="ms-3">Feed</span>
                    </x-responsive-nav-link>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-900 group">
                        <i
                            class="fa-solid fa-magnifying-glass text-xl w-7 text-gray-400 group-hover:text-purple-500"></i>
                        <span class="ms-3">Search</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 text-white rounded-lg hover:bg-gray-900 group">
                        <i class="fa-solid fa-paper-plane text-xl w-7 text-gray-400 group-hover:text-purple-500"></i>
                        <span class="ms-3">Inbox</span>
                    </a>
                </li>

                <li>
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-post')"
                        class="flex items-center w-full p-3 text-white rounded-lg hover:bg-gray-900 group transition">
                        <i class="fa-regular fa-square-plus text-xl w-7 text-gray-400 group-hover:text-purple-500"></i>
                        <span class="ms-3">Create</span>
                    </button>
                </li>

                <li>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center p-3 text-white rounded-lg hover:bg-gray-900 group">
                        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/default-avatar.png') }}"
                            class="w-7 h-7 rounded-full border border-gray-700">
                        <span class="ms-3">Profile</span>
                    </a>
                </li>
            </ul>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="px-2">
            @csrf
            <button type="submit" onclick="return confirm('Are you sure to Logout?')"
                class="flex items-center p-3 w-full text-red-500 hover:bg-red-950/30 rounded-lg transition">
                <i class="fa-solid fa-right-from-bracket w-7"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

<nav
    class="fixed bottom-0 z-50 w-full h-16 bg-black border-t border-gray-800 flex items-center justify-around md:hidden">
    <a href="{{ route('dashboard') }}" class="p-2">
        <i
            class="fa-solid fa-house text-2xl {{ request()->routeIs('dashboard') ? 'text-purple-500' : 'text-gray-400' }}"></i>
    </a>
    <a href="#" class="p-2"><i class="fa-solid fa-magnifying-glass text-2xl text-gray-400"></i></a>
    <a href="#" class="p-2 text-3xl"><i class="fa-regular fa-square-plus text-gray-400"></i></a>
    <a href="#" class="p-2 text-2xl"><i class="fa-solid fa-clapperboard text-gray-400"></i></a>
    <a href="{{ route('profile.edit') }}">
        <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets/images/default-avatar.png') }}"
            class="w-8 h-8 rounded-full border {{ request()->routeIs('profile.edit') ? 'border-purple-500' : 'border-gray-700' }}">
    </a>
</nav>
