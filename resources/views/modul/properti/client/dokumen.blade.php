<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-8 text-[32px] font-poppins font-bold text-gray-800">
                Dokumen Properti
            </h1>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <form
                    method="POST"
                    action="{{ route('client.projects.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf

                    <!-- Nama Project -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Nama Project
                        </label>
                        <input
                            type="text"
                            name="nama_project"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                    </div>

                    <!-- Contract Date -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Contract Date
                        </label>
                        <input
                            type="date"
                            name="contract_date"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Contact Person
                        </label>
                        <input
                            type="text"
                            name="contact_person"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Deskripsi
                        </label>
                        <textarea
                            name="deskripsi"
                            rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        ></textarea>
                    </div>

                    <!-- Upload Dokumen -->
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Upload Dokumen (PDF)
                        </label>
                        <input
                            type="file"
                            name="documents[]"
                            multiple
                            accept="application/pdf"
                            class="w-full text-sm text-gray-600
                                   file:mr-4 file:rounded-full file:border-0
                                   file:bg-green-50 file:px-4 file:py-2
                                   file:text-green-700 hover:file:bg-green-100"
                        >
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 text-right">
                        <x-primary-button>
                            Kirim Dokumen
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
