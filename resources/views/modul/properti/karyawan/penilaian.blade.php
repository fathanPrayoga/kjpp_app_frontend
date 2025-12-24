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
                                    <th class="pb-4 font-semibold">Nama Client</th>
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
                                        <td class="py-4 font-medium text-gray-800">
                                            {{ $project->client->name ?? 'Client Deleted' }}
                                        </td>
                                        
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
                                        <td colspan="4" class="text-center py-8 text-gray-400 italic">Belum ada project.</td>
                                    </tr>
                                    @endforelse
                                @else
                                    {{-- Fallback if variable is missing entirely --}}
                                    <tr>
                                        <td colspan="4" class="text-center py-8 text-red-400 italic">Error: Data project tidak ditemukan.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nilai Properti -->
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

                <!-- Form Section -->
                <form id="nilaiForm" class="space-y-5">
                    @csrf
                    <input type="hidden" id="projectId" name="project_id">

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Status Penilaian</label>
                        <select id="statusPenilaian" name="status_penilaian" 
                            class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                            <option value="sedang dinilai">Sedang Dinilai</option>
                            <option value="sudah dinilai">Sudah Dinilai</option>
                        </select>
                        <p id="statusWarning" class="text-xs text-red-600 mt-1 hidden">Nilai tidak dapat diubah karena status sudah "Sudah Dinilai"</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Pasar Final</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiPasarFinal" name="nilai_pasar_final" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Tanah</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiTanah" name="nilai_tanah" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Indikasi Pasar</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiIndikasiPasar" name="nilai_indikasi_dari_pasar" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Indikasi Biaya</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiIndikasiBiaya" name="nilai_indikasi_dari_biaya" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Likuidasi</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiLikuidasi" name="nilai_likuidasi" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Bangunan</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiBangunan" name="nilai_bangunan" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Per m² Tanah</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiPerM2Tanah" name="nilai_per_m2_tanah" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Nilai Per m² Bangunan</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-2 text-gray-500 font-semibold pointer-events-none">Rp</span>
                            <input type="text" id="nilaiPerM2Bangunan" name="nilai_per_m2_bangunan" placeholder="0" 
                                class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#82C17D]">
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeNilaiModal()" 
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Kembali
                        </button>
                        <button type="submit" 
                            class="flex-1 px-4 py-3 bg-[#82C17D] text-white font-semibold rounded-lg hover:bg-[#6fa86a] transition">
                            Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentNilaiId = null;
        let isFinalized = false;

        // Format number with thousands separator (.)
        function formatNumber(value) {
            // Remove all non-digit characters
            const cleanValue = value.replace(/\D/g, '');
            // Add . separator every 3 digits from the right
            return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Setup number formatting for all currency inputs
        function setupNumberFormatting() {
            const currencyInputs = [
                'nilaiPasarFinal',
                'nilaiTanah',
                'nilaiIndikasiPasar',
                'nilaiIndikasiBiaya',
                'nilaiLikuidasi',
                'nilaiBangunan',
                'nilaiPerM2Tanah',
                'nilaiPerM2Bangunan'
            ];

            currencyInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', function(e) {
                        if (!isFinalized) {
                            this.value = formatNumber(this.value);
                        }
                    });
                }
            });
        }

        // Disable/enable inputs based on status
        function toggleInputs(status) {
            const currencyInputs = [
                'nilaiPasarFinal',
                'nilaiTanah',
                'nilaiIndikasiPasar',
                'nilaiIndikasiBiaya',
                'nilaiLikuidasi',
                'nilaiBangunan',
                'nilaiPerM2Tanah',
                'nilaiPerM2Bangunan'
            ];

            const statusSelect = document.getElementById('statusPenilaian');
            const submitButton = document.querySelector('#nilaiForm button[type="submit"]');
            
            // Only disable if status is sudah dinilai AND record is finalized
            const isDisabled = status === 'sudah dinilai' && isFinalized;
            
            // Disable/enable currency inputs
            currencyInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.disabled = isDisabled;
                    if (isDisabled) {
                        input.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                    } else {
                        input.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                    }
                }
            });

            // Disable/enable status select
            if (statusSelect) {
                statusSelect.disabled = isDisabled;
                if (isDisabled) {
                    statusSelect.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                } else {
                    statusSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                }
            }

            // Disable/enable submit button - only disable if sudah dinilai AND finalized
            if (submitButton) {
                if (isDisabled) {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Show/hide warning
            const warning = document.getElementById('statusWarning');
            if (isDisabled) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        function openNilaiModal(projectId, projectName, projectDesc, projectContact) {
            // Reset form first
            document.getElementById('nilaiForm').reset();
            
            document.getElementById('projectId').value = projectId;
            document.getElementById('projectName').textContent = projectName;
            document.getElementById('projectDesc').textContent = projectDesc;
            document.getElementById('projectContact').textContent = projectContact;
            
            // Set default status to sedang dinilai
            document.getElementById('statusPenilaian').value = 'sedang dinilai';
            
            // Ensure button is enabled by default
            const submitButton = document.querySelector('#nilaiForm button[type="submit"]');
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            
            document.getElementById('nilaiModal').classList.remove('hidden');

            // Setup number formatting
            setupNumberFormatting();

            // Fetch existing nilai data if it exists
            fetchNilaiData(projectId);
        }

        function closeNilaiModal() {
            document.getElementById('nilaiModal').classList.add('hidden');
            document.getElementById('nilaiForm').reset();
            isFinalized = false;
            toggleInputs('');
        }

        function fetchNilaiData(projectId) {
            fetch(`/properti/nilai/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        currentNilaiId = data.id;
                        isFinalized = data.status_penilaian === 'sudah dinilai';
                        
                        document.getElementById('statusPenilaian').value = data.status_penilaian || 'sedang dinilai';
                        document.getElementById('nilaiPasarFinal').value = data.nilai_pasar_final ? formatNumber(data.nilai_pasar_final.toString()) : '';
                        document.getElementById('nilaiTanah').value = data.nilai_tanah ? formatNumber(data.nilai_tanah.toString()) : '';
                        document.getElementById('nilaiIndikasiPasar').value = data.nilai_indikasi_dari_pasar ? formatNumber(data.nilai_indikasi_dari_pasar.toString()) : '';
                        document.getElementById('nilaiIndikasiBiaya').value = data.nilai_indikasi_dari_biaya ? formatNumber(data.nilai_indikasi_dari_biaya.toString()) : '';
                        document.getElementById('nilaiLikuidasi').value = data.nilai_likuidasi ? formatNumber(data.nilai_likuidasi.toString()) : '';
                        document.getElementById('nilaiBangunan').value = data.nilai_bangunan ? formatNumber(data.nilai_bangunan.toString()) : '';
                        document.getElementById('nilaiPerM2Tanah').value = data.nilai_per_m2_tanah ? formatNumber(data.nilai_per_m2_tanah.toString()) : '';
                        document.getElementById('nilaiPerM2Bangunan').value = data.nilai_per_m2_bangunan ? formatNumber(data.nilai_per_m2_bangunan.toString()) : '';

                        toggleInputs(data.status_penilaian);
                    } else {
                        currentNilaiId = null;
                        isFinalized = false;
                        // Set default status to sedang dinilai when no existing data
                        document.getElementById('statusPenilaian').value = 'sedang dinilai';
                        toggleInputs('');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.getElementById('nilaiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const projectId = document.getElementById('projectId').value;
            const selectedStatus = document.getElementById('statusPenilaian').value;
            const formData = new FormData(this);

            // Check if trying to edit finalized nilai
            if (isFinalized && selectedStatus === 'sudah dinilai') {
                alert('Nilai tidak dapat diubah karena status sudah "Sudah Dinilai"');
                return;
            }

            fetch(`/properti/nilai/${projectId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeNilaiModal();
                    // Reload page to see updated status
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Close modal when clicking outside of it
        document.getElementById('nilaiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNilaiModal();
            }
        });
    </script>
</x-app-layout>