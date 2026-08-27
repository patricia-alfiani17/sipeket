<div class="form-group">
    <label>Hak Akses Tingkatan</label>
    <p class="text-muted small mb-2">
        Pilih tingkatan yang dapat diakses pelatih. Kosongkan semua checkbox jika pelatih boleh mengakses <strong>semua tingkatan</strong>.
    </p>
    <div class="row">
        @foreach($tingkats as $tingkat)
        <div class="col-md-6 col-lg-4 mb-2">
            <div class="custom-control custom-checkbox">
                <input type="checkbox"
                    class="custom-control-input"
                    id="tingkat_{{ $tingkat->id }}"
                    name="tingkat_ids[]"
                    value="{{ $tingkat->id }}"
                    {{ in_array($tingkat->id, old('tingkat_ids', $assignedTingkatIds ?? [])) ? 'checked' : '' }}>
                <label class="custom-control-label" for="tingkat_{{ $tingkat->id }}">
                    {{ $tingkat->nama_tingkat }}
                    <small class="text-muted">({{ ucfirst($tingkat->jenis_penilaian) }})</small>
                </label>
            </div>
        </div>
        @endforeach
    </div>
    @error('tingkat_ids')
    <span class="text-danger d-block"><strong>{{ $message }}</strong></span>
    @enderror
</div>
