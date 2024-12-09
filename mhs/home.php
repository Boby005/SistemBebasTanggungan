<?php
include '../koneksi.php';  // Memanggil koneksi

// Mengecek apakah cookie 'id' ada
if (!isset($_COOKIE['id'])) {
    header("Location: ../index.html");
    exit();
}

$nim = $_COOKIE['id'];

// Query untuk total terverifikasi
$query_terverifikasi = "
SELECT SUM(total_terverifikasi) AS total_terverifikasi
FROM (
    SELECT COUNT(*) AS total_terverifikasi FROM skkm WHERE status_pengumpulan_skkm = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM foto_ijazah WHERE status_pengumpulan_foto_ijazah = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM ukt WHERE status_pengumpulan_ukt = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM data_alumni WHERE status_pengumpulan_data_alumni = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM ta_softcopy WHERE status_pengumpulan_ta_softcopy = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM serahan_hardcopy WHERE status_pengumpulan_serahan_hardcopy = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM hasil_quesioner WHERE status_pengumpulan_hasil_quesioner = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM bebas_pinjam_perpustakaan WHERE status_pengumpulan_bebas_pinjam_perpustakaan = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM bebas_kompen WHERE status_pengumpulan_bebas_kompen = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM kebenaran_data WHERE status_pengumpulan_kebenaran_data = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM serahan_pkl WHERE status_pengumpulan_serahan_pkl = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM serahan_skripsi WHERE status_pengumpulan_serahan_skripsi = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM toeic WHERE status_pengumpulan_toeic = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM program_aplikasi WHERE status_pengumpulan_program_aplikasi = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM skripsi WHERE status_pengumpulan_skripsi = 'terverifikasi'
    UNION ALL
    SELECT COUNT(*) AS total_terverifikasi FROM publikasi_jurnal WHERE status_pengumpulan_publikasi_jurnal = 'terverifikasi'
) AS all_tables;
";


// Query untuk menghitung status diproses, ditolak, dan kosong untuk pengguna tertentu
$query_status_user = "
SELECT SUM(total_status) AS total_status
FROM (
    SELECT COUNT(*) AS total_status FROM skkm WHERE nim = ? AND status_pengumpulan_skkm IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM foto_ijazah WHERE nim = ? AND status_pengumpulan_foto_ijazah IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM ukt WHERE nim = ? AND status_pengumpulan_ukt IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM data_alumni WHERE nim = ? AND status_pengumpulan_data_alumni IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM ta_softcopy WHERE nim = ? AND status_pengumpulan_ta_softcopy IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM serahan_hardcopy WHERE nim = ? AND status_pengumpulan_serahan_hardcopy IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM hasil_quesioner WHERE nim = ? AND status_pengumpulan_hasil_quesioner IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM bebas_pinjam_perpustakaan WHERE nim = ? AND status_pengumpulan_bebas_pinjam_perpustakaan IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM bebas_kompen WHERE nim = ? AND status_pengumpulan_bebas_kompen IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM kebenaran_data WHERE nim = ? AND status_pengumpulan_kebenaran_data IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM serahan_pkl WHERE nim = ? AND status_pengumpulan_serahan_pkl IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM serahan_skripsi WHERE nim = ? AND status_pengumpulan_serahan_skripsi IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM toeic WHERE nim = ? AND status_pengumpulan_toeic IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM program_aplikasi WHERE nim = ? AND status_pengumpulan_program_aplikasi IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM skripsi WHERE nim = ? AND status_pengumpulan_skripsi IN ('diproses', 'ditolak', '')
    UNION ALL
    SELECT COUNT(*) AS total_status FROM publikasi_jurnal WHERE nim = ? AND status_pengumpulan_publikasi_jurnal IN ('diproses', 'ditolak', '')
) AS all_tables;
";

// Menyiapkan query untuk eksekusi dengan parameter $nim
$params = array_fill(0, 16, $nim); // Array untuk mengganti `nim` di semua bagian query
$result_status_user = sqlsrv_query($conn, $query_status_user, $params);

// Cek hasil query
if ($result_status_user === false) {
    die(print_r(sqlsrv_errors(), true)); // Cek error query
}

// Ambil hasil
$row_status_user = sqlsrv_fetch_array($result_status_user, SQLSRV_FETCH_ASSOC);
$total_status = $row_status_user['total_status'];

$result_terverifikasi = sqlsrv_query($conn, $query_terverifikasi);
if ($result_terverifikasi === false) {
    die(print_r(sqlsrv_errors(), true)); // Cek error query
}

$row_terverifikasi = sqlsrv_fetch_array($result_terverifikasi, SQLSRV_FETCH_ASSOC);
$total_terverifikasi = $row_terverifikasi['total_terverifikasi'];

