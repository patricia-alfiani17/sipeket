@if(!$siswa)
    <p class="text-muted mb-0">Data profil siswa belum tersedia. Hubungi admin sanggar.</p>
@else
    <dl class="row mb-0">
        <dt class="col-sm-4">Nama Lengkap</dt>
        <dd class="col-sm-8">{{ $user->name }}</dd>

        <dt class="col-sm-4">Nama Panggilan</dt>
        <dd class="col-sm-8">{{ $user->nama_panggilan ?: '-' }}</dd>

        <dt class="col-sm-4">Username</dt>
        <dd class="col-sm-8">{{ $user->username }}</dd>

        <dt class="col-sm-4">Email</dt>
        <dd class="col-sm-8">{{ $user->email }}</dd>

        <dt class="col-sm-4">Status Akun</dt>
        <dd class="col-sm-8">
            <span class="badge badge-{{ $user->status === 'aktif' ? 'success' : 'danger' }}">
                {{ ucfirst($user->status) }}
            </span>
        </dd>

        <dt class="col-sm-4">Tingkat Saat Ini</dt>
        <dd class="col-sm-8">{{ $tingkatSaatIni }}</dd>

        <dt class="col-sm-4">Tempat Lahir</dt>
        <dd class="col-sm-8">{{ $user->tempat_lahir ?: '-' }}</dd>

        <dt class="col-sm-4">Tanggal Lahir</dt>
        <dd class="col-sm-8">{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d M Y') : '-' }}</dd>

        <dt class="col-sm-4">Jenis Kelamin</dt>
        <dd class="col-sm-8">
            {{ $user->jenis_kelamin === 'L' ? 'Laki-laki' : ($user->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
        </dd>

        <dt class="col-sm-4">Alamat</dt>
        <dd class="col-sm-8">{{ $user->alamat ?: '-' }}</dd>

        <dt class="col-sm-4">No. HP</dt>
        <dd class="col-sm-8">
            @if($user->no_hp)
                +62{{ ltrim(ltrim($user->no_hp, '+62'), '0') }}
            @else
                -
            @endif
        </dd>

        <dt class="col-sm-4">Asal Sekolah</dt>
        <dd class="col-sm-8">{{ $user->asal_sekolah ?: '-' }}</dd>

        <dt class="col-sm-4">Kelas</dt>
        <dd class="col-sm-8">{{ $user->kelas ?: '-' }}</dd>

        <dt class="col-sm-4">Nama Orang Tua</dt>
        <dd class="col-sm-8">{{ $user->nama_orangtua ?: '-' }}</dd>

        <dt class="col-sm-4">Pekerjaan Orang Tua</dt>
        <dd class="col-sm-8">{{ $user->pekerjaan_orangtua ?: '-' }}</dd>

        <dt class="col-sm-4">Kontak Orang Tua</dt>
        <dd class="col-sm-8">
            @if($user->kontak_orangtua)
                +62{{ ltrim(ltrim($user->kontak_orangtua, '+62'), '0') }}
            @else
                -
            @endif
        </dd>

        <dt class="col-sm-4">Alamat Orang Tua</dt>
        <dd class="col-sm-8">{{ $user->alamat_orangtua ?: '-' }}</dd>
    </dl>
@endif
