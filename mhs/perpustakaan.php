<?php
include '../koneksi.php';

if (!isset($_COOKIE['id'])) {
    header("Location: ../index.html");
    exit();
}

$nim = $_COOKIE['id'];

$query = "
    SELECT 
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_serahan_hardcopy = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS serahan_hardcopy,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_ta_softcopy = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS ta_softcopy,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_bebas_pinjam_perpustakaan = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS bebas_pinjam_perpustakaan,
        (CASE WHEN MIN(CASE WHEN status_pengumpulan_hasil_quesioner = 'terverifikasi' THEN 1 ELSE 0 END) = 1 THEN 1 ELSE 0 END) AS hasil_quesioner
    FROM dbo.serahan_hardcopy
    LEFT JOIN dbo.ta_softcopy ON serahan_hardcopy.nim = ta_softcopy.nim
    LEFT JOIN dbo.bebas_pinjam_perpustakaan ON serahan_hardcopy.nim = bebas_pinjam_perpustakaan.nim
    LEFT JOIN dbo.hasil_quesioner ON serahan_hardcopy.nim = hasil_quesioner.nim
    WHERE serahan_hardcopy.nim = ?
";

$params = array($nim);
$stmt = sqlsrv_prepare($conn, $query, $params);
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

$result = sqlsrv_execute($stmt);
if (!$result) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if ($row === false) {
    die("Gagal mengambil data");
}

// Mengecek apakah semua status sudah terkonfirmasi
$allConfirmed = $row['serahan_hardcopy'] && $row['ta_softcopy'] && $row['bebas_pinjam_perpustakaan'] && $row['hasil_quesioner'];
sqlsrv_free_stmt($stmt);

//CEK APAKAH NOMOR SURAT SUDAH ADA
$cekSurat = "select * from dbo.nomor_surat where nim = ? and nama_surat = 'bebas tanggungan perpus'";
$paramsCek = array($nim);
$resultCek = sqlsrv_query($conn, $cekSurat, $paramsCek);

if ($resultCek === false) {
    die("Gagal menjalankan query");
}

if ($rowCek = sqlsrv_fetch_array($resultCek, SQLSRV_FETCH_ASSOC)) {
    // ...
} else {
    $sqlNomorSurat = "EXEC InsertSurat @nama_surat = 'bebas tanggungan perpus',
            @nim = ?";
    $paramsNomor = array($nim);
    $stmtNomor = sqlsrv_query($conn, $sqlNomorSurat, $paramsNomor);

    sqlsrv_free_stmt($stmtNomor);
}

$sql = "SELECT m.nim, m.nama_mhs, m.jurusan_mhs, m.prodi_mhs, ns.nomor_surat, ak.tanggal_adminPerpus_konfirmasi
            FROM dbo.mahasiswa m
            join dbo.nomor_surat ns on m.nim = ns.nim
            join dbo.adminPerpus_konfirmasi ak on m.nim = ak.nim
            WHERE m.nim = ? AND ns.nama_surat = 'bebas tanggungan perpus'";

$params = array($nim);
$result = sqlsrv_query($conn, $sql, $params);

if ($result === false) {
    die("Kesalahan saat menjalankan query: " . print_r(sqlsrv_errors(), true));
}

$nama_mahasiswa = "";

// Ambil data dan cek apakah ada
if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $nama_mahasiswa = $row['nama_mhs'];
    $nim = $row['nim'];
    $jurusan = $row['jurusan_mhs'];
    $prodi = $row['prodi_mhs'];
    $nomor_surat = $row['nomor_surat'];
    $tanggal = $row['tanggal_adminPerpus_konfirmasi'];
    $tanggalString = $tanggal->format('d-m-Y');
    $tahun = $tanggal->format('Y');
}

// Fungsi untuk membebaskan sumber daya yang digunakan oleh statement query
sqlsrv_free_stmt($result);