// Query untuk total semua dokumen
$query_total = "
SELECT SUM(total_dokumen) AS total_dokumen
FROM (
    SELECT COUNT(*) AS total_dokumen FROM skkm
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM foto_ijazah
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM ukt
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM data_alumni
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM ta_softcopy
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM serahan_hardcopy
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM hasil_quesioner
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM bebas_pinjam_perpustakaan
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM bebas_kompen
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM kebenaran_data
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM serahan_pkl
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM serahan_skripsi
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM toeic
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM program_aplikasi
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM skripsi
    UNION ALL
    SELECT COUNT(*) AS total_dokumen FROM publikasi_jurnal
) AS all_tables;
";

$result_total = sqlsrv_query($conn, $query_total);
if ($result_total === false) {
    die(print_r(sqlsrv_errors(), true)); // Cek error query
}

$row_total = sqlsrv_fetch_array($result_total, SQLSRV_FETCH_ASSOC);
$total_dokumen = $row_total['total_dokumen'];

// Hitung persentase
if ($total_dokumen > 0) {
    $percentage = ($total_terverifikasi / $total_dokumen) * 100;
} else {
    $percentage = 0; // Jika total dokumen 0, set persentase ke 0
}

// Menampilkan hasil (contoh)


// Jangan lupa untuk menutup koneksi setelah selesai
sqlsrv_free_stmt($result_terverifikasi);
sqlsrv_free_stmt($result_total);
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
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .card-fixed-height {
            height: 200px;
        }

        a:hover .hover-success {
            color: #1CC88A !important;
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



                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>


                    <div class="container" style="margin-left: 15px; margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    PROSES TERVERIFIKASI</div>

                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                            <?php echo number_format($percentage, 2); ?>%
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="progress progress-sm mr-2">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                style="width: <?php echo $percentage; ?>%"
                                                                aria-valuenow="<?php echo $percentage; ?>"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Tanggungan</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php echo number_format($total_status); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Download Panduan
                                                </div>
                                                <div class="h10 mb-0 text-gray-300">sebelum melakukan upload tolong download panduan berikut</div>
                                            </div>
                                            <div class="col-auto">
                                                <!-- Wrap the icon with an anchor tag for download functionality -->
                                                <a href="../Documents/downloads/template/[Panduan] Alur Bebas Tanggungan Jurusan_SIB.docx" download>
                                                    <i class="fas fa-download fa-2x text-gray-300 hover-success"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>






                        </div>
                    </div>
                    <div class="row">
                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">

                            <!-- Project Card Example -->
                            <div class="container shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Proses</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Progress Bar for Prodi -->
                                    <h4 class="small font-weight-bold">Prodi <span class="float-right" id="prodi-progress-text">0%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" id="prodi-progress-bar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <!-- Progress Bar for Perpustakaan -->
                                    <h4 class="small font-weight-bold">Perpustakaan <span class="float-right" id="perpus-progress-text">0%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" id="perpus-progress-bar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <!-- Progress Bar for Jurusan -->
                                    <h4 class="small font-weight-bold">Jurusan <span class="float-right" id="jurusan-progress-text">0%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" id="jurusan-progress-bar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <!-- Progress Bar for Pusat -->
                                    <h4 class="small font-weight-bold">Pusat <span class="float-right" id="pusat-progress-text">0%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-success" role="progressbar" id="pusat-progress-bar" style="width: 0%"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

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

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../index.html">Logout</a>
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
    <!--<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script> -->


    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fungsi untuk memperbarui progress bar
        function updateProgressBar(category, barId, textId) {
            // Panggil data dari API
            $.getJSON(`data_chart.php?category=${category}`, function(data) {
                let total = Object.values(data).reduce((a, b) => a + b, 0); // Total semua status
                let verified = data['terverifikasi'] || 0; // Jumlah yang terverifikasi
                let percentage = Math.round((verified / total) * 100); // Hitung persentase

                // Update progress bar dan teks
                $(`#${barId}`).css('width', `${percentage}%`).attr('aria-valuenow', percentage);
                $(`#${textId}`).text(`${percentage}%`);
            });
        }

        // Jalankan saat dokumen siap
        $(document).ready(function() {
            // Perbarui masing-masing progress bar
            updateProgressBar('prodi', 'prodi-progress-bar', 'prodi-progress-text');
            updateProgressBar('perpustakaan', 'perpus-progress-bar', 'perpus-progress-text');
            updateProgressBar('jurusan', 'jurusan-progress-bar', 'jurusan-progress-text');
            updateProgressBar('pusat', 'pusat-progress-bar', 'pusat-progress-text');
        });
    </script>
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




</div>

</html>