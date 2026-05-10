<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display dashboard utama
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * API: Get data Rawat Jalan
     * Nantinya data akan diambil dari API eksternal
     */
    public function getRawatJalanData(Request $request)
    {
        $periodType = $request->get('period_type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Data dummy - nantinya diganti dengan data dari API
        $dummyData = $this->generateDummyRawatJalanData($periodType, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $dummyData,
            'meta' => [
                'period_type' => $periodType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * API: Get data Rawat Inap
     */
    public function getRawatInapData(Request $request)
    {
        $periodType = $request->get('period_type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $dummyData = $this->generateDummyRawatInapData($periodType, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $dummyData,
            'meta' => [
                'period_type' => $periodType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * API: Get data Total (Rawat Jalan + Rawat Inap summary)
     */
    public function getTotalData(Request $request)
    {
        $periodType = $request->get('period_type', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $rawatJalan = $this->generateDummyRawatJalanData($periodType, $startDate, $endDate);
        $rawatInap = $this->generateDummyRawatInapData($periodType, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => [
                'rawat_jalan' => $rawatJalan,
                'rawat_inap' => $rawatInap
            ],
            'meta' => [
                'period_type' => $periodType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'timestamp' => now()->toIso8601String()
            ]
        ]);
    }

    /**
     * Generate dummy data untuk Rawat Jalan
     * Data FIX (tidak random) - akan diganti dengan data dari API nantinya
     */
    private function generateDummyRawatJalanData($periodType, $startDate = null, $endDate = null)
    {
        $poliList = ["Poli Umum", "Poli Anak", "Poli Bedah", "Poli Jantung", "Poli Saraf", "Poli Mata", "Poli THT"];
        
        // Data base untuk periode (bulanan)
        $baseData = [
            "Poli Umum" => 125,
            "Poli Anak" => 310,
            "Poli Bedah" => 340,
            "Poli Jantung" => 165,
            "Poli Saraf" => 285,
            "Poli Mata" => 280,
            "Poli THT" => 3900,
        ];

        if ($periodType === 'daily') {
            // Data harian (tetap, tidak random)
            $dailyData = [
                "Poli Umum" => 18,
                "Poli Anak" => 42,
                "Poli Bedah" => 35,
                "Poli Jantung" => 15,
                "Poli Saraf" => 28,
                "Poli Mata" => 22,
                "Poli THT" => 14,
            ];
            
            $data = [];
            foreach ($poliList as $poli) {
                $data[] = [
                    'label' => $poli,
                    'value' => $dailyData[$poli]
                ];
            }
            
            // Sort by value descending
            usort($data, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            
            return [
                'labels' => array_column($data, 'label'),
                'values' => array_column($data, 'value'),
                'total' => array_sum(array_column($data, 'value')),
                'target' => 1600
            ];
        }

        // Data periode (disesuaikan dengan rentang tanggal)
        $multiplier = 1;
        if ($startDate && $endDate) {
            $days = ceil((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
            $multiplier = min(max($days / 30, 0.5), 2);
        }

        $data = [];
        foreach ($poliList as $poli) {
            $value = floor($baseData[$poli] * $multiplier);
            $data[] = [
                'label' => $poli,
                'value' => $value
            ];
        }

        // Sort by value descending
        usort($data, function($a, $b) {
            return $b['value'] - $a['value'];
        });

        return [
            'labels' => array_column($data, 'label'),
            'values' => array_column($data, 'value'),
            'total' => array_sum(array_column($data, 'value')),
            'target' => 1600
        ];
    }

    /**
     * Generate dummy data untuk Rawat Inap
     */
    private function generateDummyRawatInapData($periodType, $startDate = null, $endDate = null)
    {
        $ruangList = ["Drupadi", "Gatot Kaca", "Yudistira", "Srikandi", "Abimanyu", "Sadewa", "Arimbi"];
        
        // Data base untuk periode (bulanan)
        $baseData = [
            "Drupadi" => 245,
            "Gatot Kaca" => 270,
            "Yudistira" => 320,
            "Srikandi" => 295,
            "Abimanyu" => 185,
            "Sadewa" => 160,
            "Arimbi" => 210,
        ];

        if ($periodType === 'daily') {
            // Data harian (tetap, tidak random)
            $dailyData = [
                "Drupadi" => 32,
                "Gatot Kaca" => 28,
                "Yudistira" => 38,
                "Srikandi" => 34,
                "Abimanyu" => 22,
                "Sadewa" => 18,
                "Arimbi" => 25,
            ];
            
            $data = [];
            foreach ($ruangList as $ruang) {
                $data[] = [
                    'label' => $ruang,
                    'value' => $dailyData[$ruang]
                ];
            }
            
            usort($data, function($a, $b) {
                return $b['value'] - $a['value'];
            });
            
            return [
                'labels' => array_column($data, 'label'),
                'values' => array_column($data, 'value'),
                'total' => array_sum(array_column($data, 'value')),
                'target' => 11200
            ];
        }

        // Data periode
        $multiplier = 1;
        if ($startDate && $endDate) {
            $days = ceil((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24));
            $multiplier = min(max($days / 30, 0.5), 2);
        }

        $data = [];
        foreach ($ruangList as $ruang) {
            $value = floor($baseData[$ruang] * $multiplier);
            $data[] = [
                'label' => $ruang,
                'value' => $value
            ];
        }

        usort($data, function($a, $b) {
            return $b['value'] - $a['value'];
        });

        return [
            'labels' => array_column($data, 'label'),
            'values' => array_column($data, 'value'),
            'total' => array_sum(array_column($data, 'value')),
            'target' => 11200
        ];
    }
}