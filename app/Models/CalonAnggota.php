<?php

namespace App\Models;

use App\Http\Controllers\StrukturController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CalonAnggota extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'calon_anggotas';

    protected $fillable = [
        'nomor_pendaftaran',
        'nama_lengkap',
        'alamat',
        'nomor_hp',
        'nik',
        'foto',
        'jenis_kelamin',
        'organisasi',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejection_reason',
        'inactive_reason',
        'inactivated_at',
        'inactivated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'inactivated_at' => 'datetime',
        ];
    }

    /**
     * Admin yang menyetujui pendaftaran ini.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Admin yang menonaktifkan anggota ini.
     */
    public function inactivator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inactivated_by');
    }

    /**
     * Baris pengurus yang otomatis dibuat saat pendaftaran ini disetujui.
     */
    public function pengurus(): HasOne
    {
        return $this->hasOne(Pengurus::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Scope: hanya anggota aktif (disetujui, bukan nonaktif).
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Disetujui / Anggota Aktif',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_INACTIVE => 'Nonaktif',
            default => 'Menunggu Persetujuan',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-700',
            self::STATUS_INACTIVE => 'bg-gray-100 text-gray-700',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }

    public function organisasiLabel(): string
    {
        return match ($this->organisasi) {
            'ipnu' => 'IPNU',
            'ippnu' => 'IPPNU',
            default => StrukturController::KATEGORI[$this->organisasi]['label'] ?? $this->organisasi,
        };
    }

    /**
     * NIK hanya ditampilkan 4 digit terakhir untuk keamanan data.
     */
    public function maskedNik(): string
    {
        return '****-****-****-'.substr((string) $this->nik, 12, 4);
    }

    /**
     * Slug kategori struktur yang setara dengan organisasi yang dipilih.
     *
     * Data lama masih menyimpan nilai gabungan 'ipnu-ippnu' (sebelum
     * normalisasi jenis kelamin diterapkan), sehingga dipetakan ulang
     * ke ipnu/ippnu berdasarkan jenis kelamin pendaftar.
     */
    public function kategoriStruktur(): string
    {
        if ($this->organisasi === 'ipnu-ippnu') {
            return $this->jenis_kelamin === 'P' ? 'ippnu' : 'ipnu';
        }

        return $this->organisasi;
    }

    /**
     * Atribut baris pengurus hasil dari persetujuan pendaftaran ini.
     *
     * Jabatan masih "Anggota" dan dapat diubah admin lewat menu Pengurus.
     * Foto tidak disalin karena keduanya sudah berada di disk public
     * dengan path yang sama.
     *
     * @return array<string, mixed>
     */
    public function pengurusFromPendaftaran(): array
    {
        $kategori = $this->kategoriStruktur();

        return [
            'nama' => $this->nama_lengkap,
            'jabatan' => 'Anggota',
            'kategori' => $kategori,
            'foto' => $this->foto,
            'urutan' => (int) Pengurus::where('kategori', $kategori)->max('urutan') + 1,
        ];
    }
}
