<?php
include '../koneksi.php';

if (isset($_COOKIE['id'])) {
    $nim = $_COOKIE['id'];

    // Query untuk tiap tabel
    $query = [
        'skripsi' => "SELECT 'Skripsi' AS nama, status_pengumpulan_skripsi AS status, keterangan_pengumpulan_skripsi AS keterangan FROM skripsi WHERE nim = ?",
        'program_aplikasi' => "SELECT 'Program/Aplikasi' AS nama, status_pengumpulan_program_aplikasi AS status, keterangan_pengumpulan_program_aplikasi AS keterangan FROM program_aplikasi WHERE nim = ?",
        'publikasi_jurnal' => "SELECT 'Publikasi Jurnal' AS nama, status_pengumpulan_publikasi_jurnal AS status, keterangan_pengumpulan_publikasi_jurnal AS keterangan FROM publikasi_jurnal WHERE nim = ?"
    ];

    $no = 1;
    foreach ($query as $key => $sql) {
        $stmt = sqlsrv_query($conn, $sql, [$nim]);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $statusClass = match ($row['status']) {
                'kosong' => 'bg-secondary text-white',
                'diproses' => 'bg-warning text-dark',
                'ditolak' => 'bg-danger text-white',
                'terverifikasi' => 'bg-success text-white',
                default => 'bg-light text-dark'
            };

            $button = ($row['status'] === 'kosong' || $row['status'] === 'ditolak') ?
                "<button onclick=\"setUploadDir('{$key}')\" class=\"btn btn-success btn-sm\" data-toggle=\"modal\" data-target=\"#uploadModal\">
                    <i class=\"fas fa-solid fa-cloud-arrow-up\"></i> Upload
                </button>" :
                "<button class=\"btn btn-secondary btn-sm\" disabled>Disable</button>";

            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td><span class='badge {$statusClass} p-2 rounded text-uppercase fs-5' style='cursor: pointer;' title='{$row['status']}'>
                        {$row['status']}
                    </span></td>
                <td>{$row['keterangan']}</td>
                <td>{$button}</td>
            </tr>";
            $no++;
        }
        sqlsrv_free_stmt($stmt);
    }
} else {
    echo "<tr><td colspan='5'>NIM belum diatur. Silakan login terlebih dahulu.</td></tr>";
}

sqlsrv_close($conn);
?>