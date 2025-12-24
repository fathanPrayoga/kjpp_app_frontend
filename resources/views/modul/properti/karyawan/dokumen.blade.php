<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div x-data="{
            isOpen: false,
            project: null,
            documents: [],
            open(proj) {
                this.project = proj
                this.documents = proj.documents
                this.isOpen = true
            },
            close() {
                this.isOpen = false
                this.project = null
                this.documents = []
            }
        }">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <h1 class="mt-8 text-[32px] font-poppins font-bold text-gray-800 mb-8">Dokumen Properti</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Side: 3 Cards -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Dokumen Card -->
                        <a href="{{ route('properti.dokumen') }}" class="block">
                            <div class="bg-green-50 p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50 ring-2 ring-[#82C17D] ring-offset-2">
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

                        <!-- Fisik Card -->
                        <a href="{{ route('properti.fisik') }}" class="block">
                            <div class="bg-white p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-[#82C17D] p-4 rounded-[22px] text-white shadow-lg">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">Fisik</h3>
                                        <p class="text-gray-400 text-sm">Properti</p>
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
                        <h3 class="text-xl font-bold mb-6">Dokumen Verifikasi</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-gray-400 text-sm border-b">
                                        <th class="pb-4 font-semibold w-12 text-center">
                                            <input id="selectAll" type="checkbox" class="w-5 h-5" onchange="toggleSelectAll()">
                                        </th>
                                        <th class="pb-4 font-semibold">Nama Client</th>
                                        <th class="pb-4 font-semibold">Nama Project</th>
                                        <th class="pb-4 font-semibold text-center">Dokumen</th>
                                        <th class="pb-4 font-semibold text-center">Status</th>
                                        <th class="pb-4 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @forelse($projects as $project)
                                    <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                        <td class="py-4 text-center">
                                            <input type="checkbox" class="w-5 h-5 projectCheckbox" value="{{ $project->id }}" onchange="updateActionButtons()">
                                        </td>
                                        <td class="py-4 font-medium text-gray-800">{{ $project->client->name ?? 'Unknown' }}</td>
                                        <td class="py-4 text-gray-600">{{ $project->nama_project }}</td>
                                        <td class="py-4 text-center">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                {{ $project->documents->count() }} File
                                            </span>
                                        </td>
                                        <td class="py-4 text-center">
                                            @php
                                                $allVerified = $project->documents->every(fn($d) => $d->status === 'verified');
                                                $hasRejected = $project->documents->some(fn($d) => $d->status === 'rejected');
                                                $hasPending = $project->documents->some(fn($d) => $d->status === 'pending');
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $allVerified ? 'bg-green-100 text-green-800' : ($hasRejected ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $allVerified ? '✓ Verified' : ($hasRejected ? '✕ Rejected' : 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <button
                                                @click="open({
                                                    id: {{ $project->id }},
                                                    nama: @js($project->nama_project),
                                                    documents: @js($project->documents->map(fn($d) => [
                                                        'id' => $d->id,
                                                        'nama' => $d->nama_file,
                                                        'url' => route('karyawan.document.download', $d->id),
                                                        'created_at' => $d->created_at->format('d M Y'),
                                                    ])->toArray())
                                                })"
                                                class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700 transition font-bold">
                                                Lihat
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-400 italic">Belum ada project.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div id="actionButtons" class="mt-6 pt-6 border-t flex gap-3 justify-end">
                            <div class="max-w-md w-full flex gap-3 justify-end">
                                <button onclick="verifySelected('approve')" class="px-4 py-3 text-white rounded-lg font-bold" style="background-color:#16a34a; box-shadow:0 6px 14px rgba(22,163,74,0.18);">✓ Verifikasi</button>
                                <button onclick="verifySelected('reject')" class="px-4 py-3 text-white rounded-lg font-bold" style="background-color:#ef4444; box-shadow:0 6px 14px rgba(239,68,68,0.12);">✕ Tolak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL DOKUMEN -->
            <div x-show="isOpen" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="close()"></div>
                <div @click.stop class="relative w-full max-w-lg rounded-[30px] shadow-2xl bg-white overflow-hidden z-10 max-h-[80vh] overflow-y-auto">
                    <div class="bg-green-500 px-8 py-4 flex justify-between items-center sticky top-0">
                        <h3 class="text-white text-lg font-bold" x-text="project.nama"></h3>
                        <button @click="close()" class="text-white text-3xl leading-none hover:text-green-100">&times;</button>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Dokumen List -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">📄 Dokumen</h4>

                            <template x-if="documents.length === 0">
                                <p class="text-center text-gray-400 text-sm py-4">Tidak ada dokumen</p>
                            </template>

                            <div class="space-y-2">
                                <template x-for="(doc, idx) in documents" :key="doc.id">
                                    <div class="flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <i class="fa fa-file-pdf text-red-500 text-2xl shrink-0"></i>
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-gray-800 truncate" x-text="doc.nama"></div>
                                                <div class="text-xs text-gray-500" x-text="doc.created_at"></div>
                                            </div>
                                        </div>
                                        <a :href="doc.url" target="_blank" rel="noopener" class="text-gray-600 hover:text-gray-800 shrink-0 flex items-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Download All Button -->
                        <button
                            @click="window.location.href = '/karyawan/projects/' + project.id + '/download-all'"
                            class="w-full bg-green-500 text-white px-4 py-3 rounded-lg hover:bg-green-600 transition font-semibold text-sm">
                            📥 Unduh Semua
                        </button>

                        <!-- Tindakan Verifikasi -->
                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">Tindakan Verifikasi</h4>
                            <div class="flex gap-3">
                                <button 
                                    @click="submitVerify(project.id, 'approve')"
                                    class="flex-1 bg-green-500 text-white px-4 py-2 rounded-xl hover:bg-green-600 transition font-bold text-sm">
                                    Verifikasi
                                </button>
                                
                                <button 
                                    @click="submitVerify(project.id, 'reject')"
                                    class="flex-1 bg-white border-2 border-red-500 text-red-500 px-4 py-2 rounded-xl hover:bg-red-50 transition font-bold text-sm">
                                    Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL VERIFIKASI PROJECT -->
            <div id="verifyModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-black/40" @click="document.getElementById('verifyModal').classList.add('hidden')"></div>
                <div class="relative w-full max-w-md rounded-2xl shadow-2xl bg-white p-8 z-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Verifikasi Project</h3>
                    <p class="text-sm text-gray-600 mb-4">Project: <span id="verifyProjectName" class="font-semibold text-gray-800"></span></p>

                    <form method="POST" id="verifyForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Verifikasi</label>
                            <textarea name="notes" placeholder="Masukkan catatan..." class="w-full border border-gray-300 rounded-lg px-4 py-2 h-24 focus:outline-none focus:border-green-500"></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" name="action" value="approve" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-bold">
                                ✓ Setujui
                            </button>
                            <button type="submit" name="action" value="reject" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-bold">
                                ✕ Tolak
                            </button>
                            <button type="button" @click="document.getElementById('verifyModal').classList.add('hidden')" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition font-bold">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const verifyBatchUrl = "{{ route('karyawan.verify-batch') }}";

        function toggleSelectAll() {
            const sel = document.getElementById('selectAll');
            document.querySelectorAll('.projectCheckbox').forEach(cb => cb.checked = sel.checked);
            updateActionButtons();
        }

        function updateActionButtons() {
            const any = document.querySelectorAll('.projectCheckbox:checked').length > 0;
            document.getElementById('actionButtons').classList.toggle('hidden', !any);
        }

        function getSelectedProjects() {
            return Array.from(document.querySelectorAll('.projectCheckbox:checked')).map(cb => cb.value);
        }

        function verifySelected(action) {
            const selected = getSelectedProjects();
            if (selected.length === 0) { alert('Pilih minimal 1 project'); return; }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = verifyBatchUrl;

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            let inputs = `<input type="hidden" name="_token" value="${csrf}">`;
            inputs += `<input type="hidden" name="action" value="${action}">`;
            selected.forEach(id => inputs += `<input type="hidden" name="project_ids[]" value="${id}">`);
            form.innerHTML = inputs;

            document.body.appendChild(form);
            form.submit();
        }

        function openVerifyModal(projectId, projectName) {
            document.getElementById('verifyProjectName').innerText = projectName;
            document.getElementById('verifyForm').action = `/karyawan/projects/${projectId}/verify`;
            document.getElementById('verifyModal').classList.remove('hidden');
        }

        function submitVerify(projectId, action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/karyawan/projects/${projectId}/verify`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="action" value="${action}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        document.getElementById('verifyForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    </script>
</x-app-layout>