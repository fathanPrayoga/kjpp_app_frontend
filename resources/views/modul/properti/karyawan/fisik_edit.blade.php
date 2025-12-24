<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-3xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-6 text-[28px] font-poppins font-bold text-gray-800">Edit Project</h1>

            <div class="bg-white p-8 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)]">
                <form method="POST" action="{{ route('projects.update', $project->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-medium text-gray-700">Nama Project</label>
                        <input type="text" name="nama_project" value="{{ old('nama_project', $project->nama_project) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('deskripsi', $project->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium text-gray-700">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300">
                            <option value="pending" {{ (old('status', $project->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ (old('status', $project->status) == 'proses') ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ (old('status', $project->status) == 'selesai') ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="pt-4 text-right">
                        <x-primary-button>
                            Simpan
                        </x-primary-button>
                        <a href="{{ route('properti.karyawan') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>