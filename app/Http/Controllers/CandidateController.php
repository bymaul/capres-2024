<?php

namespace App\Http\Controllers;

use App\Services\CandidateService;

class CandidateController extends Controller
{
    public function index()
    {
        return CandidateService::fetchData();
    }
}
