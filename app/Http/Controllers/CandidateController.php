<?php

namespace App\Http\Controllers;

use App\Services\CandidateService;
use GuzzleHttp\Client;

class CandidateController extends Controller
{
    public function index()
    {
        return CandidateService::fetchData();
    }
}
