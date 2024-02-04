<?php

namespace App\Services;

use App\Data\CandidateData;
use App\Data\CareerData;
use App\Enums\PositionStatus;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CandidateService
{
    const MOCKI_API_URL = 'https://mocki.io/v1/92a1f2ef-bef2-4f84-8f06-1965f0fca1a7';

    public static function fetchData()
    {
        $client = new Client();

        try {
            $response = $client->get(self::MOCKI_API_URL);
            $data = json_decode($response->getBody(), true);

            $presidentialCandidates = self::processCandidates(
                $data['calon_presiden'],
                PositionStatus::PRESIDENT
            );
            $vicePresidentialCandidates = self::processCandidates(
                $data['calon_wakil_presiden'],
                PositionStatus::VICE_PRESIDENT
            );

            return response()->json([
                'calon_presiden' => $presidentialCandidates,
                'calon_wakil_presiden' => $vicePresidentialCandidates,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private static function processCandidates(array $candidatesData, PositionStatus $positionStatus): array
    {
        $candidates = [];

        foreach ($candidatesData as $candidateData) {
            $candidates[] = self::createCandidate($candidateData, $positionStatus);
        }

        return $candidates;
    }

    private static function createCandidate(array $candidateData, PositionStatus $positionStatus): CandidateData
    {
        return new CandidateData(
            $candidateData['nomor_urut'],
            $positionStatus,
            $candidateData['nama_lengkap'],
            self::parseBirthPlace($candidateData['tempat_tanggal_lahir']),
            self::parseBirthDate($candidateData['tempat_tanggal_lahir']),
            self::countAge($candidateData['tempat_tanggal_lahir']),
            self::parseCareer($candidateData['karir'])
        );
    }

    public static function parseBirthPlace(string $data): string
    {
        return explode(', ', $data)[0];
    }

    public static function parseBirthDate(string $data): Carbon
    {
        return Carbon::parseFromLocale(explode(', ', $data)[1], 'id');
    }

    public static function countAge(string $data): int
    {
        return Carbon::parseFromLocale(self::parseBirthDate($data), 'id')->age;
    }

    public static function parseCareer(array $careers): array
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

    public static function extractCareerInfo(string $career): ?array
    {
        $pattern = '/^(.*?)\s*\((\d{4})-(\d{4}|\S+)\)$/';
        $matches = [];

        if (preg_match($pattern, $career, $matches)) {
            return [
                $matches[1],
                (int) $matches[2],
                $matches[3] === 'Sekarang' ? null : (int) $matches[3],
            ];
        }

        return null;
    }
}
