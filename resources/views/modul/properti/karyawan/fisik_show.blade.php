<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-6 text-[28px] font-poppins font-bold text-gray-800">Detail Project</h1>

            <div class="bg-white p-8 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.04)]">
                <h2 class="text-lg font-semibold text-gray-800">{{ $project->nama_project }}</h2>
                <p class="text-sm text-gray-500">Client: {{ $project->client->name ?? 'Unknown' }}</p>
                <p class="text-sm text-gray-500">Status: {{ ucfirst($project->status ?? '-') }}</p>
                <p class="text-sm text-gray-500">Contract Date: {{ optional($project->contract_date)->format('Y-m-d') ?? '-' }}</p>

                <div class="mt-6">
                    <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                    <div class="prose max-w-none text-sm text-gray-600">{{ $project->deskripsi ?? '-' }}</div>
                </div>

                <div class="mt-6 flex justify-end">
                    @if(\Illuminate\Support\Facades\Route::has('projects.edit'))
                        <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-50 text-yellow-700 rounded-md mr-2">Edit</a>
                    @endif
                    <a href="{{ route('properti.karyawan') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
