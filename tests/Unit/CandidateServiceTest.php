<?php

use App\Services\CandidateService;

const BIRTH_DATE_PLACE = 'City, 1990-01-01';

it('fetches data correctly', function () {
    $url = 'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7';
    $data = CandidateService::fetchData($url);

    expect($data)->toBeArray();
});

it('parses birth place correctly', function () {
    $birthPlace = CandidateService::parseBirthPlace(BIRTH_DATE_PLACE);

    expect($birthPlace)->toBe('City');
});

it('parses birth date correctly', function () {
    $birthDate = CandidateService::parseBirthDate(BIRTH_DATE_PLACE);

    expect($birthDate)->toBeInstanceOf(\Carbon\Carbon::class);
    expect($birthDate->toDateString())->toBe('1990-01-01');
});

it('counts age correctly', function () {
    $age = CandidateService::countAge(BIRTH_DATE_PLACE);

    expect($age)->toBe(\Carbon\Carbon::parse('1990-01-01')->age);
});

it('parses career correctly', function () {
    $careers = [
        'Job A (2010-2015)',
        'Job B (2015-2020)',
        'Job C (2020-Sekarang)',
    ];

    $parsedCareers = CandidateService::parseCareer($careers);

    expect($parsedCareers)->toBeArray();
    expect(count($parsedCareers))->toBe(3);
    expect($parsedCareers[0])->toBeInstanceOf(\App\Data\CareerData::class);
});

it('extracts career info correctly', function () {
    $careerInfo = CandidateService::extractCareerInfo('Job A (2010-2015)');

    expect($careerInfo)->toBeArray();
    expect(count($careerInfo))->toBe(3);
    expect($careerInfo[0])->toBe('Job A');
    expect($careerInfo[1])->toBe(2010);
    expect($careerInfo[2])->toBe(2015);
});
