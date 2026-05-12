<div class="space-y-4">
    <form action="{{ route('pemeliharaan.input_servis.store') }}" method="POST"
        class="bg-white rounded-lg shadow-sm">
        @csrf

        <!-- HEADER -->
        <div class="px-4 py-4 border-b mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                <i class="fas fa-file-invoice mr-3"></i>Form Input Riwayat Servis
            </h1>
        </div>

        <!-- BODY -->
        <div class="px-4 pb-2">
            <!-- ALERT -->
            @if(session('success'))
            <div id="alert-message"
                class="mb-2 p-2 rounded-lg bg-green-100 text-green-800 border border-green-300">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div id="alert-message"
                class="mb-2 p-2 rounded-lg bg-red-100 text-red-800 border border-red-300">
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
            @endif

            <!-- TANGGAL -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Servis <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_servis"
                    class="w-full border rounded-lg px-4 py-2"
                    required>
            </div>
            <!-- PILIH KENDARAAN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan <span class="text-red-500">*</span></label>
                <button type="button" onclick="openKendaraanModal('servis')"
                    class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 whitespace-nowrap text-md mb-2">
                    <i class="fas fa-search mr-1 text-xs"></i> Pilih Kendaraan
                </button>
                <div class="w-full overflow-x-auto" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Nomor Polisi</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Nama Kendaraan</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Tahun</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-700 text-left uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="selectedKendaraanTable_servis" class="border-b border-gray-300">
                            <tr id="emptyKendaraanRow_servis">
                                <td colspan="4"
                                    class="px-4 py-4 text-center text-sm text-gray-700 italic">
                                    Belum ada kendaraan dipilih
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="kendaraan_id_servis" name="kendaraan_id">

                <p id="kendaraanError_servis"
                    class="text-sm text-red-500 mt-1 hidden opacity-0 transition-opacity duration-500">
                    Kendaraan belum dipilih.
                </p>
            </div>
            <!-- NAMA PENGURUS -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengurus</label>
                <input type="text" name="nama_pengurus" placeholder="Masukkan nama yang mengurus servis kendaraan ini"
                    class="w-full border rounded-lg px-4 py-2">
            </div>
            <!-- KETERANGAN -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="2" placeholder="Masukkan keterangan (opsional)"
                    class="w-full border rounded-lg px-4 py-2"></textarea>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-2 px-4 py-4 border-t border-gray-200">
            <button type="reset" onclick="resetForm()"
                class="px-3 py-1.5 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                Reset
            </button>
            <button type="submit"
                class="px-3 py-1.5 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </div>
    </form>
</div>
