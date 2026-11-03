
# Weather Data REST Client

Aplikasi REST Client sederhana berbasis PHP untuk mengambil dan menampilkan data cuaca suatu kota menggunakan Open-Meteo API dan PHP cURL.

## Fitur
- Pencarian cuaca berdasarkan nama kota
- Menampilkan suhu, kelembapan, kecepatan angin, dan kondisi cuaca
- Validasi input kosong
- Penanganan kota tidak ditemukan
- Penanganan koneksi gagal
- Penanganan respons API tidak valid
- Tampilan responsif

## Teknologi
- PHP
- PHP cURL
- HTML
- CSS
- Open-Meteo API

## Cara Menjalankan
1. Clone repository ini atau download sebagai ZIP.
2. Simpan folder project di dalam folder `htdocs` XAMPP.
3. Pastikan Apache aktif.
4. Buka browser dan akses:

   `http://localhost/weather-rest-client/`

## Pengujian

| No | Skenario Pengujian | Hasil |
|---|---|---|
| 1 | Input nama kota valid | Berhasil menampilkan data cuaca |
| 2 | Input nama kota tidak valid | Menampilkan pesan kota tidak ditemukan |
| 3 | Input kosong | Menampilkan pesan validasi |
| 4 | Koneksi internet terputus | Menampilkan pesan kegagalan koneksi |
| 5 | Respons API tidak valid | Menampilkan pesan respons tidak valid |

## Tampilan Aplikasi

![Tampilan Aplikasi](screenshots/home.png)

### Hasil Pengujian

**1. Nama Kota Valid**

![Nama Kota Valid](screenshots/nama%20kota%20benar.png)

**2. Nama Kota Tidak Valid**

![Nama Kota Tidak Valid](screenshots/nama%20kota%20tidak%20valid.png)

**3. Input Kosong**

![Input Kosong](screenshots/input%20kosong.png)

**4. Koneksi Gagal**

![Koneksi Gagal](screenshots/koneksi%20gagal.png)

**5. Respons Tidak Valid**

![Respons Tidak Valid](screenshots/respon%20tidak%20valid.png)