// Tutup koneksi
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

    <script type="module">
        import {
            PDFDocument,
            rgb
        } from 'https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/+esm';

        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('downloadButton');

            // Hanya jalankan jika tombol tidak memiliki atribut 'disabled'
            if (!button.hasAttribute('disabled')) {
                button.addEventListener('click', async () => {
                    try {
                        // Fungsi untuk mendapatkan cookie
                        function getCookie(name) {
                            const nameEQ = name + "=";
                            const cookies = document.cookie.split("; ");
                            for (let i = 0; i < cookies.length; i++) {
                                const c = cookies[i];
                                if (c.indexOf(nameEQ) === 0) {
                                    return c.substring(nameEQ.length, c.length);
                                }
                            }
                            return null;
                        }

                        const username = getCookie('id');
                        const pdfPath = '../Documents/downloads/generate/Bebas_Tanggungan_Perpustakaan_Grapol.pdf';
                        const fontPath = './TimesNewRoman/TimesNewRoman.ttf';

                        // Muat PDF
                        const pdfResponse = await fetch(pdfPath);
                        if (!pdfResponse.ok) throw new Error(`Could not load PDF: ${pdfResponse.statusText}`);
                        const pdfArrayBuffer = await pdfResponse.arrayBuffer();
                        const pdfDoc = await PDFDocument.load(pdfArrayBuffer);

                        // Registrasikan fontkit
                        pdfDoc.registerFontkit(window.fontkit);

                        // Muat font kustom
                        const fontResponse = await fetch(fontPath);
                        if (!fontResponse.ok) throw new Error(`Could not load font: ${fontResponse.statusText}`);
                        const fontArrayBuffer = await fontResponse.arrayBuffer();
                        const timesFont = await pdfDoc.embedFont(fontArrayBuffer);

                        // Tambahkan teks ke halaman pertama
                        const pages = pdfDoc.getPages();
                        const firstPage = pages[0];
                        const nama = "<?php echo htmlspecialchars($nama_mahasiswa, ENT_QUOTES, 'UTF-8'); ?>";
                        const nim = "<?php echo htmlspecialchars($nim, ENT_QUOTES, 'UTF-8'); ?>";
                        const jurusan = "<?php echo htmlspecialchars($jurusan, ENT_QUOTES, 'UTF-8'); ?>";
                        const prodi = "<?php echo htmlspecialchars($prodi, ENT_QUOTES, 'UTF-8'); ?>";
                        const nomor_surat = "<?php echo htmlspecialchars($nomor_surat, ENT_QUOTES, 'UTF-8'); ?>";
                        const tanggal = "<?php echo htmlspecialchars($tanggalString, ENT_QUOTES, 'UTF-8'); ?>";
                        const tahun = "<?php echo htmlspecialchars($tahun, ENT_QUOTES, 'UTF-8'); ?>";

                        firstPage.drawText(`${nama}`, {
                            x: 190, // Ganti dengan koordinat X yang sesuai
                            y: 612.7, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });
                        firstPage.drawText(`${nim}`, {
                            x: 190, // Ganti dengan koordinat X yang sesuai
                            y: 596.6, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });
                        firstPage.drawText(`${jurusan}`, {
                            x: 190, // Ganti dengan koordinat X yang sesuai
                            y: 580.5, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });
                        firstPage.drawText(`${prodi}`, {
                            x: 190, // Ganti dengan koordinat X yang sesuai
                            y: 564.4, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });
                        firstPage.drawText(`${nomor_surat}/PL.2.UPA.PERPUS/${tahun}`, {
                            x: 215, // Ganti dengan koordinat X yang sesuai
                            y: 660.5, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });
                        firstPage.drawText(`${tanggal}`, {
                            x: 437, // Ganti dengan koordinat X yang sesuai
                            y: 324.5, // Ganti dengan koordinat Y yang sesuai
                            size: 12,
                            font: timesFont,
                            color: rgb(0, 0, 0),
                        });

                        // Simpan PDF baru
                        const modifiedPdf = await pdfDoc.save();
                        const blob = new Blob([modifiedPdf], {
                            type: 'application/pdf'
                        });

                        // Unduh PDF yang sudah diedit
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = `${nim}_Bebas_Tanggungan_Perpustakaan_Grapol.pdf`;
                        link.click();
                    } catch (error) {
                        console.error('Terjadi error:', error);
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@pdf-lib/fontkit@0.0.4/dist/fontkit.umd.min.js"></script>

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
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Verifikasi Berkas Perpustakaan Polinema (Grapol)</h1>
                    <p class="mb-4">Verifikasi berkas pada perpustakaan Polinema (Grapol lantai 3) yang akan
                        diverifikasi oleh ibu Safrilia (<a target="_blank"
                            href="https://wa.me/6281333213023">081333213023</a> - <i>Chat Only</i>) </p>

                    <!-- DataTables Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Berkas Yang Perlu Diunggah</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="table"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Download Dokumen -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Download Template Dokumen</h6>
                        </div>
                        <div class="card-body">
                            <p>Unduh template dokumen yang disediakan (sesuaikan dengan kebutuhan verifikasi), isi
                                sesuai petunjuk, lalu unggah untuk
                                proses verifikasi.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dokumenTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Dokumen</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Panduan Alur Bebas Tanggungan Perpustakaan Grapol</td>
                                            <td>-</td>
                                            <td><a href="../Documents/downloads/template/[Panduan] Alur Bebas Tanggungan Perpustakaan Pusat.pdf"
                                                    class="btn btn-success" download><i class="fas fa-download"></i>
                                                    Download</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card Bebas Tanggungan -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bebas Tanggungan Perpustakaan Polinema</h6>
                        </div>
                        <div class="card-body">
                            <p>Surat ini meliputi Bebas Tanggungan Perpustakaan Polinema.</p>
                            <?php if ($allConfirmed): ?>
                                <button class="btn btn-success" id="downloadButton">
                                    <i class="fas fa-download"></i> Download</button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled><i class="fas fa-download"></i> Disable</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- /.container-fluid -->


                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->



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
                        <a class="btn btn-primary" href="../index.html">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Upload -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header bg-primary text-white d-flex justify-content-center align-items-center">
                        <h5 class="modal-title" id="uploadModalLabel">Add Documents</h5>
                        <button type="button" class="close text-white ml-auto" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <form id="uploadForm" method="post" enctype="multipart/form-data">
                            <!-- Hidden Input for uploadDir -->
                            <input type="hidden" name="uploadDir" id="uploadDir">

                            <!-- File Upload Input -->
                            <div class="form-group">
                                <label for="file" class="col-form-label">Attachments:</label>
                                <div class="file-upload-box text-center border rounded p-4">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                                    <p class="text-muted mt-2">
                                        Attach your files here <br> or <br>
                                        <label for="file" class="text-primary" style="cursor: pointer;">Browse files</label>
                                    </p>
                                    <input type="file" class="form-control-file d-none" id="file" name="file" required
                                        onchange="updateFileName()">
                                </div>
                                <small class="form-text text-muted">Accepted file type: .doc only</small>
                            </div>

                            <!-- Preview Filename -->
                            <div class="form-group">
                                <label for="fileName" class="col-form-label">Selected File:</label>
                                <input type="text" class="form-control" id="fileName" placeholder="No file chosen" readonly>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Status Upload -->
        <div class="modal fade" id="uploadModalStatus" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" id="uploadModalHeader">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Status</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-check-circle fa-3x text-success" id="successIcon" style="display: none;"></i>
                        <i class="fas fa-times-circle fa-3x text-danger" id="errorIcon" style="display: none;"></i>
                        <p id="uploadMessage" class="mt-3"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            // Fungsi untuk memuat elemen navbar dan topbar
            function loadNavbarAndTopbar() {
                fetch('navbar.html')
                    .then(response => {
                        console.log('Navbar fetch status:', response.status);
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
                    .then(response => {
                        console.log('Topbar fetch status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(data => {
                        document.getElementById('topbar').innerHTML = data;
                    })
                    .catch(error => console.error('Error loading topbar:', error));
            }

            // Fungsi untuk memuat data tabel dan menginisialisasi DataTables
            function loadTableData() {
                $.ajax({
                    url: 'data_perpus.php', // Endpoint untuk mengambil data tabel
                    type: 'GET',
                    success: function(response) {
                        $('#table').html(`
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                </table>
            `);

                        // Inisialisasi DataTables setelah data dimasukkan
                        $('#dataTable').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading table data:', error);
                    }
                });
            }

            // Fungsi untuk menangani form upload
            function handleUploadForm() {
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
                            $('#uploadModal').modal('hide');

                            if (response.includes("berhasil")) {
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

                            $('#uploadMessage').html(response);
                            $('#uploadModalStatus').modal('show');
                            loadTableData();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error uploading file:', error);
                        }
                    });
                });
            }

            // Fungsi untuk mengatur direktori upload
            function setUploadDir(directory) {
                $('#uploadDir').val(directory);
            }

            // Fungsi untuk memperbarui nama file yang diunggah
            function updateFileName() {
                const fileInput = document.getElementById('file');
                const fileNameInput = document.getElementById('fileName');

                fileInput.addEventListener('change', function() {
                    const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : "No file chosen";
                    fileNameInput.value = fileName;
                });
            }

            // Fungsi untuk menangani perubahan status verifikasi
            function handleVerification() {
                const verifikasiTrue = document.getElementById('verifikasiTrue');
                const verifikasiFalse = document.getElementById('verifikasiFalse');
                const keterangan = document.getElementById('keterangan');
                const keteranganError = document.getElementById('keteranganError');
                const saveButton = document.getElementById('saveVerification');

                [verifikasiTrue, verifikasiFalse].forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (verifikasiTrue.checked) {
                            keterangan.disabled = true;
                            keterangan.value = "";
                            keteranganError.style.display = "none";
                        } else if (verifikasiFalse.checked) {
                            keterangan.disabled = false;
                        }
                    });
                });

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
            }

            // Panggil fungsi saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                loadNavbarAndTopbar();
                loadTableData();
                handleUploadForm();
                updateFileName();
                handleVerification();
            });
        </script>
</body>

</html>