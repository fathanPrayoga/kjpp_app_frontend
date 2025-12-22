<x-app-layout>
@php
    $activeMenu = 'dokumen';
@endphp

{{-- Alpine Wrapper --}}
<div
    x-data="{
        isOpen: false,
        project: null,
        open(project) {
            this.project = project
            this.isOpen = true
        },
        close() {
            this.isOpen = false
            this.project = null
        }
    }"
>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <h1 class="mt-8 text-[32px] font-poppins font-bold text-gray-800 mb-8">
            Dokumen Properti
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ================= LEFT MENU ================= -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Dokumen -->
                <a href="{{ route('properti.dokumen') }}" class="block group">
                    <div class="p-8 rounded-[35px] transition-all cursor-pointer border
                        {{ $activeMenu === 'dokumen'
                            ? 'bg-green-50 border-green-400 shadow-[0_20px_40px_rgba(130,193,125,0.35)]'
                            : 'bg-white border-gray-50 shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)]'
                        }}">
                        <div class="flex items-center space-x-4">
                            <div class="p-4 rounded-[22px] text-white shadow-lg
                                {{ $activeMenu === 'dokumen'
                                    ? 'bg-green-600'
                                    : 'bg-[#82C17D]'
                                }}">
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
                    <div class="bg-white p-8 rounded-[35px] shadow border border-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
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
                    <div class="bg-white p-8 rounded-[35px] shadow border border-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
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

            <!-- ================= RIGHT CONTENT ================= -->
            <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow border border-gray-50">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        Daftar Dokumen Project
                    </h3>

                    <a href="{{ route('client.projects.create') }}"
                       class="bg-[#82C17D] text-white text-xs font-bold px-4 py-2 rounded-full">
                        + Tambah
                    </a>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-sm border-b">
                                <th class="pb-4 text-[11px] uppercase">No</th>
                                <th class="pb-4 text-[11px] uppercase">Nama Project</th>
                                <th class="pb-4 text-[11px] uppercase">Contract Date</th>
                                <th class="pb-4 text-[11px] uppercase">Dokumen</th>
                                <th class="pb-4 text-[11px] uppercase text-right">
                                    Update Terakhir
                                </th>
                            </tr>
                        </thead>

                        <tbody class="text-sm">
                        @forelse ($projects as $index => $project)
                            <tr
                                class="border-b hover:bg-green-50/50 cursor-pointer"
                                @click="open({
                                    nama: @js($project->nama_project),
                                    deskripsi: @js($project->deskripsi),
                                    contract_date: '{{ $project->contract_date->format('d M Y') }}',
                                    contact: @js($project->contact_person),
                                    documents: @js(
                                        $project->documents->map(fn($d) => [
                                            'nama' => $d->nama_file,
                                            'url'  => asset($d->file_path),
                                            'size' => round(filesize(public_path($d->file_path)) / 1024),
                                        ])
                                    )
                                })"
                            >
                                <td class="py-5 font-semibold text-gray-600">
                                    {{ $index + 1 }}
                                </td>

                                <td class="py-5">
                                    <div class="font-bold text-gray-800">
                                        {{ $project->nama_project }}
                                    </div>
                                    <div class="text-xs text-gray-400 italic">
                                        ID: #PRO-{{ $project->id }}
                                    </div>
                                </td>

                                <td class="py-5 text-gray-600">
                                    {{ \Carbon\Carbon::parse($project->contract_date)->format('d M Y') }}
                                </td>

                                <td class="py-5">
                                    {{ $project->documents->count() }} File
                                </td>

                                <td class="py-5 text-right text-gray-800">
                                    {{ $project->updated_at->format('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-gray-400 italic">
                                    Belum ada data dokumen
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= MODAL ================= -->
    <div
        x-show="isOpen"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40" @click="close()"></div>

        <!-- Modal Card -->
        <div
            @click.stop
            class="relative w-full max-w-xl rounded-3xl shadow-2xl bg-white overflow-hidden overflow-x-hidden z-10"
        >
            <!-- Header -->
            <div class="bg-green-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-black text-lg font-bold">
                    Detail Proyek
                </h3>

                <button @click="close()" class="text-black text-2xl leading-none">
                    &times;
                </button>
            </div>

            <!-- Content -->
            <div class="p-4 space-y-4 text-sm text-gray-700 max-h-[65vh] overflow-y-auto overflow-x-hidden">

                <!-- Nama Proyek -->
                <div>
                    <p class="font-semibold text-gray-900">Nama Proyek</p>
                    <p class="break-all whitespace-pre-wrap max-w-full" x-text="project?.nama"></p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <p class="font-semibold text-gray-900">Deskripsi</p>
                    <p class="break-all whitespace-pre-wrap max-w-full leading-relaxed"
                        x-text="project?.deskripsi">
                    </p>
                </div>

                <!-- Contract Date -->
                <div>
                    <p class="font-semibold text-gray-900">Contract Date</p>
                    <p x-text="project?.contract_date"></p>
                </div>

                <!-- Contact -->
                <div>
                    <p class="font-semibold text-gray-900">Contact</p>
                    <p class="break-all max-w-full" x-text="project?.contact"></p>
                </div>

                <!-- Dokumen -->
                <div>
                    <p class="font-semibold text-gray-900 mb-3">Dokumen</p>

                    <template x-if="project?.documents.length === 0">
                        <p class="text-gray-400 italic text-sm">
                            Tidak ada dokumen
                        </p>
                    </template>

                    <div class="space-y-2">
                        <template x-for="doc in project?.documents" :key="doc.url">
                            <div class="flex items-center justify-between gap-3 bg-gray-50 rounded-xl px-4 py-2 overflow-hidden">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="fa fa-file-pdf text-red-500 shrink-0"></i>

                                    <a
                                        :href="doc.url"
                                        target="_blank"
                                        class="text-green-600 hover:underline break-all max-w-[75%]"
                                        x-text="doc.nama"
                                    ></a>
                                </div>

                                <span class="text-xs text-gray-500 shrink-0"
                                    x-text="doc.size + ' KB'">
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
</x-app-layout>
