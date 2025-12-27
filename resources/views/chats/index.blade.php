<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Obrolan') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex bg-white shadow rounded-lg overflow-visible" style="height:80vh;">
                <!-- Left: Users / Search -->
                <div class="w-1/3 border-r p-4 bg-white shadow-lg" id="chat-left">
                    <div class="flex items-center mb-4">
                        <input id="user-search" type="text" placeholder="Pencarian" class="w-full rounded-md border-gray-200 px-3 py-2" />
                    </div>
                    <div class="mb-3 flex gap-2" id="chat-filters">
                        <button id="filter-all" data-filter="all" class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">All</button>
                        <button id="filter-unread" data-filter="unread" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">Unread</button>
                        <button id="filter-important" data-filter="important" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">Important</button>
                    </div>

                    <ul id="chat-users" class="space-y-3 overflow-auto h-[calc(80vh-220px)]">
                        <!-- User items injected by JS -->
                    </ul>
                </div>

                <!-- Right: Conversation -->
                <div class="flex-1 p-4 flex flex-col bg-white shadow-lg" id="chat-right">
                    <div id="chat-header" class="mb-4">
                    <div id="chat-top-bar" class="bg-gradient-to-l from-[#7CC576] to-white rounded-lg p-4 flex items-center gap-4 shadow-sm transition-colors duration-200">
                        <img id="chat-header-avatar" src="" alt="avatar" class="h-14 w-14 rounded-full border-2 border-white object-cover" />
                        <div>
                            <h3 id="chat-header-name" class="text-xl font-semibold tracking-wide">Siapa yang ingin di chat?</h3>
                            <div id="chat-header-sub" class="text-sm text-gray-700">&nbsp;</div>
                        </div>
                    </div>
                    <div class="border-b border-gray-200 mt-3"></div>
                </div>

                    <div id="chat-empty" class="flex-1 flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-lg font-medium">Siapa yang ingin di chat?</div>
                            <div class="text-sm text-gray-500">Pilih pengguna dari daftar di sebelah kiri untuk memulai percakapan.</div>
                        </div>
                    </div>

                    <div id="messages" class="flex-1 overflow-auto px-2 pb-4 hidden">
                        <!-- Messages injected by JS -->
                    </div>

                    <div id="message-form-wrap" class="pt-3 border-t mt-3 hidden">
                        <form id="message-form" class="flex gap-2 items-center">
                            <input type="hidden" id="recipient-id" name="recipient_id" />
                            <input id="message-input" type="text" placeholder="type here.." class="flex-1 rounded-full border-gray-200 px-4 py-3" autocomplete="off" disabled />

                            <input id="attachment-input" type="file" name="attachment" class="hidden" />
                            <input type="hidden" id="attachment-category" name="attachment_category" value="" />

                            <div class="relative">
                                <button type="button" id="attachment-btn" class="ms-2 p-2 text-gray-600" title="Lampirkan file" aria-label="Lampirkan file" disabled>
                                    <!-- Paperclip / attachment icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.44 11.05L12.36 20.13a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24l-9.19 9.19a1 1 0 01-1.41-1.41l9.19-9.19" />
                                    </svg>
                                </button>

                                <div id="attachment-menu" class="hidden absolute -top-44 right-0 w-44 bg-white border rounded shadow p-2 z-50">
                                    <div class="text-sm text-gray-600">Pilih jenis lampiran</div>
                                    <ul class="mt-2 space-y-1">
                                        <li><button type="button" class="attachment-option w-full text-left px-2 py-1 rounded hover:bg-gray-100" data-accept="image/*,video/*" data-category="foto_video">Foto & Video</button></li>
                                        <li><button type="button" class="attachment-option w-full text-left px-2 py-1 rounded hover:bg-gray-100" data-accept="audio/*" data-category="audio">Audio</button></li>
                                        <li><button type="button" class="attachment-option w-full text-left px-2 py-1 rounded hover:bg-gray-100" data-accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip" data-category="dokumen">Dokumen</button></li>
                                    </ul>
                                </div>
                            </div>

                            <button type="submit" class="ms-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full" disabled>➤</button>
                        </form>
                    </div>

                    <!-- Attachment preview modal -->
                    <div id="attachment-preview-overlay" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-4">
                            <div id="attachment-preview-content" class="mb-4 flex items-center justify-center min-h-[120px]"></div>
                            <div id="attachment-preview-meta" class="text-sm text-gray-600 mb-4"></div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" id="attachment-cancel-btn" class="px-3 py-1 rounded border">Batal</button>
                                <button type="button" id="attachment-send-btn" class="px-3 py-1 rounded bg-green-500 text-white">Kirim</button>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm deletion modal -->
                    <div id="confirm-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-4">
                            <h3 id="confirm-modal-title" class="text-lg font-semibold mb-2">Hapus Pesan</h3>
                            <p id="confirm-modal-message" class="text-sm text-gray-700 mb-4">Anda yakin ingin menghapus pesan ini?</p>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" id="confirm-cancel-btn" class="px-3 py-1 rounded border">Batal</button>
                                <button type="button" id="confirm-ok-btn" class="px-3 py-1 rounded bg-red-600 text-white">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <!-- Edit message modal -->
                    <div id="edit-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-4">
                            <h3 class="text-lg font-semibold mb-2">Ubah Pesan</h3>
                            <textarea id="edit-modal-text" rows="4" class="w-full rounded border p-2" placeholder="Ubah pesan..."></textarea>
                            <div class="flex items-center justify-end gap-2 mt-3">
                                <button type="button" id="edit-cancel-btn" class="px-3 py-1 rounded border">Batal</button>
                                <button type="button" id="edit-save-btn" class="px-3 py-1 rounded bg-green-600 text-white">Simpan</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
