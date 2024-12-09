<?php 
include '../../../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $status = $_POST['status'];

    $sql = "UPDATE dbo.program_aplikasi SET status_pengumpulan_program_aplikasi = ? WHERE NIM = ?";
    $params = array($status, $nim);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Redirect ke index.php dengan pesan error
        header(header: "Location: ../program_a.php?message=Terjadi+kesalahan+saat+memperbarui+data&type=danger");
    } else {
        // Redirect ke index.php dengan pesan sukses
        header("Location: ../program_a.php?message=Status+berhasil+diperbarui!&type=success");
    if ($status === 'terverifikasi'){
        $sqlConfirm ="UPDATE dbo.program_aplikasi SET keterangan_pengumpulan_program_aplikasi = 'program_aplikasi diterima' WHERE NIM = ?";
        $paramsConfirm = array($nim);
        $stmtConfirm = sqlsrv_query($conn, $sqlConfirm,$paramsConfirm);
    }
    //else if($status === 'tidak terverifikasi'){

    // }
    }
    exit();
} else {
    // Redirect ke index.php dengan pesan error
    header("Location: ../program_a.php?message=Data+tidak+valid&type=danger");
    exit();
}

?>