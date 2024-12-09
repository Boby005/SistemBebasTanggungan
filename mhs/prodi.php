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
    <!-- <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- jQuery (from CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables (from CDN) -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> -->


    <style>
        .status span {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-weight: bold;
        }

        .status .badge-success {
            background-color: #1CC88A;
        }

        .status .badge-danger {
            background-color: #F6C23E;
        }

        #uploadModalHeader.bg-success {
            background-color: #1CC88A !important;
        }

        #uploadModalHeader.bg-danger {
            background-color: #E74A3B !important;
        }

        #uploadMessage {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d !important;
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
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800"></h1>


                    <!-- DataTables Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Prosess</h6>
                        </div>
                        <div class="card-body">
                            
                            <div class="table-responsive">

                                <div id="table"></div>

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
                        <span>Copyright &copy; Sistem Bebas Tanggungan 2024</span>
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
                            <small class="form-text text-muted">Hanya PDF: .doc saja</small>
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
                <!-- header -->
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
    <!--<script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script> -->


    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <!-- <script src="../js/demo/datatables-demo.js"></script> -->
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

        // Ajax untuk mengambil data dan menginisialisasi DataTables
        $(document).ready(function() {
            // Memuat data dari data.php
            $.ajax({
                url: 'data_prodi.php', // File PHP untuk memuat data
                type: 'GET', // Gunakan metode GET
                success: function(response) {
                    // Masukkan data ke dalam tabel
                    $('#table').html(`<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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

                    // Inisialisasi DataTables setelah data dimasukkan
                    $('#dataTable').DataTable({
                        "paging": true, // Menampilkan pagination
                        "searching": true, // Mengaktifkan  pencarian
                        "ordering": true, // sorting
                        "info": false // Menampilkan informasi jumlah data
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

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
                        // Tutup modal upload
                        $('#uploadModal').modal('hide');

                        // Tampilkan ikon dan teks berdasarkan respons
                        if (response.includes("berhasil")) {
                            $('#successIcon').show();
                            $('#errorIcon').hide();
                            $('#uploadModalHeader')
                                .removeClass('bg-danger')
                                .addClass('bg-success text-white');
                            $('#uploadMessage').css('color', '#155724'); // Hijau tua untuk teks
                        } else {
                            $('#successIcon').hide();
                            $('#errorIcon').show();
                            $('#uploadModalHeader')
                                .removeClass('bg-success')
                                .addClass('bg-danger text-white');
                            $('#uploadMessage').css('color', '#721c24'); // Merah tua untuk teks
                        }

                        // Tampilkan respons
                        $('#uploadMessage').html(response);

                        // Tampilkan modal status
                        $('#uploadModalStatus').modal('show');

                        loadTableData();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });

        function loadTableData() {
            $.ajax({
                url: 'data_prodi.php', // Endpoint untuk mengambil data tabel
                type: 'GET',
                success: function(data) {
                    // Perbarui isi tabel dengan data yang diterima
                    $('#table tbody').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading table data:', error);
                }
            });
        }

        function setUploadDir(directory) {
            $('#uploadDir').val(directory);
        }

        function updateFileName() {
            var fileName = document.getElementById('file').files[0]?.name || "No file chosen";
            document.getElementById('fileName').value = fileName;
        }


        document.addEventListener("DOMContentLoaded", function() {
            // Menangani perubahan input file
            const fileInput = document.getElementById("file");
            const fileNameInput = document.getElementById("fileName");

            fileInput.addEventListener("change", function() {
                const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : "No file chosen";
                fileNameInput.value = fileName;
            });
        });


        // Keterangan pada verifikasi

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
                        keterangan.value = ""; // Clear the textarea
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
                    // Lakukan tindakan simpan (AJAX atau proses lainnya)
                    alert('Data berhasil disimpan!');
                    $('#uploadModal').modal('hide');
                }
            });
        });
    </script>

</body>

</html>