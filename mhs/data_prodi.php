<?php
include '../koneksi.php';

if (isset($_COOKIE['id'])) {
    $nim = $_COOKIE['id'];

    // Query untuk prodi
    $query = [
        'serahan_skripsi' => "SELECT 'Serahan Skripsi' AS nama, status_pengumpulan_serahan_skripsi AS status, keterangan_pengumpulan_serahan_skripsi AS keterangan FROM serahan_skripsi WHERE nim = ?",
        'serahan_pkl' => "SELECT 'Serahan PKL' AS nama, status_pengumpulan_serahan_pkl AS status, keterangan_pengumpulan_serahan_pkl AS keterangan FROM serahan_pkl WHERE nim = ?",
        'toeic' => "SELECT 'TOEIC' AS nama, status_pengumpulan_toeic AS status, keterangan_pengumpulan_toeic AS keterangan FROM toeic WHERE nim = ?",
        'bebas_kompen' => "SELECT 'Bebas Kompen' AS nama, status_pengumpulan_bebas_kompen AS status, keterangan_pengumpulan_bebas_kompen AS keterangan FROM bebas_kompen WHERE nim = ?",
        'kebenaran_data' => "SELECT 'Kebenaran Data' AS nama, status_pengumpulan_kebenaran_data AS status, keterangan_pengumpulan_kebenaran_data AS keterangan FROM kebenaran_data WHERE nim = ?"
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