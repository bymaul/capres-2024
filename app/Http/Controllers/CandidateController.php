<?php

namespace App\Http\Controllers;

use App\Enums\PositionStatus;
use App\Services\CandidateService;

class CandidateController extends Controller
{
    public function index()
    {
        $mock_url = 'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7';

        $candidates = CandidateService::fetchData($mock_url);

        $presidentialCandidates = CandidateService::processCandidates(
            $candidates['calon_presiden'],
            PositionStatus::PRESIDENT
        );

        $vicePresidentialCandidates = CandidateService::processCandidates(
            $candidates['calon_wakil_presiden'],
            PositionStatus::VICE_PRESIDENT
        );

        return view('index', [
            'presidentialCandidates' => $presidentialCandidates,
            'vicePresidentialCandidates' => $vicePresidentialCandidates
        ]);
    }
}
