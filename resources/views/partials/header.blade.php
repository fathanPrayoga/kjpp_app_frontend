<!-- Header/Navigation -->
<nav class="fixed inset-x-0 top-0 bg-[#82C17D] shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/kjpp_logo.png') }}" alt="Logo" class="h-20 w-auto rounded-[35px]">
            </a>
        </div>

        <!-- Navigation Links -->
          <div class="hidden md:flex space-x-16 text-white font-medium text-lg">
                  @if(Auth::user()->role === 'karyawan')
                      <a href="{{ route('properti.karyawan') }}" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Properti</a>
                  @elseif(Auth::user()->role === 'client')
                      <a href="{{ route('properti.client') }}" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Properti</a>
                  @else
                      <a href="{{ route('properti.karyawan') }}" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Properti</a>
                  @endif
                  
                  <a href="{{ route('laporan.project') }}" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Laporan </a>
                  <a href="{{ route('chats.index') }}" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Obrolan</a>
                  <a href="#" class="text-white no-underline hover:underline hover:decoration-blue-300 hover:decoration-2 hover:underline-offset-8 transition">Setting</a>
          </div>

        <!-- User Dropdown -->
        <div class="relative group">
            <button class="flex items-center space-x-2 text-gray-800 hover:text-white focus:outline-none">
                <img src="{{ Auth::user()->profile_photo_url }}" class="h-12 w-12 rounded-full border-2 border-white object-cover">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden group-hover:block z-50">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-lg">
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-lg">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
