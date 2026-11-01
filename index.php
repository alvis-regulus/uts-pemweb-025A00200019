<?php
require_once __DIR__ . '/functions.php';

// profile warung

$nama_warung   = "Warung Pecel Lele Mas Ajik";
$pemilik       = "Mas Ajik";
$jam_buka      = "16.00 – 22.00 WIB";
$menu_andalan  = ["Pecel Lele", "Ayam Goreng", "Susu Madu Jahe"];

renderHeader('Beranda | ' . $nama_warung, 'beranda');
?>

<section class="judul-halaman">
    <h1><?= e($nama_warung) ?></h1>
    <p>Warung pecel lele dengan cita rasa khas dan harga bersahabat.</p>
</section>

<div class="profil-card">
    <div class="info-item">
        <strong>Pemilik</strong>
        <?= e($pemilik) ?>
    </div>

    <div class="info-item">
        <strong>Jam Operasional</strong>
        <?= e($jam_buka) ?>
    </div>

    <div class="info-item">
        <strong>Menu Andalan</strong>
        <ul>
            <?php foreach ($menu_andalan as $m): ?>
                <li><?= e($m) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<section class="cta">
    <h2>Siap memesan?</h2>
    <a class="tombol" href="katalog.php">Lihat Menu Lengkap →</a>
</section>

<?php
renderFooter($nama_warung);