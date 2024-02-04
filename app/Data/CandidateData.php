<?php

namespace App\Data;

use App\Enums\PositionStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CandidateData extends Data
{
    public function __construct(
        public int $nomor_urut,
        public PositionStatus $posisi,
        public string $nama_lengkap,
        public string $tempat_lahir,
        public Carbon $tanggal_lahir,
        public int $usia,
        /** @var CareerData[] $karir */
        public array $karir
    ) {
    }
}
