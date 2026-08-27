<?php

namespace App\Services;

use App\Models\Pelatih;
use App\Models\Tingkat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PelatihTingkatAccessService
{
    /**
     * Pelatih tanpa tingkatan yang di-assign dianggap punya akses ke semua tingkatan.
     */
    public function hasRestrictedAccess(?Pelatih $pelatih): bool
    {
        if (!$pelatih) {
            return true;
        }

        return $pelatih->tingkats()->exists();
    }

    public function accessibleTingkatIds(?Pelatih $pelatih): Collection
    {
        if (!$pelatih) {
            return collect();
        }

        if (!$this->hasRestrictedAccess($pelatih)) {
            return Tingkat::pluck('id');
        }

        return $pelatih->tingkats()->pluck('tingkat.id');
    }

    public function accessibleTingkats(?Pelatih $pelatih): Collection
    {
        if (!$pelatih) {
            return collect();
        }

        if (!$this->hasRestrictedAccess($pelatih)) {
            return Tingkat::orderBy('urutan')->get();
        }

        return $pelatih->tingkats()->orderBy('urutan')->get();
    }

    public function canAccessTingkat(?Pelatih $pelatih, int $tingkatId): bool
    {
        if (!$pelatih) {
            return false;
        }

        if (!$this->hasRestrictedAccess($pelatih)) {
            return Tingkat::where('id', $tingkatId)->exists();
        }

        return $this->accessibleTingkatIds($pelatih)->contains($tingkatId);
    }

    public function filterTingkats(Collection $tingkats, ?Pelatih $pelatih): Collection
    {
        if (!$pelatih || !$this->hasRestrictedAccess($pelatih)) {
            return $tingkats->values();
        }

        $allowedIds = $this->accessibleTingkatIds($pelatih);

        return $tingkats->whereIn('id', $allowedIds->all())->values();
    }

    public function scopeUsersInAccessibleTingkat(Builder $query, ?Pelatih $pelatih): Builder
    {
        if (!$pelatih) {
            return $query->whereRaw('1 = 0');
        }

        if (!$this->hasRestrictedAccess($pelatih)) {
            return $query;
        }

        $allowedIds = $this->accessibleTingkatIds($pelatih);

        return $query->whereHas('siswaProfile', function (Builder $q) use ($allowedIds) {
            $q->whereIn('tingkat_id', $allowedIds);
        });
    }

    public function assertCanAccessTingkat(?Pelatih $pelatih, int $tingkatId): void
    {
        if (!$this->canAccessTingkat($pelatih, $tingkatId)) {
            abort(403, 'Anda tidak memiliki akses ke tingkat ini.');
        }
    }
}
