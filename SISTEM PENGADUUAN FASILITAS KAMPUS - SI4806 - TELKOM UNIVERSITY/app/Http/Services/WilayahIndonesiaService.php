<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WilayahIndonesiaService
{
    /**
     * Base URL for API Wilayah Indonesia
     */
    private const BASE_URL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        return Cache::remember('provinces', 3600, function () {
            try {
                $response = Http::get(self::BASE_URL . '/provinces.json');
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Get regencies/cities by province ID
     */
    public function getRegencies($provinceId)
    {
        return Cache::remember("regencies_{$provinceId}", 3600, function () use ($provinceId) {
            try {
                $response = Http::get(self::BASE_URL . "/regencies/{$provinceId}.json");
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Get districts by regency ID
     */
    public function getDistricts($regencyId)
    {
        return Cache::remember("districts_{$regencyId}", 3600, function () use ($regencyId) {
            try {
                $response = Http::get(self::BASE_URL . "/districts/{$regencyId}.json");
                return $response->successful() ? $response->json() : [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Get province name by ID
     */
    public function getProvinceName($provinceId)
    {
        $provinces = $this->getProvinces();
        foreach ($provinces as $province) {
            if ($province['id'] == $provinceId) {
                return $province['name'];
            }
        }
        return null;
    }

    /**
     * Get regency name by ID
     */
    public function getRegencyName($regencyId)
    {
        $provinces = $this->getProvinces();
        foreach ($provinces as $province) {
            $regencies = $this->getRegencies($province['id']);
            foreach ($regencies as $regency) {
                if ($regency['id'] == $regencyId) {
                    return $regency['name'];
                }
            }
        }
        return null;
    }

    /**
     * Get district name by ID
     */
    public function getDistrictName($districtId)
    {
        $provinces = $this->getProvinces();
        foreach ($provinces as $province) {
            $regencies = $this->getRegencies($province['id']);
            foreach ($regencies as $regency) {
                $districts = $this->getDistricts($regency['id']);
                foreach ($districts as $district) {
                    if ($district['id'] == $districtId) {
                        return $district['name'];
                    }
                }
            }
        }
        return null;
    }
}
