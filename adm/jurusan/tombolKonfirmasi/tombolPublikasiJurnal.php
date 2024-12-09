<?php 
include '../../../koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $status = $_POST['status'];

    $sql = "UPDATE dbo.publikasi_jurnal SET status_pengumpulan_publikasi_jurnal = ? WHERE NIM = ?";
    $params = array($status, $nim);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        // Redirect ke index.php dengan pesan error
        header(header: "Location: ../publikasi_jurnal.php?message=Terjadi+kesalahan+saat+memperbarui+data&type=danger");
    } else {
        // Redirect ke index.php dengan pesan sukses
        header("Location: ../publikasi_jurnal.php?message=Status+berhasil+diperbarui!&type=success");
    if ($status === 'terverifikasi'){
        $sqlConfirm ="UPDATE dbo.publikasi_jurnal SET keterangan_pengumpulan_publikasi_jurnal = 'jurnal valid' WHERE NIM = ?";
        $paramsConfirm = array($nim);
        $stmtConfirm = sqlsrv_query($conn, $sqlConfirm,$paramsConfirm);
    }
    //else if($status === 'tidak terverifikasi'){

    // }
    }
    exit();
} else {
    // Redirect ke index.php dengan pesan error
    header("Location: ../publikasi_jurnal.php?message=Data+tidak+valid&type=danger");
    exit();
}
    // if ($stmt === false) {
    //     die(print_r(sqlsrv_errors(), true));
    // }else{
    //     echo "<script>
    //     alert('Status berhasil diperbarui!');
    //     window.location.href = '../AdminPerpus/publikasi_jurnal.php';
    //     </script>";
    //     exit();
    // }

    
//}
?>