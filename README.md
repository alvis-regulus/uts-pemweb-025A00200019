# Warung Pecel Lele Mas Ajik

Website katalog menu statis untuk warung pecel lele. Dibangun dengan PHP native (tanpa framework), HTML, dan CSS. Data menu, tampilan, dan pemrosesan dipisah ke file sendiri.

## 1. identitas mahasiswa: Dava Maalika Fakhriza, NIM: 025A00200019, Kelas: A2 - 3B
            
## 2. Deskripsi singkat tema

Situs ini memperkenalkan **Warung Pecel Lele Mas Ajik**, warung makan malam dengan nuansa putih dan hijau. Halaman beranda menampilkan spanduk, pemilik (Mas Ajik), jam buka (16.00–22.00 WIB), dan menu andalan. Halaman menu menampilkan 16 hidangan dan minuman beserta harga, lengkap dengan pencarian, saringan kategori, pengurutan, statistik harga, dan tanda menu terlaris.

## 3. Cara menjalankan di localhost

Tidak perlu database. Yang diperlukan hanya PHP (disarankan 8.0 ke atas, karena kode memakai `fn()` dan tipe parameter).

Letakkan berkas seperti ini. Gambar harus berada di folder `assets/`, karena `tampilGambarMenu()` mencari file di `__DIR__ . '/assets/'`.

```text
pecel-lele/
├── index.php
├── katalog.php
├── data.php
├── functions.php
├── style.css
└── assets/
    ├── pecel lele spanduk.jpg
    ├── ayam-goreng-dada.jpg
    ├── ayam-goreng-paha.jpg
    ├── pecel-lele.jpg
    ├── ati-ampela.jpg
    ├── tempe tahu goreng.jpg
    ├── nasi putih.jpg
    ├── bandeng-goreng.jpg
    ├── nila-goreng.jpg
    ├── sayap-ayam.jpg
    ├── kol-goreng.jpg
    ├── es-teh.jpg
    ├── es-sirup.jpg
    ├── soda-gembira.jpg
    ├── es-jeruk.jpg
    ├── susu-coklat-vanila.jpg
    └── susu-madu-jahe.jpg
```

Nama file gambar harus sama persis dengan nilai `gambar` di `data.php`, termasuk spasi (`nasi putih.jpg`, `tempe tahu goreng.jpg`, `pecel lele spanduk.jpg`).

### Bukak Laragon

