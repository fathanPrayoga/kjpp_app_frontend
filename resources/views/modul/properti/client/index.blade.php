<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">
                Properti Saya
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- LEFT MENU -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Dokumen -->
                    <a href="{{ route('properti.dokumen') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg
                                            group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Dokumen</h3>
                                    <p class="text-gray-400 text-sm">Upload & Kelola Berkas</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Fisik -->
                    <a href="{{ route('properti.fisik') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg
                                            group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Fisik</h3>
                                    <p class="text-gray-400 text-sm">Status Objek Properti</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Penilaian -->
                    <a href="{{ route('properti.penilaian') }}" class="block group">
                        <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)]
                                    hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]
                                    transition-all cursor-pointer border border-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg
                                            group-hover:scale-105 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Penilaian</h3>
                                    <p class="text-gray-400 text-sm">Hasil & Review Penilaian</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- RIGHT CONTENT -->
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px]
                            shadow-[0_20px_40px_rgba(0,0,0,0.04)] border border-gray-50">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">
                            Riwayat Project Terbaru
                        </h3>
                        <div class="flex items-center gap-3">
                            <!-- Status -->
                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1
                                        rounded-full font-bold uppercase">
                                Aktif
                            </span>

                            <!-- Tambah Project -->
                                <a href="{{ route('client.projects.create') }}"
                                class="inline-flex items-center gap-2
                                        bg-[#82C17D] hover:bg-[#6cad67]
                                        text-white text-xs font-bold
                                        px-3 py-1 rounded-full
                                        transition shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah
                                </a>

                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 text-[11px] uppercase">Nama Project</th>
                                    <th class="pb-4 text-[11px] uppercase">Status</th>
                                    <th class="pb-4 text-[11px] uppercase text-right">
                                        Update Terakhir
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="text-sm">
                                @forelse ($projects as $project)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition">
                                        <td class="py-5">
                                            <div class="font-bold text-gray-800">
                                                {{ $project->nama_project }}
                                            </div>

                                            <div class="text-[11px] text-gray-400 italic">
                                                ID: #PRO-{{ $project->id }}
                                            </div>
                                        </td>

                                        <td class="py-5">
                                            <span class="font-bold text-blue-600 capitalize">
                                                {{ $project->status }}
                                            </span>
                                        </td>

                                        <td class="py-5 font-semibold text-gray-800 text-right">
                                            {{ $project->updated_at->format('d M, H.i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-16 text-center text-gray-400 italic">
                                            Anda belum memiliki pengajuan project.
                                        </td>
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
