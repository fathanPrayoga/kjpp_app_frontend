<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Laporan</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-6">
                    <a href="{{ route('laporan.project') }}" class="block">
                        <div
                            class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-gray-50">
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

                    <a href="{{ route('laporan.tahunan') }}" class="block">
                        <div
                            class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] border border-gray-50">
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

                <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow">
                    <h3 class="text-xl font-bold mb-6">List Project</h3>

                    @if($projects->isEmpty())
                        <div class="text-center text-gray-400 py-10">
                            Belum ada project
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <thead class="border-b text-gray-500">
                                <tr>
                                    <th class="py-2 text-left">No</th>
                                    <th class="text-left">Project</th>
                                    <th class="text-left">Status</th>
                                    <th class="text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $i => $project)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3">{{ $i + 1 }}</td>
                                        <td>{{ $project->nama_project }}</td>
                                        <td>
                                            <span
                                                class="px-3 py-1 rounded-full text-xs {{ $project->status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $project->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex gap-3">
                                                <button onclick="openModal({{ $project->id }})"
                                                    class="text-[#82C17D] font-medium hover:underline">
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete({{ $project->id }})"
                                                    class="text-red-500 font-medium hover:underline">
                                                    Hapus
                                                </button>
                                                <form id="delete-form-{{ $project->id }}"
                                                    action="{{ route('laporan.project.delete', $project->id) }}" method="POST"
                                                    class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="laporanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-xl mx-auto">

            <!-- HEADER -->
            <div class="bg-[#82C17D] px-6 py-4 flex justify-between text-white">
                <h3 id="modalTitle" class="font-bold text-lg">Laporan Project</h3>
                <button onclick="closeModal()">✖</button>
            </div>

            <!-- BODY -->
            <form id="uploadForm" method="POST" action="{{ route('laporan.upload') }}" enctype="multipart/form-data"
                class="p-8 space-y-5">
                @csrf

                <input type="hidden" id="project_id" name="project_id">

                <div class="space-y-1">
                    <label class="text-sm font-semibold">Nama Project</label>
                    <input id="projectName" type="text" readonly class="w-full px-4 py-2 bg-gray-100 border rounded-xl">
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold">Asal Instansi</label>
                    <input id="asal_instansi" name="asal_instansi" type="text"
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#82C17D]">
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold">Tanggal Mulai</label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="date"
                        class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-[#82C17D]">
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold">Dokumen (PDF)</label>
                    <input type="file" name="file" accept="application/pdf" class="w-full text-sm">
                </div>

                <button type="submit" class="w-full bg-[#82C17D] hover:bg-[#6ba867]
                           text-white font-bold py-3 rounded-full">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variabel untuk menyimpan data asli
        let originalData = {};

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('uploadForm');
            const asalInput = document.getElementById('asal_instansi');
            const tanggalInput = document.getElementById('tanggal_mulai');
            const fileInput = document.querySelector('input[name="file"]');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const asalVal = asalInput.value;
                const tanggalVal = tanggalInput.value;
                const fileCount = fileInput.files.length;

                // Cek apakah ada perubahan
                if (
                    asalVal === (originalData.asal_instansi || '') &&
                    tanggalVal === (originalData.tanggal_mulai || '') &&
                    fileCount === 0
                ) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Perubahan',
                        text: 'Silakan ubah data atau upload file terlebih dahulu.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Simpan Laporan?',
                    text: 'Pastikan data yang diisi sudah benar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#82C17D'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        function openModal(id) {
            const modal = document.getElementById('laporanModal');
            const projectIdInput = document.getElementById('project_id');
            const projectNameInput = document.getElementById('projectName');
            const asalInput = document.getElementById('asal_instansi');
            const tanggalInput = document.getElementById('tanggal_mulai');

            fetch(`/laporan/project/${id}`)
                .then(res => res.json())
                .then(data => {
                    modal.classList.remove('hidden');

                    projectIdInput.value = data.id;
                    projectNameInput.value = data.nama_project;
                    asalInput.value = data.asal_instansi ?? '';
                    tanggalInput.value = data.tanggal_mulai ?? '';

                    // Simpan data awal untuk perbandingan di fungsi submit
                    originalData = {
                        asal_instansi: data.asal_instansi ?? '',
                        tanggal_mulai: data.tanggal_mulai ?? ''
                    };

                    // Reset input file
                    document.querySelector('input[name="file"]').value = '';
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    alert('Gagal mengambil data project');
                });
        }

        function closeModal() {
            document.getElementById('laporanModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then(res => {
                if (res.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>