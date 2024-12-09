<?php
include 'koneksi.php'; // Pastikan koneksi berhasil

// Mendapatkan parameter kategori dari permintaan GET
$category = $_GET['category'] ?? '';

if (!$category) {
    echo json_encode(['error' => 'Category not specified']);
    exit;
}

// Pemetaan kategori ke tabel (untuk query sederhana)
$table_map = [
    'prodi' => 'bebas_kompen',
    'perpustakaan' => 'ta_softcopy',
    'jurusan' => 'program_aplikasi',
    'pusat' => 'skkm'
];

// Fungsi untuk query kompleks berdasarkan kategori
function getComplexProgressData($conn, $category)
{
    // ... (kode query kompleks yang sama seperti sebelumnya)
    switch ($category) {
        case 'prodi':
            $query = "
                WITH StatusCount AS (
                    SELECT status_pengumpulan_bebas_kompen AS status, COUNT(*) AS count FROM bebas_kompen
                    UNION ALL
                    SELECT status_pengumpulan_kebenaran_data AS status, COUNT(*) AS count FROM kebenaran_data
                    UNION ALL
                    SELECT status_pengumpulan_serahan_pkl AS status, COUNT(*) AS count FROM serahan_pkl
                    UNION ALL
                    SELECT status_pengumpulan_serahan_skripsi AS status, COUNT(*) AS count FROM serahan_skripsi
                    UNION ALL
                    SELECT status_pengumpulan_toeic AS status, COUNT(*) AS count FROM toeic
                )
                SELECT status, SUM(count) AS total FROM StatusCount GROUP BY status;
            ";
            break;

        case 'jurusan':
            $query = "
                WITH StatusCount AS (
                    SELECT status_pengumpulan_program_aplikasi AS status, COUNT(*) AS count FROM program_aplikasi
                    UNION ALL
                    SELECT status_pengumpulan_skripsi AS status, COUNT(*) AS count FROM skripsi
                    UNION ALL
                    SELECT status_pengumpulan_publikasi_jurnal AS status, COUNT(*) AS count FROM publikasi_jurnal
                )
                SELECT status, SUM(count) AS total FROM StatusCount GROUP BY status;
            ";
            break;

        case 'perpustakaan':
            $query = "
                WITH StatusCount AS (
                    SELECT status_pengumpulan_ta_softcopy AS status, COUNT(*) AS count FROM ta_softcopy
                    UNION ALL
                    SELECT status_pengumpulan_serahan_hardcopy AS status, COUNT(*) AS count FROM serahan_hardcopy
                    UNION ALL
                    SELECT status_pengumpulan_hasil_quesioner AS status, COUNT(*) AS count FROM hasil_quesioner
                    UNION ALL
                    SELECT status_pengumpulan_bebas_pinjam_perpustakaan AS status, COUNT(*) AS count FROM bebas_pinjam_perpustakaan
                )
                SELECT status, SUM(count) AS total FROM StatusCount GROUP BY status;
            ";
            break;

        case 'pusat':
            $query = "
                WITH StatusCount AS (
                    SELECT status_pengumpulan_skkm AS status, COUNT(*) AS count FROM skkm
                    UNION ALL
                    SELECT status_pengumpulan_foto_ijazah AS status, COUNT(*) AS count FROM foto_ijazah
                    UNION ALL
                    SELECT status_pengumpulan_ukt AS status, COUNT(*) AS count FROM ukt
                    UNION ALL
                    SELECT status_pengumpulan_data_alumni AS status, COUNT(*) AS count FROM data_alumni
                )
                SELECT status, SUM(count) AS total FROM StatusCount GROUP BY status;
            ";
            break;

        default:
            return []; // Jika kategori tidak sesuai, kembalikan array kosong
    }
}

// Jika kategori ada di map tabel (query sederhana)
if (array_key_exists($category, $table_map)) {
    $table_name = $table_map[$category];

    $query = "
        SELECT 
            COUNT(CASE WHEN status_pengumpulan_$category = 'terverifikasi' THEN 1 END) AS terverifikasi,
            COUNT(CASE WHEN status_pengumpulan_$category = 'diproses' THEN 1 END) AS diproses,
            COUNT(CASE WHEN status_pengumpulan_$category = 'kosong' THEN 1 END) AS kosong,
            COUNT(CASE WHEN status_pengumpulan_$category = 'ditolak' THEN 1 END) AS tidak_terverifikasi
        FROM $table_name";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode(['error' => 'Query error: ' . mysqli_error($conn)]);
        exit;
    }

    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
} else {
    // Gunakan query kompleks untuk kategori tertentu
    $data = getComplexProgressData($conn, $category);
    echo json_encode($data);
}
