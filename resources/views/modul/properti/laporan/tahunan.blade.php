<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Laporan Tahunan</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side: 2 Cards -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Project Card -->
                    <a href="{{ route('laporan.project') }}" class="block">
                        <div
                            class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5h6m2 0h1a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h1m2-2h6a2 2 0 012 2v2H7V5a2 2 0 012-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Project</h3>
                                    <p class="text-gray-400 text-sm">Laporan</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Tahunan Card -->
                    <a href="{{ route('laporan.tahunan') }}" class="block">
                        <div
                            class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Tahunan</h3>
                                    <p class="text-gray-400 text-sm">Laporan</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Area kanan -->
                <div
                    class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)] min-h-[400px] flex flex-col">
                    <h3 class="text-xl font-bold mb-6">List Laporan Tahunan</h3>

                    <div class="space-y-4">
                        @forelse($years as $y)
                            <div onclick="openTahunanModal({{ $y->tahun }})"
                                class="flex items-center justify-between p-5 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors group">
                                <span class="text-gray-700 font-medium text-lg">Laporan {{ $y->tahun }}</span>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-[#82C17D]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        @empty
                            <div class="flex-grow flex flex-col items-center justify-center text-center py-20">
                                <p class="text-gray-500 font-medium text-lg">Belum ada Laporan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tahunan -->
    <div id="tahunanModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-md rounded-[30px] shadow-2xl overflow-hidden border border-gray-100 mx-4">
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between items-center">
                <h3 id="modalYearTitle" class="text-gray-800 font-bold text-lg font-poppins">Laporan 2020</h3>
                <button onclick="closeTahunanModal()" class="text-gray-700 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="fileListContainer" class="p-6 space-y-3 max-height-[400px] overflow-y-auto">
            </div>

            <div class="p-6 pt-0 text-center">
                <button
                    class="bg-[#82C17D] hover:bg-[#6ba867] text-white font-bold py-2.5 px-8 rounded-full transition-all text-sm">
                    Unduh semua
                </button>
            </div>
        </div>
    </div>

    <script>
        function openTahunanModal(year) {
            // Pastikan URL fetch mengarah ke route yang baru kita buat
            fetch(`/laporan/tahunan/${year}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('tahunanModal').classList.remove('hidden');
                    document.getElementById('modalYearTitle').innerText = 'Laporan ' + data.tahun;

                    const container = document.getElementById('fileListContainer');
                    container.innerHTML = '';

                    if (data.files.length === 0) {
                        container.innerHTML = '<p class="text-center text-gray-400">Tidak ada dokumen di tahun ini.</p>';
                    }

                    data.files.forEach(project => {
                        container.innerHTML += `
                    <div class="flex items-center justify-between py-3 border-b border-gray-50">
                        <span class="text-sm text-gray-600 font-medium">${project.nama_project}.pdf</span>
                        <a href="/storage/${project.dokumen}" target="_blank" class="text-[#82C17D] hover:underline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                    </div>
                `;
                    });
                });
        }

        function closeTahunanModal() {
            document.getElementById('tahunanModal').classList.add('hidden');
        }
    </script>
</x-app-layout>