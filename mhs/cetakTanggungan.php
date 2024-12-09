<?php
include '../koneksi.php';

if (!isset($_COOKIE['id'])) {
    header("Location: ../index.html");
    exit();
}
$sql = "SELECT m.nim, m.nama_mhs, m.jurusan_mhs, m.prodi_mhs
            FROM dbo.mahasiswa m
            WHERE m.nim = ?";

$params = array($nim);
$result = sqlsrv_query($conn, $sql, $params);

if ($result === false) {
    die("Kesalahan saat menjalankan query: " . print_r(sqlsrv_errors(), true));
}

$nama_mahasiswa = "";

// Ambil data dan cek apakah ada
if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $nama_mahasiswa = $row['nama_mahasiswa'];
    $nim = $row['nim'];
    $jurusan = $row['jurusan_mahasiswa'];
    $prodi = $row['prodi_mahasiswa'];
}

$nim = $_COOKIE['id'];

// Query untuk jurusan
$queryJurusan = "
    SELECT 
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_publikasi_jurnal = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS publikasi_jurnal,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_program_aplikasi = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS program_aplikasi,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_skripsi = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS status_skripsi
    FROM dbo.publikasi_jurnal
    LEFT JOIN dbo.program_aplikasi ON publikasi_jurnal.nim = program_aplikasi.nim
    LEFT JOIN dbo.skripsi ON publikasi_jurnal.nim = skripsi.nim
    WHERE publikasi_jurnal.nim = '$nim'
";

// Query untuk prodi
$queryProdi = "
    SELECT 
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_serahan_skripsi = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS skripsi,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_serahan_pkl = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS pkl,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_toeic = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS toeic,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_bebas_kompen = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS bebas_kompen
    FROM dbo.serahan_skripsi
    LEFT JOIN dbo.serahan_pkl ON serahan_skripsi.nim = serahan_pkl.nim      
    LEFT JOIN dbo.toeic ON serahan_skripsi.nim = toeic.nim      
    LEFT JOIN dbo.bebas_kompen ON serahan_skripsi.nim = bebas_kompen.nim  
    WHERE serahan_skripsi.nim = '$nim'
    ";

// Query untuk perpustakaan
$queryPerpustakaan = "
    SELECT 
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_serahan_hardcopy = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS serahan_hardcopy,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_ta_softcopy = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS ta_softcopy,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_bebas_pinjam_perpustakaan = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS bebas_pinjam_perpustakaan,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_hasil_quesioner = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS hasil_quesioner
    FROM dbo.serahan_hardcopy
    LEFT JOIN dbo.ta_softcopy ON serahan_hardcopy.nim = ta_softcopy.nim
    LEFT JOIN dbo.bebas_pinjam_perpustakaan ON serahan_hardcopy.nim = bebas_pinjam_perpustakaan.nim
    LEFT JOIN dbo.hasil_quesioner ON serahan_hardcopy.nim = hasil_quesioner.nim
    WHERE serahan_hardcopy.nim = '$nim'
";

// Query untuk akademik pusat
$queryAkademik = "
    SELECT 
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_data_alumni = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS data_alumni,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_skkm = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS skkm,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_foto_ijazah = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS foto_ijazah,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_ukt = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS ukt
    FROM dbo.data_alumni
    LEFT JOIN dbo.skkm ON data_alumni.nim = skkm.nim
    LEFT JOIN dbo.foto_ijazah ON data_alumni.nim = foto_ijazah.nim
    LEFT JOIN dbo.ukt ON data_alumni.nim = ukt.nim
    WHERE data_alumni.nim = '$nim'
";

// Eksekusi query dan validasi hasil
function executeQuery($conn, $query)
{
    $stmt = sqlsrv_query($conn, $query);
    if (!$stmt) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    return $row;
}
// Menggabungkan proses jurusan dan prodi
$rowJurusan = executeQuery($conn, $queryJurusan);
$rowProdi = executeQuery($conn, $queryProdi);

// Konfirmasi gabungan jurusan dan prodi
$jurusanProdiConfirmed = $rowJurusan['publikasi_jurnal'] &&
    $rowJurusan['program_aplikasi'] &&
    $rowJurusan['status_skripsi'] &&
    $rowProdi['serahan_skripsi'] &&
    $rowProdi['serahan_pkl'] &&
    $rowProdi['toeic'] &&
    $rowProdi['bebas_kompen'];

// Konfirmasi perpustakaan
$rowPerpustakaan = executeQuery($conn, $queryPerpustakaan);
$perpustakaanConfirmed = $rowPerpustakaan['serahan_hardcopy'] &&
    $rowPerpustakaan['ta_softcopy'] &&
    $rowPerpustakaan['bebas_pinjam_perpustakaan'] &&
    $rowPerpustakaan['hasil_quesioner'];

