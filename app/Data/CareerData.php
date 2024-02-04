<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CareerData extends Data
{
    public function __construct(
        public string $jabatan,
        public int $tahun_mulai,
        public ?int $tahun_selesai,
    ) {
    }
}
