<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi ID dari cookie
    if (!isset($_COOKIE['id'])) {
        echo "ID tidak ditemukan.";
        exit;
    }

    $id = htmlspecialchars($_COOKIE['id']); // Amankan data dari cookie

    
    // Tentukan tabel berdasarkan input hidden dari uploadDir
    $directoryLabel = htmlspecialchars($_POST['uploadDir']);
    // Tentukan direktori tujuan tetap
    $uploadDir = '../Documents/uploads/' . $directoryLabel;

    $tableMap = [
        'skkm' => 'skkm',
        'foto_ijazah' => 'foto_ijazah',
        'ukt' => 'ukt',
        'data_alumni' => 'data_alumni',
        'ta_softcopy' => 'ta_softcopy',
        'serahan_hardcopy' => 'serahan_hardcopy',
        'hasil_quesioner' => 'hasil_quesioner',
        
        'bebas_pinjam_perpustakaan' => 'bebas_pinjam_perpustakaan',
        'bebas_kompen' => 'bebas_kompen',
        'kebenaran_data' => 'kebenaran_data',
        'serahan_pkl' => 'serahan_pkl',
        'serahan_skripsi' => 'serahan_skripsi',
        'toeic' => 'toeic',
        
        'program_aplikasi' => 'program_aplikasi',
        'publikasi_jurnal' => 'publikasi_jurnal',
        'skripsi' => 'skripsi'
    ];
    
    $tableName = isset($tableMap[$directoryLabel]) ? $tableMap[$directoryLabel] : null;
    

    if (!$tableName) {
        echo "Nama tabel tidak valid.";
        exit;
    }

    // Validasi file
    $allowedExtensions = ['pdf', 'zip', 'rar'];
    $maxFileSize = 3 * 1024 * 1024; // Default: 3 MB

    if ($directoryLabel === 'skripsi') {
        $allowedExtensions = ['pdf'];
        $maxFileSize = 20 * 1024 * 1024; // 20 MB
    } elseif ($directoryLabel === 'aplikasi') {
        $allowedExtensions = ['zip', 'rar'];
        $maxFileSize = 30 * 1024 * 1024; // 30 MB
    }

    $originalFileName = basename($_FILES["file"]["name"]);
    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $fileSize = $_FILES["file"]["size"];

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Tipe file tidak diizinkan. Hanya diperbolehkan: " . implode(', ', $allowedExtensions) . ".";
        exit;
    }

    if ($fileSize > $maxFileSize) {
        echo "Ukuran file terlalu besar. Maksimal: " . ($maxFileSize / (1024 * 1024)) . " MB.";
        exit;
    }

    // Buat direktori jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Buat nama file baru
    $newFileName = $id . '_' . $directoryLabel . '.' . $fileExtension;

    // Tentukan path lengkap untuk menyimpan file
    $target_file = $uploadDir . '/' . $newFileName;

    // Cek apakah file sudah ada
    if (file_exists($target_file)) {
        // Hapus file lama
        if (!unlink($target_file)) {
            echo "Gagal menghapus file lama.";
            exit;
        }
    }

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        // Query untuk memperbarui status
        $sql = "UPDATE {$tableName} 
        SET status_pengumpulan_{$directoryLabel} = 'diproses', 
            keterangan_pengumpulan_{$directoryLabel} = 'Menunggu Proses Verifikasi' 
        WHERE nim = ?";
        $params = [$id];

        // Eksekusi query
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Redirect ke halaman dengan status sukses
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=success');
    } else {
        // Redirect ke halaman dengan status error
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=error');
    }
} else {
    echo "Tidak ada file yang diupload.";
}
?>
