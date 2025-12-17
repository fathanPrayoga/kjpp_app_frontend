<header x-data="{ open: false }" class="bg-[#86c381] shadow-md px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <img src="/logo.png" alt="Logo" class="h-10 w-auto">
            <nav class="hidden md:flex space-x-8 text-gray-900 font-semibold ml-10">
                <a href="#" class="hover:text-white transition">Properti</a>
                <a href="#" class="hover:text-white transition">Laporan</a>
                <a href="#" class="hover:text-white transition">Obrolan</a>
                <a href="#" class="hover:text-white transition">Setting</a>
            </nav>
        </div>

        <div class="relative flex items-center space-x-3">
            <span class="text-gray-900 font-bold hidden sm:block">{{ Auth::user()->name }}</span>
            <button @click="open = !open" class="focus:outline-none">
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" 
                     class="h-10 w-10 rounded-full border-2 border-white shadow-sm hover:scale-105 transition transform">
            </button>

            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute right-0 mt-32 w-48 bg-white rounded-xl shadow-lg border py-2 z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Setting</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>