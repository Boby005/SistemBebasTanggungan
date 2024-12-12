<?php
include '../../login.php';
include '../../koneksi.php';

try {
    $sql = " 
            SELECT nip, nama_adm, no_telp_adm, alamat_adm, tgl_lahir_adm, jenis_kelamin_adm
            FROM dbo.admin a
            WHERE a.nip = ?";

    session_start(); // Tambahkan di atas file adm.php
    if (isset($_COOKIE['id'])) {
        $inputUsername = $_COOKIE['id'];
    } else {
        die("Anda harus login terlebih dahulu.");
    }

    $param = array($inputUsername);
    $stmt = sqlsrv_query($conn, $sql, $param);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Tangani error query
    }

    // Ambil hasil query
    if (sqlsrv_has_rows($stmt)) {
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC); // Ambil data sebagai array asosiatif
    } else {
        echo "Data tidak ditemukan.";
        $result = null; // Pastikan $result diset null jika data tidak ditemukan
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bebas Tanggungan Politeknik Negeri Malang - Teknologi Informasi</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item strong {
            font-size: 16px;
            /* Ubah ukuran teks label di sini */

            font-weight: bold;
            /* Mempertahankan teks tebal */
        }

        .info-item span {
            font-size: 16px;
            /* Sesuaikan ukuran teks untuk data */
            color: #4E73DF;
            /* Warna teks data */
        }

        .card-fixed-height {
            height: 200px;
        }

        h2 {
            font-size: 20px;
            /* Ukuran heading */
        }

        p {
            font-size: 18px;
            /* Ukuran paragraf */
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <div id="navbar"></div>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <div id="topbar"></div>
                <!-- End of Topbar -->
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"></h1>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <p></p>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>
                    <!-- End Page Heading -->

                    <!-- body -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h3 class="m-0 font-weight-bold">Informasi Pribadi</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="info-item">
                                                <strong>Nama Lengkap</strong>
                                                <span><?= htmlspecialchars($result['nama_adm'] ?? '') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <strong>NIP (No Induk)</strong>
                                                <span><?= htmlspecialchars($result['nip'] ?? '') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <strong>Nomor Telepon</strong>
                                                <span><?= htmlspecialchars($result['no_telp_adm'] ?? '') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <strong>Alamat</strong>
                                                <span><?= htmlspecialchars($result['alamat_adm'] ?? '') ?></span>
                                            </div>
                                            <div class="info-item">
                                                <strong>Jenis Kelamin</strong>
                                                <span>
                                                    <?php
                                                    if ($result['jenis_kelamin_adm'] == 'L') {
                                                        echo 'Laki-Laki';
                                                    } elseif ($result['jenis_kelamin_adm'] == 'P') {
                                                        echo 'Perempuan';
                                                    }
                                                    ?>
                                                </span>
                                            </div>

                                            <div class="info-item">
                                                <strong>Tanggal Lahir</strong>
                                                <span><?= htmlspecialchars($result['tgl_lahir_adm']->format('Y-m-d') ?? 'Tanggal tidak tersedia') ?></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Bebas Tanggungan 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../index.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('navbar.html')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    document.getElementById('navbar').innerHTML = data;
                })
                .catch(error => console.error('Error loading navbar:', error));
        });

        fetch('topbar.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('topbar').innerHTML = data;
            })
            .catch(error => console.error('Error loading topbar:', error));
    </script>
</body>

</html>