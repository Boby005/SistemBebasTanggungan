<?php 
include '../../../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $status = $_POST['status'];

    $sql = "UPDATE dbo.skripsi SET status_pengumpulan_skripsi = ? WHERE NIM = ?";
    $params = array($status, $nim);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Redirect ke index.php dengan pesan error
        header(header: "Location: ../skripsi.php?message=Terjadi+kesalahan+saat+memperbarui+data&type=danger");
    } else {
        // Redirect ke index.php dengan pesan sukses
        header("Location: ../skripsi.php?message=Status+berhasil+diperbarui!&type=success");
    if ($status === 'terverifikasi'){
        $sqlConfirm ="UPDATE dbo.skripsi SET keterangan_pengumpulan_skripsi = 'jurnal valid' WHERE NIM = ?";
        $paramsConfirm = array($nim);
        $stmtConfirm = sqlsrv_query($conn, $sqlConfirm,$paramsConfirm);
    }
    //else if($status === 'tidak terverifikasi'){

    // }
    }
    exit();
} else {
    // Redirect ke index.php dengan pesan error
    header("Location: ../skripsi.php?message=Data+tidak+valid&type=danger");
    exit();
}
    // if ($stmt === false) {
    //     die(print_r(sqlsrv_errors(), true));
    // }else{
    //     echo "<script>
    //     alert('Status berhasil diperbarui!');
    //     window.location.href = '../AdminPerpus/skripsi.php';
    //     </script>";
    //     exit();
    // }

    
//}
?>