// Konfirmasi akademik
$rowAkademik = executeQuery($conn, $queryAkademik);
$akademikConfirmed = $rowAkademik['data_alumni'] &&
    $rowAkademik['skkm'] &&
    $rowAkademik['foto_ijazah'] &&
    $rowAkademik['ukt'];

$tanggalProdi = null;
$tanggalJurusan = null;

$sqlTanggal = "SELECT tanggal_adminProdi_konfirmasi FROM dbo.adminProdi_konfirmasi WHERE nim = ?";
$resultTanggal = sqlsrv_query($conn, $sqlTanggal, $params);

if ($resultTanggal === false) {
    die("Kesalahan saat menjalankan query tanggal: " . print_r(sqlsrv_errors(), true));
}

// Mengecek apakah tanggal hanya diambil jika semua status sudah terkonfirmasi
$tanggalProdi = null;
$tanggalJurusan = null;

if ($jurusanProdiConfirmed) {
    // Query untuk mengambil tanggal konfirmasi dari tabel adminProdi_konfirmasi
    $sqlTanggalProdi = "SELECT tanggal_adminProdi_konfirmasi FROM dbo.adminProdi_konfirmasi WHERE nim = ?";
    $resultTanggalProdi = sqlsrv_query($conn, $sqlTanggalProdi, $params);

    if ($resultTanggalProdi === false) {
        die("Kesalahan saat menjalankan query tanggal: " . print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($resultTanggal, SQLSRV_FETCH_ASSOC)) {
        $tanggalProdi = $row['tanggal_adminProdi_konfirmasi'];
    }

    // Jika $tanggal adalah objek DateTime, ubah menjadi string
    if ($tanggalProdi instanceof DateTime) {
        $tanggalProdi = $tanggalProdi->format('Y-m-d');  // Atur format sesuai kebutuhan
    }

    // Gunakan htmlspecialchars untuk mencegah XSS
    $tanggalProdi = htmlspecialchars($tanggalProdi, ENT_QUOTES, 'UTF-8');

    sqlsrv_free_stmt($resultTanggalProdi);

    $sqltanggalJurusan = "SELECT tanggal_adminJurusan_konfirmasi FROM dbo.adminJurusan_konfirmasi WHERE nim = ?";
    $resulttanggalJurusan = sqlsrv_query($conn, $sqltanggalJurusan, $params);

    if ($resulttanggalJurusan === false) {
        die("Kesalahan saat menjalankan query tanggal: " . print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($resulttanggalJurusan, SQLSRV_FETCH_ASSOC)) {
        $tanggalJurusan = $row['tanggal_adminJurusan_konfirmasi'];
    }

    // Jika $tanggal adalah objek DateTime, ubah menjadi string
    if ($tanggalJurusan instanceof DateTime) {
        $tanggalJurusan = $tanggalJurusan->format('Y-m-d');  // Atur format sesuai kebutuhan
    }

    // Gunakan htmlspecialchars untuk mencegah XSS
    $tanggalJurusan = htmlspecialchars($tanggalJurusan, ENT_QUOTES, 'UTF-8');

    sqlsrv_free_stmt($resulttanggalJurusan);
}

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

    <title>Verifikasi</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- DataTables -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- jQuery (from CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables (from CDN) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script type="module">
        import {
            PDFDocument,
            rgb
        } from 'https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/+esm';

        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('downloadButton');

            button.addEventListener('click', async () => {
                // Fungsi untuk mengambil nilai cookie
                function getCookie(name) {
                    let cookieArr = document.cookie.split(";");
                    for (let i = 0; i < cookieArr.length; i++) {
                        let cookie = cookieArr[i].trim();
                        if (cookie.indexOf(name + "=") == 0) {
                            return cookie.substring(name.length + 1);
                        }
                    }
                    return "";
                }

                const username = getCookie('id'); // Ambil cookie 'id' (jika ada)
                const pdfPath = '../Documents/downloads/generate/Bebas_Tanggungan_Jurusan.pdf'; // Path ke file PDF
                const fontPath = './TimesNewRoman/TimesNewRoman.ttf'; // Path ke font kustom

                try {
                    // Muat PDF
                    const pdfResponse = await fetch(pdfPath);
                    if (!pdfResponse.ok) {
                        console.error('Failed to load PDF:', pdfResponse.statusText);
                        return;
                    }

                    const pdfArrayBuffer = await pdfResponse.arrayBuffer();
                    const pdfDoc = await PDFDocument.load(pdfArrayBuffer);

                    // Registrasikan fontkit (periksa apakah window.fontkit ada)
                    if (typeof window.fontkit === 'undefined') {
                        console.error('fontkit is not available!');
                        return;
                    }
                    pdfDoc.registerFontkit(window.fontkit);

                    // Muat font kustom
                    const fontResponse = await fetch(fontPath);
                    if (!fontResponse.ok) {
                        console.error('Failed to load font:', fontResponse.statusText);
                        return;
                    }

                    const fontArrayBuffer = await fontResponse.arrayBuffer();
                    const timesFont = await pdfDoc.embedFont(fontArrayBuffer);

                    // Ambil data dari PHP untuk dimasukkan ke PDF
                    const nama = "<?php echo htmlspecialchars($nama_mahasiswa, ENT_QUOTES, 'UTF-8'); ?>";
                    const nim = "<?php echo htmlspecialchars($nim, ENT_QUOTES, 'UTF-8'); ?>";
                    const prodi = "<?php echo htmlspecialchars($prodi, ENT_QUOTES, 'UTF-8'); ?>";
                    const tanggalLt7 = "<?php echo htmlspecialchars($tanggalProdi, ENT_QUOTES, 'UTF-8'); ?>";
                    const tanggalJurusan = "<?php echo htmlspecialchars($tanggalJurusan, ENT_QUOTES, 'UTF-8'); ?>";

                    // Pastikan data PHP sudah terisi
                    if (!nama || !nim || !prodi || !tanggalLt7 || !tanggalJurusan) {
                        console.error('Some PHP variables are not properly set');
                        return;
                    }

                    // Tambahkan teks ke halaman pertama
                    const pages = pdfDoc.getPages();
                    const firstPage = pages[0];

                    // Tentukan posisi teks untuk setiap field
                    firstPage.drawText(`${nama}`, {
                        x: 190, // Koordinat X
                        y: 626, // Koordinat Y
                        size: 12,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    firstPage.drawText(`${nim}`, {
                        x: 190, // Koordinat X
                        y: 605, // Koordinat Y
                        size: 12,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    firstPage.drawText(`${prodi}`, {
                        x: 190, // Koordinat X
                        y: 584, // Koordinat Y
                        size: 12,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    firstPage.drawText(`${tanggalLt7}`, {
                        x: 296, // Koordinat X
                        y: 511, // Koordinat Y
                        size: 9,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    firstPage.drawText(`${tanggalJurusan}`, {
                        x: 296, // Koordinat X
                        y: 413, // Koordinat Y
                        size: 9,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    // TTD penanggung Jawab (Ketua Prodi)
                    firstPage.drawText(`${tanggalJurusan}`, {
                        x: 462, // Koordinat X
                        y: 474, // Koordinat Y
                        size: 9,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    // TTD Ketua Jurusan
                    firstPage.drawText(`${tanggalJurusan}`, {
                        x: 332, // Koordinat X
                        y: 298, // Koordinat Y
                        size: 12,
                        font: timesFont,
                        color: rgb(0, 0, 0),
                    });

                    // Unduh PDF yang telah dimodifikasi
                    const pdfBytes = await pdfDoc.save();
                    const blob = new Blob([pdfBytes], {
                        type: 'application/pdf'
                    });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `${nim}_Bebas_Tanggungan_Jurusan.pdf`;
                    link.click();
                } catch (error) {
                    console.error('Error generating PDF:', error);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@pdf-lib/fontkit@0.0.4/dist/fontkit.umd.min.js"></script>

    <style>
        .status span {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-weight: bold;
        }

        .status .badge-success {
            background-color: green;
        }

        .status .badge-danger {
            background-color: red;
        }

        #uploadModalHeader.bg-success {
            background-color: #28a745 !important;
            /* Hijau terang */
        }

        #uploadModalHeader.bg-danger {
            background-color: #dc3545 !important;
            /* Merah terang */
        }

        #uploadMessage {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d !important;
            /* Abu-abu lebih terang */
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


                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Rekomendasi Pengambilan Ijazah</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-gray-600">Untuk mengambil Transkrip, Ijazah, dan SKPI, pastikan Anda
                                sudah memenuhi
                                seluruh persyaratan yang diperlukan. Jika sudah,
                                silakan unduh Surat Rekomendasi Pengambilan Transkrip, Ijazah, dan SKPI di bawah
                                ini.</p>

                            <!-- Download Button -->
                            <button class="btn btn-block" id="downloadButton" disabled>
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>

                    <!-- Card Bebas Tanggungan -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bebas Tanggungan Jurusan dan Prodi</h6>
                        </div>
                        <div class="card-body">
                            <p>Meliputi proses jurusan serta prodi</p>
                            <?php if ($jurusanProdiConfirmed): ?>
                                <button class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Surat
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    Belum Terverifikasi
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bebas Tanggungan Perpustakaan -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bebas Tanggungan Perpustakaan</h6>
                        </div>
                        <div class="card-body">
                            <p>Meliputi proses perpustakaan</p>
                            <?php if ($perpustakaanConfirmed): ?>
                                <button class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Surat
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    Belum Terverifikasi
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bebas Tanggungan Akademik -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bebas Tanggungan Akademik Pusat</h6>
                        </div>
                        <div class="card-body">
                            <p>Meliputi proses akademik</p>
                            <?php if ($akademikConfirmed): ?>
                                <button class="btn btn-success">
                                    <i class="fas fa-download"></i> Download Surat
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    Belum Terverifikasi
                                </button>
                            <?php endif; ?>



</body>

</html>
</div>
<div class="row">

    <!-- Content Column -->


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

<!-- Logout Modal-->
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

    function setUploadDir(directory) {
        $('#uploadDir').val(directory);
    }

    // Mengambil dan menampilkan navbar dan topbar
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

        fetch('topbar.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('topbar').innerHTML = data;
            })
            .catch(error => console.error('Error loading topbar:', error));
    });

    // Memuat data untuk DataTables
    $(document).ready(function() {
        // Memuat data untuk Pusat
        loadDataTable('data_akademik.php', '#tablePusat');
        // Memuat data untuk Perpustakaan
        loadDataTable('data_perpus.php', '#tablePerpus');
    });

    function loadDataTable(url, tableId) {
        $.ajax({
            url: url, // URL endpoint data
            type: 'GET',
            success: function(response) {
                // Masukkan data ke dalam tabel
                $(tableId).html(`<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Berkas</th>
                            <th>Status Pengumpulan</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>${response}</tbody>
                </table>`);

                // Inisialisasi DataTables
                $(tableId + ' table').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading table data:', error);
            }
        });
    }

    // Fungsi upload form dengan validasi
    $(document).ready(function() {
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'upload.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#uploadModal').modal('hide'); // Tutup modal upload

                    // Respons dan status
                    if (response.includes("berhasil")) {
                        showUploadStatus(true, response);
                    } else {
                        showUploadStatus(false, response);
                    }
                    loadTableData(); // Perbarui tabel
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });

    function showUploadStatus(success, message) {
        if (success) {
            $('#successIcon').show();
            $('#errorIcon').hide();
            $('#uploadModalHeader')
                .removeClass('bg-danger')
                .addClass('bg-success text-white');
            $('#uploadMessage').css('color', '#155724');
        } else {
            $('#successIcon').hide();
            $('#errorIcon').show();
            $('#uploadModalHeader')
                .removeClass('bg-success')
                .addClass('bg-danger text-white');
            $('#uploadMessage').css('color', '#721c24');
        }
        $('#uploadMessage').html(message);
        $('#uploadModalStatus').modal('show'); // Tampilkan modal status
    }

    function loadTableData() {
        loadDataTable('data_pusat.php', '#tablePusat'); // Pusat
        loadDataTable('data_grapol.php', '#tablePerpus'); // Perpustakaan
    }

    function setUploadDir(directory) {
        $('#uploadDir').val(directory);
    }

    function updateFileName() {
        var fileName = document.getElementById('file').files[0]?.name || "No file chosen";
        document.getElementById('fileName').value = fileName;
    }

    // Validasi verifikasi
    document.addEventListener('DOMContentLoaded', () => {
        const verifikasiTrue = document.getElementById('verifikasiTrue');
        const verifikasiFalse = document.getElementById('verifikasiFalse');
        const keterangan = document.getElementById('keterangan');
        const keteranganError = document.getElementById('keteranganError');
        const saveButton = document.getElementById('saveVerification');

        // Event listener untuk radio buttons
        [verifikasiTrue, verifikasiFalse].forEach(radio => {
            radio.addEventListener('change', () => {
                if (verifikasiTrue.checked) {
                    keterangan.disabled = true;
                    keterangan.value = ""; // Clear textarea
                    keteranganError.style.display = "none";
                } else if (verifikasiFalse.checked) {
                    keterangan.disabled = false;
                }
            });
        });

        // Validasi sebelum menyimpan
        saveButton.addEventListener('click', () => {
            if (verifikasiFalse.checked && keterangan.value.trim() === "") {
                keteranganError.style.display = "block";
                keterangan.focus();
            } else {
                keteranganError.style.display = "none";
                alert('Data berhasil disimpan!');
                $('#uploadModal').modal('hide');
            }
        });
    });
</script>

</body>

</html>










<!-- Content Row -->