1. Salin folder proyek ke `C:\laragon\www\` atau `D:\laragon\www\`. 
2. Start Laragon (Apache + PHP).
3. Buka [http://localhost/uts-pemweb-025A00200019/].

## 4. Daftar fitur, file, dan fungsi

| Fitur | File | Fungsi yang menangani |
|---|---|---|
| Halaman beranda: judul, spanduk, pemilik, jam buka, menu andalan | `index.php` | `renderHeader()`, `renderFooter()`, `e()` |
| Header, merek, dan navigasi Beranda / Menu (tautan aktif ditandai) | `functions.php` | `renderHeader($judul, $halamanAktif)` |
| Footer dengan tahun berjalan dan nama warung | `functions.php` | `renderFooter($nama)` |
| Sumber data 16 menu (nama, kategori, harga, gambar, status terlaris) | `data.php` | variabel `$menu` (bukan fungsi) |
| Menampilkan kartu menu: gambar, nama, kategori, harga | `katalog.php` | `tampilGambarMenu()`, `formatRupiah()`, `e()` |
| Lencana “Terlaris” pada item dengan `'terlaris' => true` | `katalog.php` + `data.php` | kondisi `!empty($item['terlaris'])` di template |
| Pencarian menu lewat query `?q=` (tidak peka huruf besar/kecil) | `katalog.php`, `functions.php` | `filterMenu($menu, $kategori, $kata)` |
| Saring kategori Semua / Makanan / Minuman lewat `?kategori=` | `katalog.php`, `functions.php` | `daftarKategori()`, `filterMenu()` |
| Urutkan bawaan, nama A–Z, termurah, atau termahal lewat `?urut=` | `katalog.php`, `functions.php` | `urutkanMenu($menu, $urut)` |
| Statistik hasil saringan: jumlah menu, harga termurah, termahal, rata-rata | `katalog.php`, `functions.php` | `statistikMenu($hasil)` |
| Harga ditampilkan sebagai Rupiah (`Rp 12.000`) | `functions.php` | `formatRupiah($angka)` |
| Teks dari data dan query di-escape agar aman dari XSS | `functions.php` | `e($teks)` |
| Gambar menu; jika file tidak ada, tampil emoji pengganti (🍽️ / 🥤) | `functions.php` | `tampilGambarMenu($item)` |
| Validasi input GET: kategori palsu dikembalikan ke `semua`, urutan palsu ke `bawaan` | `katalog.php` | `in_array()`, `array_key_exists()` |
| Pesan kosong jika pencarian tidak ketemu | `katalog.php` | cabang `else` setelah `statistikMenu()` |
| Membuat slug URL dari teks (tersedia, belum dipanggil halaman mana pun) | `functions.php` | `buatSlug($teks)` |

Alur halaman menu di `katalog.php`:

1. Baca `$menu` dari `data.php`.
2. Ambil dan validasi `$_GET['kategori']`, `$_GET['q']`, dan `$_GET['urut']`.
3. `filterMenu()` menyaring, lalu `urutkanMenu()` mengurutkan hasilnya.
4. `statistikMenu()` menghitung ringkasan harga dari hasil itu.
5. Setiap item dicetak jadi kartu; gambar lewat `tampilGambarMenu()`.

Atribut `data-pesan`, `data-id`, `data-nama`, `data-harga`, dan `data-img` sudah dipasang di kartu, dan teks halaman berkata “Klik gambar untuk memesan”. Di berkas yang ada belum ada JavaScript maupun fungsi PHP pemesanan, jadi klik gambar belum membuka form pesan.

## 5. Fungsi array bawaan yang dipakai

| Fungsi | Dipakai di | Untuk apa |
|---|---|---|
| `array_filter()` | `filterMenu()` di `functions.php` | Menyisakan item yang kategorinya cocok dan namanya mengandung kata kunci. Callback memakai `stripos()` supaya pencarian tidak peka huruf. |
| `array_values()` | `filterMenu()` dan `daftarKategori()` di `functions.php` | Menyusun ulang indeks menjadi 0, 1, 2, … `array_filter()` dan `array_unique()` mempertahankan kunci lama, jadi hasilnya diindeks ulang. |
| `array_column()` | `statistikMenu()` dan `daftarKategori()` di `functions.php` | Mengambil satu kolom dari array menu: kolom `harga` untuk statistik, kolom `kategori` untuk daftar saringan. |
| `array_unique()` | `daftarKategori()` di `functions.php` | Membuang kategori ganda. Dari 16 item hanya tersisa `Makanan` dan `Minuman`. |
| `array_sum()` | `statistikMenu()` di `functions.php` | Menjumlahkan semua harga pada hasil saringan, lalu dibagi `count()` dan dibulatkan `round()` menjadi harga rata-rata. |
| `usort()` | `urutkanMenu()` di `functions.php` | Mengurutkan menu di tempat. `termurah` memakai `$a['harga'] <=> $b['harga']`, `termahal` kebalikannya, `nama` memakai `strcasecmp()`. Nilai `bawaan` tidak diurutkan ulang. |
| `count()` | `statistikMenu()` di `functions.php` | Menghitung jumlah menu. Jika hasilnya 0, fungsi langsung mengembalikan statistik kosong supaya tidak terjadi pembagian dengan nol. |
| `min()` dan `max()` | `statistikMenu()` di `functions.php` | Mencari harga terendah dan tertinggi dari larik harga hasil `array_column()`. |
| `in_array()` | `katalog.php` | Memeriksa apakah `$_GET['kategori']` benar-benar ada di hasil `daftarKategori()`. Jika tidak, kategori dipaksa menjadi `semua`. |
| `array_key_exists()` | `katalog.php` | Memeriksa apakah `$_GET['urut']` adalah kunci yang sah di `$opsiUrut` (`bawaan`, `nama`, `termurah`, `termahal`). Jika tidak, urutan dikembalikan ke `bawaan`. |

Fungsi bawaan lain yang dipakai, tetapi **bukan** fungsi array: `htmlspecialchars()` di `e()`, `number_format()` di `formatRupiah()`, `stripos()` di `filterMenu()`, `strcasecmp()` di `urutkanMenu()`, `strtolower()`, `trim()`, dan `preg_replace()` di `buatSlug()`, serta `file_exists()` di `tampilGambarMenu()`.

## 6. Catatan pemakaian AI

Asisten AI (Grok) dipakai untuk menjelaskan isi kode dan membadingkan hasil kode saya di bagian: `functions.php`, `style.css` di bagian `renderFooter` dan `renderHeader`.
