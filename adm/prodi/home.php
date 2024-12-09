<!-- prodi -->

<?php
include '../../koneksi.php';

// Query untuk TOEIC
$toeicQuery = "
    SELECT 
        COUNT(CASE WHEN status_pengumpulan_toeic = 'terverifikasi' THEN 1 END) AS terverifikasi,
        COUNT(CASE WHEN status_pengumpulan_toeic = 'diproses' THEN 1 END) AS diproses,
        COUNT(CASE WHEN status_pengumpulan_toeic = 'belum upload' THEN 1 END) AS kosong,
        COUNT(CASE WHEN status_pengumpulan_toeic = 'ditolak' THEN 1 END) AS tidak_terverifikasi
    FROM toeic;
";
$toeicResult = sqlsrv_query($conn, $toeicQuery);
$toeicRow = sqlsrv_fetch_array($toeicResult, SQLSRV_FETCH_ASSOC);

// Query untuk Penyerahan Skripsi
$skripsiQuery = "
    SELECT 
        COUNT(CASE WHEN status_pengumpulan_serahan_skripsi = 'terverifikasi' THEN 1 END) AS terverifikasi,
        COUNT(CASE WHEN status_pengumpulan_serahan_skripsi = 'diproses' THEN 1 END) AS diproses,
        COUNT(CASE WHEN status_pengumpulan_serahan_skripsi = 'belum upload' THEN 1 END) AS kosong,
        COUNT(CASE WHEN status_pengumpulan_serahan_skripsi = 'ditolak' THEN 1 END) AS tidak_terverifikasi
    FROM serahan_skripsi;
";
$skripsiResult = sqlsrv_query($conn, $skripsiQuery);
$skripsiRow = sqlsrv_fetch_array($skripsiResult, SQLSRV_FETCH_ASSOC);

// Query untuk Penyerahan PKL
$pklQuery = "
    SELECT 
        COUNT(CASE WHEN status_pengumpulan_serahan_pkl = 'terverifikasi' THEN 1 END) AS terverifikasi,
        COUNT(CASE WHEN status_pengumpulan_serahan_pkl = 'diproses' THEN 1 END) AS diproses,
        COUNT(CASE WHEN status_pengumpulan_serahan_pkl = 'belum upload' THEN 1 END) AS kosong,
        COUNT(CASE WHEN status_pengumpulan_serahan_pkl = 'ditolak' THEN 1 END) AS tidak_terverifikasi
    FROM serahan_pkl;
";
$pklResult = sqlsrv_query($conn, $pklQuery);
$pklRow = sqlsrv_fetch_array($pklResult, SQLSRV_FETCH_ASSOC);

// Query untuk Bebas Kompen
$kompenQuery = "
    SELECT 
        COUNT(CASE WHEN status_pengumpulan_bebas_kompen = 'terverifikasi' THEN 1 END) AS terverifikasi,
        COUNT(CASE WHEN status_pengumpulan_bebas_kompen = 'diproses' THEN 1 END) AS diproses,
        COUNT(CASE WHEN status_pengumpulan_bebas_kompen = 'belum upload' THEN 1 END) AS kosong,
        COUNT(CASE WHEN status_pengumpulan_bebas_kompen = 'ditolak' THEN 1 END) AS tidak_terverifikasi
    FROM bebas_kompen;
";
$kompenResult = sqlsrv_query($conn, $kompenQuery);
$kompenRow = sqlsrv_fetch_array($kompenResult, SQLSRV_FETCH_ASSOC);

// Query untuk Penyerahan Kebenaran Data
$kebenaranQuery = "
    SELECT 
        COUNT(CASE WHEN status_pengumpulan_kebenaran_data = 'terverifikasi' THEN 1 END) AS terverifikasi,
        COUNT(CASE WHEN status_pengumpulan_kebenaran_data = 'diproses' THEN 1 END) AS diproses,
        COUNT(CASE WHEN status_pengumpulan_kebenaran_data = 'belum upload' THEN 1 END) AS kosong,
        COUNT(CASE WHEN status_pengumpulan_kebenaran_data = 'ditolak' THEN 1 END) AS tidak_terverifikasi
    FROM kebenaran_data;
";
$kebenaranResult = sqlsrv_query($conn, $kebenaranQuery);
$kebenaranRow = sqlsrv_fetch_array($kebenaranResult, SQLSRV_FETCH_ASSOC);

sqlsrv_close($conn);
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .status span {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .status .badge-success {
            background-color: #1cc88a;
        }

        .status .badge-warning {
            background-color: #f6c23e;
            color: #5a5c69;
        }

        .status .badge-secondary {
            background-color: #858796;
        }

        .status .badge-danger {
            background-color: #e74a3b;
        }

        .card-fixed-height {
            height: 200px;
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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <p></p>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Kolom untuk tabel -->
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Dokumen</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr class="table-header bg-primary text-white">
                                                    <th rowspan="2">Status</th>
                                                    <th colspan="5">Dokumen</th>
                                                    <th rowspan="2">Total</th>
                                                </tr>
                                                <tr class="table-header bg-primary text-white">
                                                    <th>Skripsi</th>
                                                    <th>PKL</th>
                                                    <th>TOEIC</th>
                                                    <th>Bebas Kompen</th>
                                                    <th>Kebenaran Data</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $statuses = ['terverifikasi', 'diproses', 'kosong', 'ditolak'];
                                                foreach ($statuses as $status) {
                                                    // Menentukan kelas badge berdasarkan status
                                                    $statusClass = '';
                                                    switch ($status) {
                                                        case 'terverifikasi':
                                                            $statusClass = 'badge-success';
                                                            break;
                                                        case 'diproses':
                                                            $statusClass = 'badge-warning';
                                                            break;
                                                        case 'kosong':
                                                            $statusClass = 'badge-secondary';
                                                            break;
                                                        case 'ditolak':
                                                            $statusClass = 'badge-danger';
                                                            break;
                                                    }

                                                    // Data tiap dokumen
                                                    $skripsi = $skripsiRow[$status] ?? 0;
                                                    $pkl = $pklRow[$status] ?? 0;
                                                    $toeic = $toeicRow[$status] ?? 0;
                                                    $kompen = $kompenRow[$status] ?? 0;
                                                    $kebenaran = $kebenaranRow[$status] ?? 0;
                                                    $total = $skripsi + $pkl + $toeic + $kompen + $kebenaran;

                                                    echo "<tr>
                                                            <td class='status'>
                                                                <span class='badge $statusClass p-2 rounded text-uppercase'
                                                                    style='cursor: pointer;'
                                                                    title='" . htmlspecialchars($status) . "'>
                                                                    " . htmlspecialchars($status) . "
                                                                </span>
                                                            </td>
                                                            <td>$skripsi</td>
                                                            <td>$pkl</td>
                                                            <td>$toeic</td>
                                                            <td>$kompen</td>
                                                            <td>$kebenaran</td>
                                                            <td><strong>$total</strong></td>
                                                        </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End of Content Row -->
                </div>
                <!-- /.container-fluid -->


            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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

        document.addEventListener("DOMContentLoaded", function () {
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