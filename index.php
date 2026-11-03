<?php

/**
 * index.php
 *
 * Halaman utama: form pencarian kota dan tampilan hasil cuaca.
 * Seluruh pemanggilan API dilakukan oleh PHP (api.php), bukan oleh JavaScript.
 */

require_once __DIR__ . '/api.php';

/**
 * Mengamankan teks sebelum dicetak ke HTML (mencegah XSS).
 */
function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

$isSearching = isset($_GET['city']);
$city        = ($isSearching && is_string($_GET['city'])) ? trim($_GET['city']) : '';
$result      = $isSearching ? getWeatherByCity($city) : null;
$display     = null;

// Siapkan teks yang siap tampil jika pencarian berhasil.
if ($result !== null && $result['success']) {
    $location = $result['location'];
    $weather  = $result['weather'];

    $display = [
        'place'       => implode(', ', array_filter([$location['name'], $location['country']])),
        'temperature' => number_format($weather['temperature'], 1, ',', '.'),
        'humidity'    => $weather['humidity'] . '%',
        'wind'        => number_format($weather['windSpeed'], 1, ',', '.') . ' km/jam',
        'icon'        => $result['condition']['icon'],
        'label'       => $result['condition']['label'],
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cuaca Kota</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="page">
        <header class="page-header">
            <h1>Cuaca Kota</h1>
            <p>Ketik nama kota untuk melihat cuaca saat ini.</p>
        </header>

        <form class="search" method="get" action="index.php" role="search">
            <label class="visually-hidden" for="city">Nama kota</label>
            <input
                type="text"
                id="city"
                name="city"
                value="<?= e($city) ?>"
                placeholder="Contoh: Semarang"
                maxlength="100"
                autocomplete="off"
                autofocus
            >
            <button type="submit">Cari</button>
        </form>

        <?php if ($result !== null && !$result['success']): ?>
            <p class="error-message" role="alert"><?= e($result['message']) ?></p>
        <?php elseif ($display !== null): ?>
            <section class="weather" aria-label="Cuaca saat ini">
                <h2 class="weather-place"><?= e($display['place']) ?></h2>
                <p class="weather-temperature">
                    <?= e($display['temperature']) ?><span class="weather-unit">°C</span>
                </p>
                <p class="weather-condition">
                    <span class="weather-icon" aria-hidden="true"><?= e($display['icon']) ?></span>
                    <?= e($display['label']) ?>
                </p>
                <dl class="weather-details">
                    <div class="weather-detail">
                        <dt>Kelembapan</dt>
                        <dd><?= e($display['humidity']) ?></dd>
                    </div>
                    <div class="weather-detail">
                        <dt>Kecepatan angin</dt>
                        <dd><?= e($display['wind']) ?></dd>
                    </div>
                </dl>
            </section>
        <?php endif; ?>

        <footer class="page-footer">
            <p>
                Data cuaca oleh
                <a href="https://open-meteo.com/" target="_blank" rel="noopener">Open-Meteo.com</a>.
            </p>
        </footer>
    </main>
</body>
</html>
