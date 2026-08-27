@extends('layout.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('page_title', 'Data Pendaftaran')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pendaftaran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Data Pendaftaran</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pendaftar</span>
                        <span class="info-box-number">{{ $totalPendaftar }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Menunggu</span>
                        <span class="info-box-number">{{ $pendingCount }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Telah Diproses</span>
                        <span class="info-box-number">{{ $processedCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tabel Pendaftaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="pendaftaran" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <th>No. Hp</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftaran as $item)
                                <tr>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->kontak_aktif ? '+62' . ltrim(ltrim($item->kontak_aktif, '+62'), '0') : '-' }}</td>
                                    <td>{{ optional($item->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($item->status === 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                        @elseif($item->status === 'diterima')
                                        <span class="badge badge-success">Disetujui</span>
                                        @elseif($item->status === 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                        @else
                                        <span class="badge badge-secondary">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info btn-detail" data-toggle="modal" data-target="#detailModal"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_lengkap }}"
                                            data-email="{{ $item->email }}"
                                            data-tanggal_lahir="{{ $item->tanggal_lahir ? date('d-m-Y', strtotime($item->tanggal_lahir)) : '' }}"
                                            data-tempat_lahir="{{ $item->tempat_lahir }}"
                                            data-jenis_kelamin="{{ $item->jenis_kelamin }}"
                                            data-nama_panggilan="{{ $item->nama_panggilan }}"
                                            data-asal_sekolah="{{ $item->asal_sekolah }}"
                                            data-kelas="{{ $item->kelas }}"
                                            data-kontak_aktif="{{ $item->kontak_aktif }}"
                                            data-tingkat="{{ $item->tingkat->nama_tingkat ?? 'N/A' }}"
                                            data-nama_orangtua="{{ $item->nama_orangtua }}"
                                            data-pekerjaan_orangtua="{{ $item->pekerjaan_orangtua }}"
                                            data-kontak_orangtua="{{ $item->kontak_orangtua }}"
                                            data-alamat_orangtua="{{ $item->alamat_orangtua }}"
                                            data-tanggal_daftar="{{ optional($item->created_at)->format('d-m-Y') }}"
                                            data-status="{{ $item->status }}"
                                            data-catatan_admin="{{ $item->catatan_admin }}"
                                            data-akta="{{ $item->akta_kelahiran_url ?: ($item->akta_kelahiran ? route('admin.pendaftaran.akta', $item->id) : '') }}"
                                            data-is-pending="{{ $item->status === 'pending' ? '1' : '0' }}">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>
                                        @if($item->status === 'diterima' || $item->status === 'ditolak')
                                        <button type="button" class="btn btn-sm btn-success btn-whatsapp-table"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_lengkap }}"
                                            data-kontak_aktif="{{ $item->kontak_aktif }}"
                                            data-status="{{ $item->status }}"
                                            data-catatan_admin="{{ $item->catatan_admin }}"
                                            title="Kirim WhatsApp">
                                            <i class="fab fa-whatsapp"></i> WA
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data pendaftaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                        <div class="mt-3">
    {{ $pendaftaran->links() }}
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Pendaftar -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pendaftar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="statusForm" method="POST" action="">
                @csrf
                <input type="hidden" name="status" id="formStatus" value="diterima">
                <div class="modal-body">
                    <h5 class="mb-3">Data Pribadi</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td id="detailNama"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="detailEmail"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td id="detailTanggalLahir"></td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td id="detailTempatLahir"></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td id="detailJenisKelamin"></td>
                            </tr>
                            <tr>
                                <th>Nama Panggilan</th>
                                <td id="detailNamaPanggilan"></td>
                            </tr>
                            <tr>
                                <th>Asal Sekolah</th>
                                <td id="detailAsalSekolah"></td>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <td id="detailKelas"></td>
                            </tr>
                            <tr>
                                <th>Kontak Aktif</th>
                                <td id="detailKontakAktif"></td>
                            </tr>
                            <tr>
                                <th>Tingkat</th>
                                <td id="detailTingkat"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Daftar</th>
                                <td id="detailTanggalDaftar"></td>
                            </tr>
                            <tr>
                                <th>Status Saat Ini</th>
                                <td id="detailStatus"></td>
                            </tr>
                            <tr>
                                <th>Catatan Admin</th>
                                <td id="detailCatatanAdmin"></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-4 mb-3">Data Orang Tua</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Nama Orang Tua</th>
                                <td id="detailNamaOrangtua"></td>
                            </tr>
                            <tr>
                                <th>Pekerjaan Orang Tua</th>
                                <td id="detailPekerjaanOrangtua"></td>
                            </tr>
                            <tr>
                                <th>Kontak Orang Tua</th>
                                <td id="detailKontakOrangtua"></td>
                            </tr>
                            <tr>
                                <th>Alamat Orang Tua</th>
                                <td id="detailAlamatOrangtua"></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <label for="formCatatanAdmin">Catatan Admin</label>
                        <textarea name="catatan_admin" id="formCatatanAdmin" class="form-control" rows="3"></textarea>
                        <small class="form-text text-muted">Catatan wajib ketika status ditolak, opsional jika disetujui.</small>
                    </div>

                    <div class="form-group">
                        <label>Preview Akta Kelahiran</label>
                        <div id="aktaPreviewContainer">
                            <p id="aktaPreviewEmpty" class="text-muted">Tidak ada file akta.</p>
                            <iframe id="aktaPreview" src="" class="w-100" style="height:400px; display:none; border:1px solid #ddd;"></iframe>
                            <p id="aktaLink" class="mt-2" style="display:none;"><a href="" target="_blank" id="aktaUrl">Buka akta di tab baru</a></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="whatsappButton" class="btn btn-success" style="display:none;">
                        <i class="fab fa-whatsapp"></i> Kirim WhatsApp
                    </button>
                    <button type="button" id="rejectButton" class="btn btn-danger" style="display:none;">Tolak</button>
                    <button type="button" id="acceptButton" class="btn btn-success" style="display:none;">Terima</button>
                    <button type="submit" id="saveButton" class="btn btn-primary" style="display:none;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailButtons = document.querySelectorAll('.btn-detail');
        const statusForm = document.getElementById('statusForm');
        const formStatus = document.getElementById('formStatus');
        const formCatatan = document.getElementById('formCatatanAdmin');
        const aktaPreview = document.getElementById('aktaPreview');
        const aktaPreviewEmpty = document.getElementById('aktaPreviewEmpty');
        const aktaUrl = document.getElementById('aktaUrl');
        const aktaLink = document.getElementById('aktaLink');
        const rejectButton = document.getElementById('rejectButton');
        const acceptButton = document.getElementById('acceptButton');
        const whatsappButton = document.getElementById('whatsappButton');
        const saveButton = document.getElementById('saveButton');
        const detailModal = document.getElementById('detailModal');
        const whatsappTableButtons = document.querySelectorAll('.btn-whatsapp-table');

        let currentPhone = '';
        let currentName = '';
        let currentPendaftaranId = '';

        if (!statusForm || !formStatus || !formCatatan || !aktaPreview || !aktaPreviewEmpty || !aktaUrl || !aktaLink || !rejectButton || !acceptButton) {
            return;
        }

        function formatPhone(value) {
            if (!value) return '-';
            let phone = value.toString().trim();
            if (phone.startsWith('0')) {
                phone = '+62' + phone.slice(1);
            } else if (phone.startsWith('62')) {
                phone = '+' + phone;
            } else if (!phone.startsWith('+')) {
                phone = '+62' + phone;
            }
            return phone;
        }

        function generateWhatsAppMessage(status, catatan = '', username = '', password = '') {
            let message = `Halo ${currentName},\n\n`;

            if (status === 'diterima' && username && password) {
                message += `Selamat! Pendaftaran Anda di Sanggar Tari Dharmo Yuwono *DITERIMA*!\n\n`;
                message += `Berikut data login Anda:\n`;
                message += `Username: ${username}\n`;
                message += `Password: ${password}\n\n`;
                message += `Instruksi Login:\n`;
                message += `1. Buka website kami\n`;
                message += `2. Klik Login\n`;
                message += `3. Masukkan username dan password di atas\n`;
                message += `4. Klik Masuk\n\n`;
                message += `Terima kasih telah mendaftar!`;
            } else if (status === 'ditolak') {
                message += `Terima kasih telah mendaftar di Sanggar Tari Dharmo Yuwono.\n\n`;
                message += `Dengan berat hati, kami sampaikan bahwa pendaftaran Anda saat ini *STATUS DITOLAK*.\n\n`;
                message += `Catatan: ${catatan || 'Tidak ada catatan tambahan'}\n\n`;
                message += `Silakan hubungi kami untuk informasi lebih lanjut.`;
            }
            return message;
        }

        function getPhoneForWhatsApp(phone) {
            phone = phone.replace(/[^0-9]/g, '');
            if (phone.startsWith('0')) {
                phone = '62' + phone.slice(1);
            } else if (!phone.startsWith('62')) {
                phone = '62' + phone;
            }
            return phone;
        }

        function updateActionButtons(isPending) {
            if (isPending === '1') {
                rejectButton.style.display = 'inline-block';
                acceptButton.style.display = 'inline-block';
                whatsappButton.style.display = 'none';
                saveButton.style.display = 'none';
            } else {
                rejectButton.style.display = 'none';
                acceptButton.style.display = 'none';
                whatsappButton.style.display = 'inline-block';
                saveButton.style.display = 'none';
            }
        }

        detailButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const detailUrl = "{{ url('admin/pendaftaran') }}" + '/' + button.dataset.id + '/status';
                const modalStatus = button.dataset.status || '-';
                const isPending = button.dataset.isPending || '0';

                currentPhone = button.dataset.kontak_aktif;
                currentName = button.dataset.nama;
                currentPendaftaranId = button.dataset.id;

                statusForm.action = detailUrl;
                document.getElementById('detailNama').textContent = button.dataset.nama || '-';
                document.getElementById('detailEmail').textContent = button.dataset.email || '-';
                document.getElementById('detailTanggalLahir').textContent = button.dataset.tanggal_lahir || '-';
                document.getElementById('detailTempatLahir').textContent = button.dataset.tempat_lahir || '-';
                document.getElementById('detailJenisKelamin').textContent = button.dataset.jenis_kelamin == 'L' ? 'Laki-laki' : (button.dataset.jenis_kelamin == 'P' ? 'Perempuan' : '-');
                document.getElementById('detailNamaPanggilan').textContent = button.dataset.nama_panggilan || '-';
                document.getElementById('detailAsalSekolah').textContent = button.dataset.asal_sekolah || '-';
                document.getElementById('detailKelas').textContent = button.dataset.kelas || '-';
                document.getElementById('detailKontakAktif').textContent = formatPhone(button.dataset.kontak_aktif);
                document.getElementById('detailTingkat').textContent = button.dataset.tingkat || '-';
                document.getElementById('detailNamaOrangtua').textContent = button.dataset.nama_orangtua || '-';
                document.getElementById('detailPekerjaanOrangtua').textContent = button.dataset.pekerjaan_orangtua || '-';
                document.getElementById('detailKontakOrangtua').textContent = formatPhone(button.dataset.kontak_orangtua);
                document.getElementById('detailAlamatOrangtua').textContent = button.dataset.alamat_orangtua || '-';
                document.getElementById('detailTanggalDaftar').textContent = button.dataset.tanggal_daftar || '-';
                document.getElementById('detailStatus').textContent = modalStatus === 'pending' ? 'Menunggu' : (modalStatus === 'diterima' ? 'Disetujui' : (modalStatus === 'ditolak' ? 'Ditolak' : modalStatus));
                document.getElementById('detailCatatanAdmin').textContent = button.dataset.catatan_admin || '-';

                formStatus.value = 'diterima';
                formCatatan.value = button.dataset.catatan_admin || '';
                formCatatan.required = false;

                updateActionButtons(isPending);

                if (button.dataset.akta) {
                    aktaPreviewEmpty.style.display = 'none';
                    aktaPreview.style.display = 'block';
                    aktaPreview.src = button.dataset.akta;
                    aktaLink.style.display = 'block';
                    aktaUrl.href = button.dataset.akta;
                } else {
                    aktaPreviewEmpty.style.display = 'block';
                    aktaPreview.style.display = 'none';
                    aktaPreview.src = '';
                    aktaLink.style.display = 'none';
                    aktaUrl.href = '';
                }
            });
        });

        rejectButton.addEventListener('click', function() {
            formStatus.value = 'ditolak';
            if (!formCatatan.value.trim()) {
                alert('Catatan admin wajib diisi ketika menolak pendaftar.');
                formCatatan.focus();
                return;
            }

            const formData = new FormData(statusForm);

            fetch(statusForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pendaftar berhasil ditolak.');
                        $(detailModal).modal('hide');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan.');
                });
        });

        acceptButton.addEventListener('click', function() {
            formStatus.value = 'diterima';

            const formData = new FormData(statusForm);

            fetch(statusForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.credentials) {
                        alert(`Pendaftar berhasil diterima!\n\nUsername: ${data.credentials.username}\nPassword: ${data.credentials.password}`);
                        $(detailModal).modal('hide');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan.');
                });
        });

        function buildAcceptanceMessage(nama, username, password) {
            let message = `Halo *${nama}* 👋\n\n`;
            message += `Selamat! Pendaftaran Anda di *Sanggar Tari Dharmo Yuwono* telah *DITERIMA* 🎉\n\n`;
            message += `----------------------------------------\n`;
            message += `🔐 *DATA AKUN & LOGIN*\n`;
            message += `• *Username* : ${username}\n`;
            message += `• *Password* : ${password}\n\n`;
            message += `*Instruksi Login:*\n`;
            message += `1. Buka website SIPEKET\n`;
            message += `2. Pilih menu *Login*\n`;
            message += `3. Masukkan username dan password di atas\n`;
            message += `4. Klik Masuk\n\n`;
            message += `----------------------------------------\n`;
            message += `💳 *INFORMASI ADMINISTRASI*\n\n`;
            message += `• *Biaya Pendaftaran* : Rp75.000\n\n`;
            message += `• *SPP per bulan* :\n`;
            message += `  - *Pradasar & Dasar 1.1* : Rp125.000/bulan (Rp100.000 iuran + Rp25.000 tabungan pentas tahunan)\n`;
            message += `  - *Dasar 1.2 s.d. Terampil 2* : Rp100.000/bulan\n\n`;
            message += `*Informasi Pembayaran:*\n`;
            message += `1. *Transfer Bank*:\n`;
            message += `   • *BNI* : 1234567890\n`;
            message += `   • *a.n.* Sanggar Dharmo Yuwono Purwokerto\n`;
            message += `   _(Mohon kirimkan bukti transfer ke nomor WA Admin ini untuk proses konfirmasi)_\n\n`;
            message += `2. *Tunai*:\n`;
            message += `   • Pembayaran tunai dapat dilakukan langsung melalui *Bendahara Sanggar*.\n\n`;
            message += `----------------------------------------\n`;
            message += `Terima kasih dan selamat bergabung! 🙏✨`;
            return message;
        }

        whatsappButton.addEventListener('click', function() {
            const status = document.getElementById('detailStatus').textContent.trim();

            if (status === 'Disetujui') {
                // For accepted registrations, fetch credentials and send with login info
                fetch(`{{ url('admin/pendaftaran') }}/${currentPendaftaranId}/credentials`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.credentials && data.credentials.username && data.credentials.password) {
                            const message = buildAcceptanceMessage(currentName, data.credentials.username, data.credentials.password);

                            const phone = getPhoneForWhatsApp(currentPhone);
                            const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                            window.open(whatsappUrl, '_blank');
                        } else {
                            alert('Data credentials tidak ditemukan.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal mengambil data credentials. Pastikan pendaftar sudah diterima.');
                    });
            } else if (status === 'Ditolak') {
                // For rejected registrations, send rejection message with reason
                const catatan = document.getElementById('detailCatatanAdmin').textContent.trim() || 'Tidak ada catatan tambahan';
                let message = `Halo ${currentName},\n\n`;
                message += `Terima kasih telah mendaftar di Sanggar Tari Dharmo Yuwono.\n\n`;
                message += `Dengan berat hati, kami sampaikan bahwa pendaftaran Anda saat ini *DITOLAK*.\n\n`;
                message += `Alasan: ${catatan}\n\n`;
                message += `Silakan hubungi kami untuk informasi lebih lanjut.`;

                const phone = getPhoneForWhatsApp(currentPhone);
                const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            } else {
                alert('Status registrasi harus Disetujui atau Ditolak untuk mengirim pesan WhatsApp.');
            }
        });

        // Handle WhatsApp buttons in table
        whatsappTableButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const pendaftaranId = button.dataset.id;
                const nama = button.dataset.nama;
                const kontakAktif = button.dataset.kontak_aktif;
                const status = button.dataset.status;
                const catatanAdmin = button.dataset.catatan_admin;

                if (status === 'diterima') {
                    // For accepted registrations, fetch credentials and send with login info
                    fetch(`{{ url('admin/pendaftaran') }}/${pendaftaranId}/credentials`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.credentials && data.credentials.username && data.credentials.password) {
                                const message = buildAcceptanceMessage(nama, data.credentials.username, data.credentials.password);

                                const phone = getPhoneForWhatsApp(kontakAktif);
                                const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                                window.open(whatsappUrl, '_blank');
                            } else {
                                alert('Data credentials tidak ditemukan.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal mengambil data credentials. Pastikan pendaftar sudah diterima.');
                        });
                } else if (status === 'ditolak') {
                    // For rejected registrations, send rejection message with reason
                    const catatan = catatanAdmin.trim() || 'Tidak ada catatan tambahan';
                    let message = `Halo ${nama},\n\n`;
                    message += `Terima kasih telah mendaftar di Sanggar Tari Dharmo Yuwono.\n\n`;
                    message += `Dengan berat hati, kami sampaikan bahwa pendaftaran Anda saat ini *DITOLAK*.\n\n`;
                    message += `Alasan: ${catatan}\n\n`;
                    message += `Silakan hubungi kami untuk informasi lebih lanjut.`;

                    const phone = getPhoneForWhatsApp(kontakAktif);
                    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                }
            });
        });

        formStatus.addEventListener('change', function() {
            formCatatan.required = formStatus.value === 'ditolak';
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#pendaftaran').DataTable({
            "responsive": true,
            "lengthChange": false,
            "paging": true,
            "pageLength": 10,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#pendaftaran_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection