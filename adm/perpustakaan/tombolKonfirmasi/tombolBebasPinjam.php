<?php
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];

    // Pastikan status verifikasi terpilih
    if (!isset($_POST['status_verifikasi'])) {
        echo json_encode(['success' => false, 'error' => 'Status verifikasi tidak dipilih.']);
        exit();
    }

    $statusVerifikasi = $_POST['status_verifikasi'];

    // Validasi keterangan jika status verifikasi 'ditolak'
    if ($statusVerifikasi === 'ditolak' && ($_POST['keterangan'] === '' || !isset($_POST['keterangan']))) {
        echo json_encode(['success' => false, 'error' => 'Keterangan wajib diisi jika status verifikasi ditolak.']);
        exit();
    }

    // Jika keterangan tidak dikirim, set default "-"
    $keterangan = isset($_POST['keterangan']) && $_POST['keterangan'] !== '' ? $_POST['keterangan'] : '-';

    // Query untuk update 
    $sql = "UPDATE dbo.bebas_pinjam_perpustakaan
            SET status_pengumpulan_bebas_pinjam_perpustakaan = ?, keterangan_pengumpulan_bebas_pinjam_perpustakaan = ?
            WHERE nim = ?";
    $params = [$statusVerifikasi, $keterangan, $nim];

    // Mengeksekusi query
    $stmt = sqlsrv_query($conn, $sql, $params);

    $sqlTanggal = "UPDATE dbo.adminPerpus_konfirmasi SET tanggal_adminPerpus_konfirmasi = GETDATE() WHERE nim = ?";
    $params2 = [$nim];

    $stmt2 = sqlsrv_query($conn, $sqlTanggal, $params2);

    if ($stmt && $stmt2) {
        header("Location: ../bp_perpus.php?message=Status+berhasil+diperbarui!&type=success");
        exit();
    } else {
        // Cek apakah ada error dari SQL Server
        $errors = sqlsrv_errors();
        echo json_encode(['success' => false, 'error' => $errors ? $errors : 'Unknown error dah masuk sini']);
    }
    
}
?>