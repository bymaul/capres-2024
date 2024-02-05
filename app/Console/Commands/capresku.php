<?php

namespace App\Console\Commands;

use App\Enums\PositionStatus;
use App\Services\CandidateService;
use Illuminate\Console\Command;

class capresku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capresku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the candidates for president and vice president in 2024 election.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mock_url = 'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7';

        $this->info('The candidates for president and vice president in 2024 election are:');

        $candidates = CandidateService::fetchData($mock_url);

        $presidentialCandidates = CandidateService::processCandidates(
            $candidates['calon_presiden'],
            PositionStatus::PRESIDENT

        );

        $vicePresidentialCandidates = CandidateService::processCandidates(
            $candidates['calon_wakil_presiden'],
            PositionStatus::VICE_PRESIDENT
        );

        $this->info('Presidential Candidates:');
        foreach ($presidentialCandidates as $candidate) {
            $this->info($candidate->nomor_urut . '. ' . $candidate->nama_lengkap);
        }

        $this->info('Vice Presidential Candidates:');
        foreach ($vicePresidentialCandidates as $candidate) {
            $this->info($candidate->nomor_urut . '. ' . $candidate->nama_lengkap);
        }
    }
}
