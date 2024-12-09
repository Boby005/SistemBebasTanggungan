<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username dan password
    $sql = "SELECT position, username FROM [dbo].[login] WHERE username = ? AND password = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Kesalahan saat menjalankan query: " . print_r(sqlsrv_errors(), true));
    }

    // Memeriksa apakah ada hasil
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Pastikan cookie 'nim' diset sebelum header
        setcookie('id', $row['username'], time() + 3600, "/"); // Mengatur path cookie agar dapat diakses di seluruh aplikasi

        // Pengalihan berdasarkan position
        switch ($row['position']) {
            case 'a.prodi':
                header("Location: adm/prodi/home.html");
                break;
            case 'a.jurusan':
                header("Location: adm/jurusan/home.html");
                break;
            case 'a.pusat':
                header("Location: adm/pusat/home.html");
                break;
            case 'a.perpus':
                header("Location: adm/perpustakaan/home.html");
                break;
            case 'mahasiswa':
                header("Location: mhs/home.php");
                break;
            default:
                echo "position tidak dikenal.";
                break;
        }
        exit;
    } else {
        // Jika username/password tidak cocok
        echo "Username atau password salah.";
    }

    // Menutup statement
    sqlsrv_free_stmt($stmt);
}
?>