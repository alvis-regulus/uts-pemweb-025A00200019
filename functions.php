<?php
// functions.php — fungsi buatan sendiri

/**
 * Escape teks agar aman ditampilkan di HTML (mencegah XSS).
 */
function e($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

/**
 * Ubah angka menjadi format rupiah.
 */
function formatRupiah(int $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Saring menu berdasarkan kategori dan kata kunci pencarian.
 */
function filterMenu(array $menu, string $kategori = 'semua', string $kata = ''): array
{
    $hasil = array_filter($menu, function ($item) use ($kategori, $kata) {
        $cocokKategori = ($kategori === 'semua')
            || (strtolower($item['kategori']) === strtolower($kategori));

        $cocokKata = ($kata === '')
            || (stripos($item['nama'], $kata) !== false);

        return $cocokKategori && $cocokKata;
    });

    return array_values($hasil);
}

/**
 * Urutkan menu: nama (A-Z), termurah, atau termahal.
 */
function urutkanMenu(array $menu, string $urut): array
{
    switch ($urut) {
        case 'termurah':
            usort($menu, fn($a, $b) => $a['harga'] <=> $b['harga']);
            break;
        case 'termahal':
            usort($menu, fn($a, $b) => $b['harga'] <=> $a['harga']);
            break;
        case 'nama':
            usort($menu, fn($a, $b) => strcasecmp($a['nama'], $b['nama']));
            break;
        default:
            // urutan bawaan dari data.php
            break;
    }

    return $menu;
}

/**
 * Hitung statistik sederhana dari daftar menu.
 */
function statistikMenu(array $menu): array
{
    if (count($menu) === 0) {
        return ['jumlah' => 0, 'termurah' => null, 'termahal' => null, 'rata' => 0];
    }

    $harga = array_column($menu, 'harga');

    return [
        'jumlah'   => count($menu),
        'termurah' => min($harga),
        'termahal' => max($harga),
        'rata'     => (int) round(array_sum($harga) / count($harga)),
    ];
}

/**
 * Daftar kategori unik dari data menu.
 */
function daftarKategori(array $menu): array
{
    return array_values(array_unique(array_column($menu, 'kategori')));
}

/**
 * Ubah teks menjadi slug (untuk URL), contoh: "Pecel Lele" -> "pecel-lele".
 */
function buatSlug(string $teks): string
{
    $slug = strtolower(trim($teks));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Tampilkan gambar menu. Jika file gambar tidak ada, pakai emoji placeholder.
 */
function tampilGambarMenu(array $item): string
{
    $berkas = $item['gambar'] ?? '';
    $path   = __DIR__ . '/assets/' . $berkas;

    if ($berkas !== '' && file_exists($path)) {
        return '<img src="assets/' . e($berkas) . '" alt="' . e($item['nama']) . '" loading="lazy">';
    }

    // Placeholder emoji berdasarkan kategori
    $emoji = (strtolower($item['kategori'] ?? '') === 'minuman') ? '🥤' : '🍽️';
    return '<div class="placeholder-img">' . $emoji . '</div>';
}

/**
 * Cetak bagian atas halaman (head + navigasi).
 */
function renderHeader(string $judul, string $halamanAktif): void
{
    $nav = [
        'beranda'  => ['index.php', 'Beranda'],
        'katalog'  => ['katalog.php', 'Menu'],
    ];

    echo '<!DOCTYPE html>' . "\n";
    echo '<html lang="id">' . "\n<head>\n";
    echo '  <meta charset="UTF-8">' . "\n";
    echo '  <meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    echo '  <title>' . e($judul) . '</title>' . "\n";
    echo '  <link rel="stylesheet" href="style.css">' . "\n";
    echo "</head>\n<body>\n";
    echo '<header class="site-header"><div class="container">' . "\n";
    echo '  <a class="brand" href="index.php">🔥 Warung Pecel Lele Mas Ajik</a>' . "\n";
    echo '  <nav>';
    foreach ($nav as $kunci => [$url, $label]) {
        $kelas = ($kunci === $halamanAktif) ? ' class="aktif"' : '';
        echo '<a href="' . $url . '"' . $kelas . '>' . e($label) . '</a>';
    }
    echo "</nav>\n</div></header>\n<main class=\"container\">\n";
}

/**
 * Cetak bagian bawah halaman.
 */
function renderFooter(string $nama): void
{
    echo "</main>\n";
    echo '<footer class="site-footer"><div class="container">';
    echo '&copy; ' . date('Y') . ' ' . e($nama) . ' &mdash; Tugas UTS Pemrograman Web';
    echo "</div></footer>\n";
    echo "</body>\n</html>\n";
}
