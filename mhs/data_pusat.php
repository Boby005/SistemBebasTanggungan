<?php
include '../koneksi.php';

if (isset($_COOKIE['id'])) {
    $nim = $_COOKIE['id'];

    // Query untuk tiap tabel
    $query = [
        'data_alumni' => "SELECT 'Data Alumni' AS nama, status_pengumpulan_data_alumni AS status, keterangan_pengumpulan_data_alumni AS keterangan FROM data_alumni WHERE nim = ?",
        'skkm' => "SELECT 'SKKM' AS nama, status_pengumpulan_skkm AS status, keterangan_pengumpulan_skkm AS keterangan FROM skkm WHERE nim = ?",
        'foto_ijazah' => "SELECT 'Foto Ijazah' AS nama, status_pengumpulan_foto_ijazah AS status, keterangan_pengumpulan_foto_ijazah AS keterangan FROM foto_ijazah WHERE nim = ?",
        'ukt' => "SELECT 'UKT' AS nama, status_pengumpulan_ukt AS status, keterangan_pengumpulan_ukt AS keterangan FROM ukt WHERE nim = ?"
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

            // Tentukan file download berdasarkan key
            $filePath = '../Documents/Uploads/' . $key . '/' . $nim . '_' . basename($key) . '.pdf'; // Sesuaikan dengan ekstensi file yang diharapkan (misal .pdf)
            $downloadButton = '';
            $uploadButton = '';

            // Tombol Upload: aktif jika status 'kosong' atau 'ditolak', disabled jika status lainnya
            if ($row['status'] === 'kosong' || $row['status'] === 'ditolak') {
                $uploadButton = "<button onclick=\"setUploadDir('{$key}')\" class=\"btn btn-primary btn-sm\" data-toggle=\"modal\" data-target=\"#uploadModal\">
                                    <i class=\"fas fa-solid fa-cloud-arrow-up\"></i> Upload 
                                  </button>";
            } else {
                $uploadButton = "<button class=\"btn btn-secondary btn-sm\" disabled><i class=\"fas fa-solid fa-cloud-arrow-up\"></i> Disable</button>";
            }

            // // Tombol Download: aktif jika statusnya 'diproses', 'ditolak', atau 'terverifikasi', dan file ada
            // if (in_array($row['status'], ['diproses', 'ditolak', 'terverifikasi']) && file_exists($filePath)) {
            //     $downloadButton = "<a href='{$filePath}' class='btn btn-success btn-sm' download>
            //                         <i class='fas fa-download'></i> Download
            //                       </a>";
            // } else {
            //     $downloadButton = "<button class=\"btn btn-secondary btn-sm\" disabled><i class='fas fa-download'></i> Disable</button>";
            // }

            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td><span class='badge {$statusClass} p-2 rounded text-uppercase fs-5' style='cursor: pointer;' title='{$row['status']}'>
                        {$row['status']}
                    </span></td>
                <td>{$row['keterangan']}</td>
                <td>{$uploadButton}</td>
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