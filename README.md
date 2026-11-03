
# Weather Data REST Client

Weather Data REST Client merupakan aplikasi sederhana berbasis PHP yang digunakan untuk mengambil dan menampilkan informasi cuaca berdasarkan nama kota. Aplikasi ini dibuat sebagai implementasi REST Client menggunakan PHP cURL dan Open-Meteo API.

## Deskripsi

Aplikasi ini memungkinkan pengguna mencari informasi cuaca suatu kota melalui input nama kota. Setelah nama kota dimasukkan, aplikasi akan mengirimkan request ke API untuk mendapatkan data lokasi dan informasi cuaca terkini.

Data yang ditampilkan meliputi:
- Nama kota dan negara
- Suhu
- Kelembapan
- Kecepatan angin
- Kondisi cuaca

Selain menampilkan informasi cuaca, aplikasi juga memiliki penanganan untuk input kosong, nama kota yang tidak ditemukan, kegagalan koneksi, dan respons API yang tidak valid.

## API yang Digunakan

Aplikasi menggunakan Open-Meteo API yang menyediakan data cuaca dan informasi lokasi.

API yang digunakan:
- **Geocoding API**: digunakan untuk mencari koordinat lokasi berdasarkan nama kota.
- **Forecast API**: digunakan untuk mengambil data cuaca berdasarkan koordinat latitude dan longitude.

Open-Meteo dapat digunakan tanpa API key untuk kebutuhan aplikasi ini.

Website API:
https://open-meteo.com/

## Teknologi yang Digunakan

- PHP
- PHP cURL
- HTML
- CSS
- Open-Meteo API
- XAMPP

## Cara Kerja Aplikasi

1. Pengguna memasukkan nama kota pada kolom pencarian.
2. Aplikasi mengirimkan request ke Geocoding API untuk mencari lokasi kota.
3. API mengembalikan informasi lokasi beserta koordinat latitude dan longitude.
4. Koordinat tersebut digunakan untuk mengirim request ke Forecast API.
5. Data cuaca yang diterima kemudian ditampilkan pada halaman aplikasi.

## Struktur File

```text
weather-rest-client/
├── index.php
├── api.php
├── style.css
├── README.md
├── .gitignore
└── screenshots/
    ├── home.png
    ├── input kosong.png
    ├── koneksi gagal.png
    ├── nama kota benar.png
    ├── nama kota tidak valid.png
    └── respon tidak valid.png
```

## Cara Menjalankan Aplikasi

1. Download atau clone repository ini.
2. Simpan folder project di dalam folder `htdocs` pada XAMPP.
3. Jalankan Apache melalui XAMPP.
4. Buka browser.
5. Akses alamat berikut:

   `http://localhost/weather-rest-client/`

6. Masukkan nama kota yang ingin dicari pada kolom pencarian.

## Pengujian Aplikasi

Pengujian dilakukan untuk memastikan aplikasi dapat berjalan sesuai dengan kondisi input dan respons API yang diterima.

| No. | Skenario Pengujian | Hasil |
|---|---|---|
| 1 | Memasukkan nama kota yang valid | Data cuaca berhasil ditampilkan |
| 2 | Memasukkan nama kota yang tidak valid | Aplikasi menampilkan pesan kota tidak ditemukan |
| 3 | Mengosongkan kolom input | Aplikasi menampilkan pesan validasi |
| 4 | Memutus koneksi internet | Aplikasi menampilkan pesan kegagalan koneksi |
| 5 | Menggunakan endpoint API dengan respons tidak valid | Aplikasi menampilkan pesan respons tidak valid |

Seluruh skenario pengujian tersebut telah dicoba dan menghasilkan respons sesuai dengan yang diharapkan.

## Tampilan Aplikasi

![Tampilan Aplikasi](screenshots/home.png)

## Hasil Pengujian

### 1. Nama Kota Valid

Pengujian dilakukan dengan memasukkan nama kota Nganjuk. Aplikasi berhasil menampilkan informasi cuaca dari kota yang dicari.

![Nama Kota Valid](screenshots/nama%20kota%20benar.png)

### 2. Nama Kota Tidak Valid

Pengujian dilakukan dengan memasukkan nama kota yang tidak ditemukan. Aplikasi menampilkan pesan bahwa kota tidak ditemukan.

![Nama Kota Tidak Valid](screenshots/nama%20kota%20tidak%20valid.png)

### 3. Input Kosong

Pengujian dilakukan tanpa memasukkan nama kota. Aplikasi menampilkan pesan agar pengguna memasukkan nama kota terlebih dahulu.

![Input Kosong](screenshots/input%20kosong.png)

### 4. Koneksi Gagal

Pengujian dilakukan dengan memutus koneksi internet. Aplikasi menampilkan pesan kegagalan koneksi ketika tidak dapat mengambil data dari API.

![Koneksi Gagal](screenshots/koneksi%20gagal.png)

### 5. Respons Tidak Valid

Pengujian dilakukan dengan mengubah endpoint API sehingga aplikasi menerima respons yang tidak sesuai. Aplikasi menampilkan pesan bahwa respons dari layanan cuaca kosong atau tidak valid.

![Respons Tidak Valid](screenshots/respon%20tidak%20valid.png)

## Repository

Source code aplikasi ini dapat dilihat melalui GitHub:

https://github.com/popoposhh/weather-rest-client
