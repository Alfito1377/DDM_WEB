@extends('layouts.app')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Mitra</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data mitra dan tambahkan mitra baru secara langsung.</p>
            </div>
            <button onclick="openRegisterModal()"
                class="bg-green-600 text-white text-sm font-bold px-4 py-2.5 rounded-lg hover:bg-green-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Mitra Baru
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">Nama Mitra</th>
                        <th scope="col" class="px-6 py-4 font-bold">Jenis Mitra</th>
                        <th scope="col" class="px-6 py-4 font-bold">Pemilik</th>
                        <th scope="col" class="px-6 py-4 font-bold">Kontak (WA)</th>
                        <th scope="col" class="px-6 py-4 font-bold">Alamat</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center">Tanggal Daftar</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center">Aksi & QR Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($stores as $toko)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $toko->store_name }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $toko->jenis_mitra }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $toko->owner_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $toko->phone_number ?? '-' }}</td>

                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $toko->address }}">
                                {{ $toko->address }}</td>
                            <td class="px-6 py-4 text-center">
                                {{ \Carbon\Carbon::parse($toko->created_at)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $loginUrl = urlencode(url('/login/qr?token=' . $toko->qr_token_login));
                                    $qrImageLogin =
                                        'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $loginUrl;
                                    $checkpointUrl = urlencode(
                                        url('/login/qr/checkpoint?token=' . $toko->qr_token_checkpoint),
                                    );
                                    $qrImageCheckpoint =
                                        'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' .
                                        $checkpointUrl;
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <button
        onclick="showQR({id: {{ $toko->id }}, storeName: '{{ $toko->store_name }}', qrLoginUrl: '{{ $qrImageLogin }}', qrCheckpointUrl: '{{ $qrImageCheckpoint }}'})"
        class="px-3 py-1.5 border border-green-200 bg-green-50 text-green-700 rounded-lg text-xs font-bold hover:bg-green-100 transition">
        QR Code
    </button>
                                    <button onclick="openEditModal({{ $toko->id }})"
                                        class="px-3 py-1.5 border border-blue-200 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-100 transition">
                                        Edit
                                    </button>
                                    <button onclick="deleteStore({{ $toko->id }}, '{{ $toko->store_name }}')"
                                        class="px-3 py-1.5 border border-red-200 bg-red-50 text-red-700 rounded-lg text-xs font-bold hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada toko yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="registerModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Registrasi Toko Baru</h3>
                    <button onclick="closeRegisterModal()" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <form id="formRegisterCustomer" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Toko</label>
                            <input type="text" name="store_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Contoh: Toko Tani Makmur">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Pemilik Toko</label>
                            <input type="text" name="owner_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Masukkan nama lengkap pemilik">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">No. WhatsApp / Telepon</label>
                            <input type="number" name="phone_number" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                                placeholder="Contoh: 081234567890">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Lengkap</label>
                            <textarea name="address" rows="3" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition resize-none"
                                placeholder="Jalan, Kecamatan, Kota..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Jenis Mitra</label>
                            <select name="jenis_mitra_id" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                                <option value="" disabled selected>-- Pilih Jenis Mitra --</option>
                                @foreach ($jenisMitraList as $jenisMitra)
                                    <option value="{{ $jenisMitra->id }}">{{ $jenisMitra->nama_jenis_mitra }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                                Simpan & Generate QR Code
                            </button>
                        </div>
                    </form>
                    <div id="registerSuccessArea" class="hidden text-center">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <p class="text-green-800 font-bold text-lg mb-1">Toko Berhasil Didaftarkan!</p>
                        <p class="text-sm text-gray-600 mb-6">Berikut adalah QR Code akses untuk toko ini.</p>
                        <div class="flex justify-center gap-6 mb-6">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-bold text-gray-500 mb-1">QR Login</span>
                                <div
                                    class="w-36 h-36 bg-white p-2 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center">
                                    <img id="newQrImageLogin" src="" alt="QR Code Login"
                                        class="w-full h-full object-contain">
                                </div>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-bold text-gray-500 mb-1">QR Checkpoint</span>
                                <div
                                    class="w-36 h-36 bg-white p-2 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center">
                                    <img id="newQrImageCheckpoint" src="" alt="QR Code Checkpoint"
                                        class="w-full h-full object-contain">
                                </div>
                            </div>
                        </div>
                        <button onclick="reloadPage()"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition">
                            Tutup & Perbarui Tabel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="editModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Edit Data Toko</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <form id="formEditCustomer" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_store_id" name="id">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Toko</label>
                            <input type="text" id="edit_store_name" name="store_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Pemilik Toko</label>
                            <input type="text" id="edit_owner_name" name="owner_name" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">No. WhatsApp / Telepon</label>
                            <input type="number" id="edit_phone_number" name="phone_number" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Lengkap</label>
                            <textarea id="edit_address" name="address" rows="3" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Jenis Mitra</label>
                            <select id="edit_jenis_mitra_id" name="jenis_mitra_id" required
                                class="w-full border border-gray-300 p-3 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                                <option value="" disabled>-- Pilih Jenis Mitra --</option>
                                @foreach ($jenisMitraList as $jenisMitra)
                                    <option value="{{ $jenisMitra->id }}">{{ $jenisMitra->nama_jenis_mitra }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="qrModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white p-6 rounded-2xl shadow-xl max-w-xl w-full mx-4 transform transition-all">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 text-lg" id="modalStoreName">Nama Toko</h3>
                    <button onclick="closeQR()" class="text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
               <div class="flex justify-center items-center gap-6 mb-6">
    <div class="flex flex-col items-center mb-4">
        <h1 class="font-bold text-lg text-gray-800 mb-2">QR Code Login</h1>
        <!-- Gambar dibungkus link agar bisa diklik -->
        <a id="modalImageLinkLogin" href="#" target="_blank" class="cursor-pointer transition-all hover:scale-105 hover:shadow-lg rounded-xl" title="Klik untuk Cetak QR Login">
            <img id="modalQrImageLogin" src="" alt="QR Code Login" class="w-48 h-48 object-contain border-2 border-transparent hover:border-green-400 rounded-xl p-2 transition-colors">
        </a>
        <p class="text-[10px] text-green-600 mt-2 font-bold animate-pulse">👆 Klik gambar untuk cetak</p>
    </div>
    
    <div class="flex flex-col items-center mb-4">
        <h1 class="font-bold text-lg text-gray-800 mb-2">QR Code Checkpoint</h1>
        <a id="modalImageLinkCheckpoint" href="#" target="_blank" class="cursor-pointer transition-all hover:scale-105 hover:shadow-lg rounded-xl" title="Klik untuk Cetak QR Checkpoint">
            <img id="modalQrImageCheckpoint" src="" alt="QR Code Checkpoint" class="w-48 h-48 object-contain border-2 border-transparent hover:border-green-400 rounded-xl p-2 transition-colors">
        </a>
        <p class="text-[10px] text-green-600 mt-2 font-bold animate-pulse">👆 Klik gambar untuk cetak</p>
    </div>
                </div>
                <p class="text-xs text-center text-gray-500 mb-6">Scan QR ini menggunakan kamera perangkat toko untuk masuk
                    otomatis dan checkpoint sopir untuk konfirmasi.</p>
                <a id="modalDownloadLoginBtn" href="#" target="_blank"
                    class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold my-2 py-2.5 rounded-xl transition">
                    Buka Gambar QR Login di Tab Baru
                </a>
                <a id="modalDownloadCheckpointBtn" href="#" target="_blank"
                    class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold my-2 py-2.5 rounded-xl transition">
                    Buka Gambar QR Checkpoint di Tab Baru
                </a>
            </div>
        </div>

        <script>
            function openRegisterModal() {
                document.getElementById('registerModal').classList.remove('hidden');
                document.getElementById('formRegisterCustomer').classList.remove('hidden');
                document.getElementById('registerSuccessArea').classList.add('hidden');
            }

            function closeRegisterModal() {
                document.getElementById('registerModal').classList.add('hidden');
                document.getElementById('formRegisterCustomer').reset();
            }

            function reloadPage() {
                window.location.reload();
            }

            // SUBMIT FORM REGISTRASI VIA AJAX
            document.getElementById('formRegisterCustomer').addEventListener('submit', async function(e) {
                e.preventDefault();
                const btnSubmit = e.target.querySelector('button[type="submit"]');
                const originalText = btnSubmit.innerHTML;
                btnSubmit.innerHTML = 'Memproses...';
                btnSubmit.disabled = true;

                let formData = new FormData(this);
                try {
                    const response = await fetch('/superadmin/register-customer', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        document.getElementById('formRegisterCustomer').classList.add('hidden');
                        document.getElementById('newQrImageLogin').src = result.qr_image_login;
                        document.getElementById('newQrImageCheckpoint').src = result.qr_checkpoint_image;
                        document.getElementById('registerSuccessArea').classList.remove('hidden');
                        e.target.reset();
                    } else {
                        alert(result.message);
                    }
                } catch (error) {
                    alert('Terjadi kesalahan koneksi server.');
                } finally {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                }
            });

            // --- 👇 LOGIKA MODAL EDIT & HAPUS (BARU) ---
            async function openEditModal(id) {
                try {
                    // Mengambil data customer spesifik via AJAX (Sesuaikan route endpoint ini di Laravel kamu)
                    const response = await fetch(`/superadmin/edit-customer/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await response.json();

                    if (result.success) {
                        document.getElementById('edit_store_id').value = result.data.id;
                        document.getElementById('edit_store_name').value = result.data.store_name;
                        document.getElementById('edit_owner_name').value = result.data.owner_name;
                        document.getElementById('edit_phone_number').value = result.data.phone_number;
                        document.getElementById('edit_address').value = result.data.address;
                        document.getElementById('edit_jenis_mitra_id').value = result.data.jenis_mitra_id;

                        document.getElementById('editModal').classList.remove('hidden');
                    } else {
                        alert(result.message || 'Gagal mengambil data customer.');
                    }
                } catch (error) {
                    alert('Terjadi kesalahan saat mengambil data dari server.');
                }
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('formEditCustomer').reset();
            }

            // SUBMIT FORM EDIT VIA AJAX
            document.getElementById('formEditCustomer').addEventListener('submit', async function(e) {
                e.preventDefault();
                const id = document.getElementById('edit_store_id').value;
                const btnSubmit = e.target.querySelector('button[type="submit"]');
                const originalText = btnSubmit.innerHTML;
                btnSubmit.innerHTML = 'Menyimpan...';
                btnSubmit.disabled = true;

                let formData = new FormData(this);
                try {
                    // Sesuaikan endpoint update di Laravel kamu
                    const response = await fetch(`/superadmin/update-customer/${id}`, {
                        method: 'POST', // Menggunakan POST dengan _method PUT di form data
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Data mitra berhasil diperbarui!');
                        reloadPage();
                    } else {
                        alert(result.message);
                    }
                } catch (error) {
                    alert('Terjadi kesalahan koneksi server.');
                } finally {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                }
            });

            // FUNGSI HAPUS DATA
            async function deleteStore(id, storeName) {
                if (confirm(
                    `Apakah Anda yakin ingin menghapus mitra "${storeName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                    try {
                        // Sesuaikan endpoint destroy di Laravel kamu
                        const response = await fetch(`/superadmin/delete-customer/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const result = await response.json();
                        if (result.success) {
                            alert('Mitra berhasil dihapus.');
                            reloadPage();
                        } else {
                            alert(result.message || 'Gagal menghapus mitra.');
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi server.');
                    }
                }
            }

            // LOGIKA MODAL LIHAT QR LAMA
 // Pastikan ada kata "id" di dalam kurung kurawal ini
function showQR({ id, storeName, qrLoginUrl, qrCheckpointUrl }) {
    document.getElementById('modalStoreName').innerText = storeName;
    document.getElementById('modalQrImageLogin').src = qrLoginUrl;
    document.getElementById('modalQrImageCheckpoint').src = qrCheckpointUrl;

    // Update link untuk tombol
    document.getElementById('modalDownloadLoginBtn').href = `/superadmin/mitra/${id}/print-qr?type=login`;
    document.getElementById('modalDownloadCheckpointBtn').href = `/superadmin/mitra/${id}/print-qr?type=checkpoint`;
    document.getElementById('modalDownloadLoginBtn').innerText = "Cetak QR Login";
    document.getElementById('modalDownloadCheckpointBtn').innerText = "Cetak QR Checkpoint";

    // Update link untuk gambar
    document.getElementById('modalImageLinkLogin').href = `/superadmin/mitra/${id}/print-qr?type=login`;
    document.getElementById('modalImageLinkCheckpoint').href = `/superadmin/mitra/${id}/print-qr?type=checkpoint`;

    document.getElementById('qrModal').classList.remove('hidden');
}
            function closeQR() {
                document.getElementById('qrModal').classList.add('hidden');
                document.getElementById('modalQrImageLogin').src = '';
                document.getElementById('modalQrImageCheckpoint').src = '';
            }
        </script>
    @endsection
