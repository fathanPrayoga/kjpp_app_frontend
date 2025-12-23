<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Fisik Properti</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Side: 3 Cards -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Dokumen Card -->
                    <a href="{{ route('properti.dokumen') }}" class="block">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Dokumen</h3>
                                    <p class="text-gray-400 text-sm">Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Fisik Card (active) -->
                    <a href="{{ route('properti.fisik') }}" class="block" aria-current="page">
                        <div class="bg-[#F0FDF4] p-8 rounded-[35px] shadow-[0_12px_24px_rgba(130,193,125,0.12)] transition-all cursor-default border-2 border-[#82C17D]">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Fisik</h3>
                                    <p class="text-[#1F7A3A] text-sm font-medium">Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Penilaian Card -->
                    <a href="{{ route('properti.penilaian') }}" class="block">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Penilaian</h3>
                                    <p class="text-gray-400 text-sm">Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Right Side: Project List -->
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <h3 class="text-xl font-bold mb-6">Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold">Nama Client</th>
                                    <th class="pb-4 font-semibold">Nama Project</th>
                                    <th class="pb-4 font-semibold">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($projects as $project)
                                <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                    <td class="py-4 font-medium text-gray-800">{{ $project->client->name ?? 'Unknown Client' }}</td>
                                    <td class="py-4 text-gray-600">{{ $project->nama_project ?? 'Project' }}</td>
                                    <td class="py-4 font-semibold text-gray-800">{{ $project->created_at->format('H.i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-gray-400 italic">Belum ada project.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
