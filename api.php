<?php

/**
 * api.php
 *
 * Berisi seluruh logika REST Client: memanggil Open-Meteo Geocoding API
 * dan Forecast API dengan PHP cURL, lalu merapikan hasilnya untuk ditampilkan.
 */

const GEOCODING_API_URL = 'https://geocoding-api.open-meteo.com/v1/search';
const FORECAST_API_URL  = 'https://api.open-meteo.com/v1/forecast';
const CONNECT_TIMEOUT   = 5;
const REQUEST_TIMEOUT   = 10;
const MAX_CITY_LENGTH   = 100;

// Data cuaca saat ini yang diminta dari Forecast API.
const WEATHER_FIELDS = [
    'temperature_2m',
    'relative_humidity_2m',
    'wind_speed_10m',
    'weather_code',
];

/**
 * Membuat hasil gagal dengan format yang seragam.
 */
function failure(string $message): array
{
    return ['success' => false, 'message' => $message];
}

/**
 * Mengirim HTTP GET ke sebuah API dengan cURL, lalu mengubah JSON menjadi array.
 */
function fetchJson(string $baseUrl, array $params): array
{
    $curl = curl_init($baseUrl . '?' . http_build_query($params));

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => CONNECT_TIMEOUT,
        CURLOPT_TIMEOUT        => REQUEST_TIMEOUT,
        CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    ]);

    $body       = curl_exec($curl);
    $curlError  = curl_error($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

    // Pada PHP 8+, handle cURL dibebaskan otomatis sehingga curl_close() tidak diperlukan.

    if ($body === false) {
        error_log('cURL error: ' . $curlError);
        return failure('Tidak dapat terhubung ke layanan cuaca. Periksa koneksi internet lalu coba lagi.');
    }

    if ($statusCode !== 200) {
        return failure("Layanan cuaca mengembalikan kode {$statusCode}. Coba lagi nanti.");
    }

    $data = json_decode($body, true);

    if (!is_array($data)) {
        return failure('Respons dari layanan cuaca kosong atau tidak valid.');
    }

    return ['success' => true, 'data' => $data];
}

/**
 * Mencari koordinat sebuah kota melalui Geocoding API.
 */
function searchLocation(string $city): array
{
    $response = fetchJson(GEOCODING_API_URL, [
        'name'     => $city,
        'count'    => 1,
        'language' => 'id',
        'format'   => 'json',
    ]);

    if (!$response['success']) {
        return $response;
    }

    // Jika kota tidak ditemukan, API tidak mengirim kunci "results".
    $place = $response['data']['results'][0] ?? null;

    if ($place === null) {
        return failure("Kota \"{$city}\" tidak ditemukan. Periksa penulisan lalu coba lagi.");
    }

    $isValid = isset($place['name'], $place['latitude'], $place['longitude'])
        && is_string($place['name'])
        && is_numeric($place['latitude'])
        && is_numeric($place['longitude']);

    if (!$isValid) {
        return failure('Data lokasi dari layanan cuaca tidak lengkap.');
    }

    return [
        'success'   => true,
        'name'      => $place['name'],
        'country'   => is_string($place['country'] ?? null) ? $place['country'] : '',
        'latitude'  => (float) $place['latitude'],
        'longitude' => (float) $place['longitude'],
    ];
}

/**
 * Memastikan semua data cuaca yang dibutuhkan ada dan berupa angka.
 */
function hasWeatherFields($current): bool
{
    if (!is_array($current)) {
        return false;
    }

    foreach (WEATHER_FIELDS as $field) {
        if (!is_numeric($current[$field] ?? null)) {
            return false;
        }
    }

    return true;
}

/**
 * Mengambil cuaca saat ini berdasarkan koordinat melalui Forecast API.
 */
function fetchCurrentWeather(float $latitude, float $longitude): array
{
    $response = fetchJson(FORECAST_API_URL, [
        'latitude'  => $latitude,
        'longitude' => $longitude,
        'current'   => implode(',', WEATHER_FIELDS),
        'timezone'  => 'auto',
    ]);

    if (!$response['success']) {
        return $response;
    }

    $current = $response['data']['current'] ?? null;

    if (!hasWeatherFields($current)) {
        return failure('Data cuaca dari layanan tidak lengkap.');
    }

    return [
        'success'     => true,
        'temperature' => (float) $current['temperature_2m'],
        'humidity'    => (int) round((float) $current['relative_humidity_2m']),
        'windSpeed'   => (float) $current['wind_speed_10m'],
        'weatherCode' => (int) $current['weather_code'],
    ];
}

/**
 * Mengubah kode cuaca WMO dari Open-Meteo menjadi teks dan ikon.
 */
function getWeatherCondition(int $code): array
{
    [$label, $icon] = match ($code) {
        0                       => ['Cerah', '☀️'],
        1                       => ['Sebagian cerah', '🌤️'],
        2                       => ['Berawan sebagian', '⛅'],
        3                       => ['Mendung', '☁️'],
        45, 48                  => ['Berkabut', '🌫️'],
        51, 53, 55, 56, 57      => ['Gerimis', '🌦️'],
        61, 63, 65, 66, 67      => ['Hujan', '🌧️'],
        71, 73, 75, 77, 85, 86  => ['Salju', '❄️'],
        80, 81, 82              => ['Hujan deras', '🌧️'],
        95, 96, 99              => ['Badai petir', '⛈️'],
        default                 => ['Kondisi tidak diketahui', '🌡️'],
    };

    return ['label' => $label, 'icon' => $icon];
}

/**
 * Alur utama: nama kota -> koordinat -> cuaca saat ini.
 */
function getWeatherByCity(string $city): array
{
    $city = trim($city);

    if ($city === '') {
        return failure('Masukkan nama kota terlebih dahulu.');
    }

    if (strlen($city) > MAX_CITY_LENGTH) {
        return failure('Nama kota terlalu panjang.');
    }

    $location = searchLocation($city);

    if (!$location['success']) {
        return $location;
    }

    $weather = fetchCurrentWeather($location['latitude'], $location['longitude']);

    if (!$weather['success']) {
        return $weather;
    }

    return [
        'success'   => true,
        'location'  => $location,
        'weather'   => $weather,
        'condition' => getWeatherCondition($weather['weatherCode']),
    ];
}
