<?php

namespace App\Services;

use App\Data\CandidateData;
use App\Data\CareerData;
use App\Enums\PositionStatus;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CandidateService
{
    public static function fetchData()
    {
        $url = 'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7';

        $client = new Client();

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            $presidentialCandidates = [];
            foreach ($data['calon_presiden'] as $presidentialCandidateData) {
                $presidentialCandidates[] = new CandidateData(
                    $presidentialCandidateData['nomor_urut'],
                    PositionStatus::PRESIDENT,
                    $presidentialCandidateData['nama_lengkap'],
                    self::parseBirthPlace($presidentialCandidateData['tempat_tanggal_lahir']),
                    self::parseBirthDate($presidentialCandidateData['tempat_tanggal_lahir']),
                    self::countAge($presidentialCandidateData['tempat_tanggal_lahir']),
                    self::parseCareer($presidentialCandidateData['karir'])
                );
            }

            $vicePresidentialCandidates = [];
            foreach ($data['calon_wakil_presiden'] as $vicePresidentialCandidateData) {
                $vicePresidentialCandidates[] = new CandidateData(
                    $vicePresidentialCandidateData['nomor_urut'],
                    PositionStatus::VICE_PRESIDENT,
                    $vicePresidentialCandidateData['nama_lengkap'],
                    self::parseBirthPlace($vicePresidentialCandidateData['tempat_tanggal_lahir']),
                    self::parseBirthDate($vicePresidentialCandidateData['tempat_tanggal_lahir']),
                    self::countAge($vicePresidentialCandidateData['tempat_tanggal_lahir']),
                    self::parseCareer($vicePresidentialCandidateData['karir'])
                );
            }

            return response()->json([
                'calon_presiden' => $presidentialCandidates,
                'calon_wakil_presiden' => $vicePresidentialCandidates,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function parseBirthPlace($data): string
    {
        $birthPlace = explode(', ', $data);
        return $birthPlace[0];
    }

    public static function parseBirthDate($data): string
    {

        $birthDate = explode(', ', $data);
        return Carbon::parseFromLocale($birthDate[1], 'id');
    }

    public static function countAge($data): int
    {
        $data = self::parseBirthDate($data);

        return Carbon::parseFromLocale($data, 'id')->age;
    }

    public static function parseCareer($careers): array
    {
        $careerData = [];

        foreach ($careers as $career) {
            $careerInfo = self::extractCareerInfo($career);

            if ($careerInfo) {
                [$careerName, $yearStart, $yearEnd] = $careerInfo;

                $careerData[] = new CareerData(
                    $careerName,
                    $yearStart,
                    $yearEnd
                );
            }
        }

        return $careerData;
    }

    private static function extractCareerInfo($career): ?array
    {
        $pattern = '/^(.*?) \((\d{4})-(\d{4})\)$/';
        $matches = [];

        if (preg_match($pattern, $career, $matches)) {
            return [
                $matches[1],
                $matches[2],
                $matches[3]
            ];
        }

        return null;
    }
}
