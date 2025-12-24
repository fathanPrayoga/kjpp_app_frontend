<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 text-3xl font-bold text-gray-800 mb-8 font-poppins text-[32px]">Penilaian Properti</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-6">
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

                    <a href="{{ route('properti.penilaian') }}" class="block">
                        <div class="bg-green-50 p-8 rounded-[35px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] transition-shadow cursor-pointer border border-gray-50 ring-2 ring-[#82C17D] ring-offset-2">
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

                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                    <h3 class="text-xl font-bold mb-6">Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-4 font-semibold">Nama Project</th>
                                    <th class="pb-4 font-semibold">Waktu</th>
                                    <th class="pb-4 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                {{-- SAFETY CHECK: Ensure $projects exists before looping --}}
                                @if(isset($projects))
                                    @forelse($projects as $project)
                                    <tr class="border-b last:border-0 hover:bg-gray-50 transition cursor-pointer" 
                                        onclick="openNilaiModal({{ $project->id }}, '{{ $project->nama_project }}', '{{ addslashes($project->deskripsi) }}', '{{ $project->contact_person }}')">
                                        
                                        <td class="py-4 text-gray-600">
                                            {{ $project->nama_project ?? '-' }}
                                        </td>
                                        
                                        <td class="py-4 font-semibold text-gray-800">
                                            {{ optional($project->created_at)->format('H.i') ?? '-' }}
                                        </td>

                                        <td class="py-4">
                                            @php
                                                $nilaiStatus = 'belum dinilai';
                                                if ($project->nilai) {
                                                    $nilaiStatus = $project->nilai->status_penilaian?->value ?? 'belum dinilai';
                                                }
                                            @endphp
                                            <span class="
                                                px-3 py-1 rounded-full text-xs font-semibold
                                                @if($nilaiStatus === 'belum dinilai')
                                                    bg-red-100 text-red-700
                                                @elseif($nilaiStatus === 'sedang dinilai')
                                                    bg-yellow-100 text-yellow-700
                                                @elseif($nilaiStatus === 'sudah dinilai')
                                                    bg-green-100 text-green-700
                                                @endif
                                            ">
                                                {{ str_replace('_', ' ', ucfirst($nilaiStatus)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-8 text-gray-400 italic">Belum ada project.</td>
                                    </tr>
                                    @endforelse
                                @else
                                    {{-- Fallback if variable is missing entirely --}}
                                    <tr>
                                        <td colspan="3" class="text-center py-8 text-red-400 italic">Error: Data project tidak ditemukan.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nilai Properti (Read-Only for Client) -->
    <div id="nilaiModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[30px] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <!-- Modal Header -->
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Nilai Properti</h2>

                <!-- Project Info Section -->
                <div class="bg-gray-50 p-6 rounded-[20px] mb-6 border border-gray-200">
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Nama Project</label>
                        <p id="projectName" class="text-lg font-bold text-gray-800 mt-1">-</p>
                    </div>
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Deskripsi</label>
                        <p id="projectDesc" class="text-gray-700 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Contact Person</label>
                        <p id="projectContact" class="text-gray-700 mt-1">-</p>
                    </div>
                </div>

                <!-- Form Section (Read-Only) -->
                <form id="nilaiForm" class="space-y-5">
                    <input type="hidden" id="projectId" name="project_id">

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Status Penilaian</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="statusPenilaian" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Pasar Final</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiPasarFinal" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Tanah</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiTanah" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Indikasi Pasar</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiIndikasiPasar" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Indikasi Biaya</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiIndikasiBiaya" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Likuidasi</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiLikuidasi" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Bangunan</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiBangunan" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Per m² Tanah</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiPerM2Tanah" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Per m² Bangunan</label>
                        <div class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            <p id="nilaiPerM2Bangunan" class="text-gray-700 py-1">-</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeNilaiModal()" 
                            class="w-full px-4 py-3 bg-[#82C17D] text-white font-semibold rounded-lg hover:bg-[#6fa86a] transition">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format number with Rp prefix and thousands separator (.)
        function formatNumberDisplay(value) {
            if (!value || value === 0 || value === '') {
                return '-';
            }
            // Convert to string and remove non-digits
            const cleanValue = value.toString().replace(/\D/g, '');
            // Add . separator every 3 digits from the right
            const formatted = cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return 'Rp ' + formatted;
        }

        function openNilaiModal(projectId, projectName, projectDesc, projectContact) {
            document.getElementById('projectId').value = projectId;
            document.getElementById('projectName').textContent = projectName;
            document.getElementById('projectDesc').textContent = projectDesc;
            document.getElementById('projectContact').textContent = projectContact;
            
            document.getElementById('nilaiModal').classList.remove('hidden');

            // Fetch existing nilai data
            fetchNilaiData(projectId);
        }

        function closeNilaiModal() {
            document.getElementById('nilaiModal').classList.add('hidden');
        }

        function fetchNilaiData(projectId) {
            fetch(`/properti/nilai/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById('statusPenilaian').textContent = data.status_penilaian ? data.status_penilaian.replace('_', ' ').charAt(0).toUpperCase() + data.status_penilaian.slice(1).replace('_', ' ') : '-';
                        document.getElementById('nilaiPasarFinal').textContent = formatNumberDisplay(data.nilai_pasar_final);
                        document.getElementById('nilaiTanah').textContent = formatNumberDisplay(data.nilai_tanah);
                        document.getElementById('nilaiIndikasiPasar').textContent = formatNumberDisplay(data.nilai_indikasi_dari_pasar);
                        document.getElementById('nilaiIndikasiBiaya').textContent = formatNumberDisplay(data.nilai_indikasi_dari_biaya);
                        document.getElementById('nilaiLikuidasi').textContent = formatNumberDisplay(data.nilai_likuidasi);
                        document.getElementById('nilaiBangunan').textContent = formatNumberDisplay(data.nilai_bangunan);
                        document.getElementById('nilaiPerM2Tanah').textContent = formatNumberDisplay(data.nilai_per_m2_tanah);
                        document.getElementById('nilaiPerM2Bangunan').textContent = formatNumberDisplay(data.nilai_per_m2_bangunan);
                    } else {
                        document.getElementById('statusPenilaian').textContent = 'Belum Dinilai';
                        document.getElementById('nilaiPasarFinal').textContent = '-';
                        document.getElementById('nilaiTanah').textContent = '-';
                        document.getElementById('nilaiIndikasiPasar').textContent = '-';
                        document.getElementById('nilaiIndikasiBiaya').textContent = '-';
                        document.getElementById('nilaiLikuidasi').textContent = '-';
                        document.getElementById('nilaiBangunan').textContent = '-';
                        document.getElementById('nilaiPerM2Tanah').textContent = '-';
                        document.getElementById('nilaiPerM2Bangunan').textContent = '-';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Close modal when clicking outside of it
        document.getElementById('nilaiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNilaiModal();
            }
        });
    </script>
</x-app-layout>
