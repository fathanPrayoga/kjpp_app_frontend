<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Selamat Datang, {{ Auth::user()->name }}!</h1>

            @php
                $projects = \App\Models\Project::where('client_id', Auth::id())->latest()->take(5)->get();
                $projectCount = \App\Models\Project::where('client_id', Auth::id())->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-[15px]">
                <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50">
                    <div class="bg-[#82C17D] p-3 rounded-full text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Properti</h3>
                        <p class="text-gray-400 font-medium">{{ $projectCount }} Project Anda</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50">
                    <div class="bg-[#82C17D] p-3 rounded-full text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Laporan</h3>
                        <p class="text-gray-400 font-medium">Belum ada laporan baru</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] flex items-center space-x-6 border border-gray-50">
                    <div class="bg-[#82C17D] p-3 rounded-full text-white shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Obrolan</h3>
                        <p class="text-gray-400 font-medium">Belum ada pesan</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <h3 class="text-xl font-bold mb-6">Project Saya</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold">No</th>
                                    <th class="pb-4 font-semibold">Nama Project</th>
                                    <th class="pb-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @forelse($projects as $index => $project)
                                <tr class="border-b last:border-0">
                                    <td class="py-4 font-medium">{{ $index + 1 }}</td>
                                    <td class="py-4">{{ $project->nama_project ?? ($project->name ?? 'Project') }}</td>
                                    <td class="py-4">
                                        <span class="bg-[#D1E7D0] text-[#4A7C47] px-4 py-1.5 rounded-full text-xs font-bold">{{ ucfirst($project->status ?? 'unknown') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-gray-400 italic">Belum ada project.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Notifikasi</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-2 border-b border-gray-100 pb-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                                <span class="text-sm">Tidak ada notifikasi baru</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[28px] shadow-[0_18px_30px_rgba(0,0,0,0.04)]">
                        <h3 class="text-xl font-bold mb-4">Recent Activity</h3>
                        <div class="space-y-4 text-sm">
                            @foreach($projects as $project)
                            <div class="flex justify-between items-center border-b border-gray-50 pb-2 last:border-0">
                                <span class="text-gray-600 font-medium truncate w-20">{{ $project->nama_project ?? ($project->name ?? 'Project') }}</span>
                                <span class="text-gray-400 font-bold px-2">{{ \Illuminate\Support\Str::limit($project->deskripsi ?? '', 40) }}</span>
                                <span class="font-semibold text-gray-800">{{ $project->created_at->format('d M') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
