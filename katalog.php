<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

// Validasi input dari URL (GET)
$daftarKategori = daftarKategori($menu);
$opsiUrut = [
    'bawaan'   => 'Urutan bawaan',
    'nama'     => 'Nama (A-Z)',
    'termurah' => 'Harga termurah',
    'termahal' => 'Harga termahal',
];

$kategori = $_GET['kategori'] ?? 'semua';
if ($kategori !== 'semua' && !in_array($kategori, $daftarKategori, true)) {
    $kategori = 'semua';
}

$kata = trim($_GET['q'] ?? '');
$urut = $_GET['urut'] ?? 'bawaan';
if (!array_key_exists($urut, $opsiUrut)) {
    $urut = 'bawaan';
}

// Proses data
$hasil     = urutkanMenu(filterMenu($menu, $kategori, $kata), $urut);
$statistik = statistikMenu($hasil);

renderHeader('Menu | Warung Pecel Lele Mas Ajik', 'katalog');
?>

<section class="judul-halaman">
    <h1>Menu Warung Pecel Lele Mas Ajik</h1>
    <p>Pilih makanan dan minuman favoritmu hari ini. Klik gambar untuk memesan.</p>
</section>

<form class="filter" method="get" action="katalog.php">
    <label>
        Cari menu
        <input type="search" name="q" value="<?= e($kata) ?>" placeholder="contoh: ayam, lele, susu">
    </label>

    <label>
        Kategori
        <select name="kategori">
            <option value="semua">Semua</option>
            <?php foreach ($daftarKategori as $kat): ?>
                <option value="<?= e($kat) ?>" <?= $kat === $kategori ? 'selected' : '' ?>>
                    <?= e($kat) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        Urutkan
        <select name="urut">
            <?php foreach ($opsiUrut as $nilai => $label): ?>
                <option value="<?= e($nilai) ?>" <?= $nilai === $urut ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <div class="aksi">
        <button type="submit" class="tombol">Terapkan</button>
        <a class="tombol sekunder" href="katalog.php">Reset</a>
    </div>
</form>

<?php if ($statistik['jumlah'] > 0): ?>
    <section class="statistik">
        <div><span><?= $statistik['jumlah'] ?></span> menu</div>
        <div><span><?= formatRupiah($statistik['termurah']) ?></span> termurah</div>
        <div><span><?= formatRupiah($statistik['termahal']) ?></span> termahal</div>
        <div><span><?= formatRupiah($statistik['rata']) ?></span> rata-rata</div>
    </section>

    <section class="grid">
        <?php foreach ($hasil as $item): ?>
            <article class="menu-card">
                <div class="gambar"
                     data-pesan
                     data-id="<?= (int)$item['id'] ?>"
                     data-nama="<?= e($item['nama']) ?>"
                     data-harga="<?= (int)$item['harga'] ?>"
                     data-img="<?= e($item['gambar'] ?? '') ?>">
                    <?php if (!empty($item['terlaris'])): ?>
                        <span class="badge-terlaris">🔥 Terlaris</span>
                    <?php endif; ?>
                    <?= tampilGambarMenu($item) ?>
                </div>
                <div class="isi">
                    <h3 class="nama"><?= e($item['nama']) ?></h3>
                    <div class="kategori"><?= e($item['kategori']) ?></div>
                    <div class="harga"><?= formatRupiah($item['harga']) ?></div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php else: ?>
    <div class="kosong">
        <p>😕 Menu tidak ditemukan untuk pencarian
            <strong>"<?= e($kata) ?>"</strong>. Coba kata kunci lain.</p>
    </div>
<?php endif; ?>


<?php
renderFooter('Warung Pecel Lele Mas Ajik');
