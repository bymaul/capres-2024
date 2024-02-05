<?php

namespace App\Services;

use App\Data\CandidateData;
use App\Data\CareerData;
use App\Enums\PositionStatus;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CandidateService
{
    public static function fetchData(string $url): array
    {
        $client = new Client();

        try {
            $response = $client->get($url);

            $candidates = json_decode($response->getBody(), true);

            usort($candidates['calon_presiden'], function ($a, $b) {
                return $a['nomor_urut'] - $b['nomor_urut'];
            });

            usort($candidates['calon_wakil_presiden'], function ($a, $b) {
                return $a['nomor_urut'] - $b['nomor_urut'];
            });

            return $candidates;
        } catch (\Exception $e) {
            return [
                'message' => $e->getMessage(),
                'calon_presiden' => [],
                'calon_wakil_presiden' => [],
            ];
        }
    }

    public static function processCandidates(array $candidatesData, PositionStatus $positionStatus): array
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
