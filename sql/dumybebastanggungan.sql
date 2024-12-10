use bebastanggungan;

INSERT INTO admin
    (id_adm, nip, nama_adm, no_telp_adm, alamat_adm, tgl_lahir_adm, jenis_kelamin_adm)
VALUES
('1','20230006', 'Safrilia', '081333213023', 'Jl. Merdeka No. 1, Jakarta', '1990-05-15', 'P'),
('2','20230007', 'Merry', '082247723596', 'Jl. Pahlawan No. 22, Bandung', '1988-12-20', 'P'),
('3','20230008', 'Ila', '081232245969', 'Jl. Mawar No. 10, Surabaya', '1992-08-11', 'P'),
('4','20230009', 'Widya Novy', '082232867789', 'Jl. Kenangan No. 5, Yogyakarta', '1995-03-25', 'P');


-- Dummy Data Mahasiswa


INSERT INTO mahasiswa (nim, nama_mhs, no_telp_mhs, jurusan_mhs, prodi_mhs, jenis_kelamin_mhs, tahun_angkatan_mhs, tgl_lahir_mhs) 
VALUES 
    ('2341760162', 'Boby Rozak Saputra', '089602168049', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'L', '2023', '2004-04-06'),
    ('2351760163', 'Jevon Khusnul Abidin', '089602168049', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'L', '2023', '2004-04-06'),
    ('2361760164', 'Ani Setiawati', '081234567890', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'P', '2023', '2004-03-15'),
    ('2024000001', 'Dedi Pratama', '089876543210', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'L', '2024', '2005-01-12'),
    ('2024000002', 'Rini Marlina', '087654321098', 'Teknologi Informasi', 'Teknik Informatika', 'P', '2024', '2005-06-25');

INSERT INTO mahasiswa (nim, nama_mhs, no_telp_mhs, jurusan_mhs, prodi_mhs, jenis_kelamin_mhs, tahun_angkatan_mhs, tgl_lahir_mhs)
VALUES
    ('2341760098', 'Firman Dzaki Rahman', '085174324054', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'L', '2023', '2005-05-24'),
    ('2341760036', 'Ismi Atika', '085259638605', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'P', '2023', '2005-06-26'),
    ('2341760086', 'Isnaeny Tri Larasati', '085826195940', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'P', '2023', '2004-07-02'),
    ('2341760101', 'Fransiska Widya Krisanti', '082138069699', 'Teknologi Informasi', 'Sistem Informasi Bisnis', 'P', '2023', '2004-07-02');
select * from mahasiswa;
INSERT INTO login (username, password, [position])
VALUES
('2341760162', '2341760162', 'mahasiswa'),
('2351760163', '2351760163', 'mahasiswa'),
('2361760164', '2361760164', 'mahasiswa'),
('2024000001', '2024000001', 'mahasiswa'),
('2024000002', '2024000002', 'mahasiswa');

--pusat

INSERT INTO skkm (status_pengumpulan_skkm, keterangan_pengumpulan_skkm, nim)
VALUES
('terverifikasi', 'SKKM diterima', '2341760162'),
('kosong', 'SKKM belum ada', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'SKKM ditolak', '2024000001'),
('terverifikasi', 'SKKM valid', '2024000002');

INSERT INTO foto_ijazah (status_pengumpulan_foto_ijazah, keterangan_pengumpulan_foto_ijazah, nim)
VALUES
('terverifikasi', 'Foto ijazah diterima', '2341760162'),
('kosong', 'Foto ijazah belum ada', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Foto ijazah valid', '2024000002');

INSERT INTO ukt (status_pengumpulan_ukt, keterangan_pengumpulan_ukt, nim)
VALUES
('terverifikasi', 'UKT diterima', '2341760162'),
('kosong', 'UKT belum ada', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Dokumen tidak valid', '2024000001'),
('terverifikasi', 'UKT valid', '2024000002');

INSERT INTO data_alumni (status_pengumpulan_data_alumni, keterangan_pengumpulan_data_alumni, nim)
VALUES
('terverifikasi', 'Data alumni lengkap', '2341760162'),
('kosong', 'Data alumni belum diunggah', '2351760163'),
('diproses', 'Proses validasi', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Data alumni valid', '2024000002');



-- perpustakaan

INSERT INTO ta_softcopy (status_pengumpulan_ta_softcopy, keterangan_pengumpulan_ta_softcopy, nim)
VALUES
('terverifikasi', 'Softcopy lengkap', '2341760162'),
('kosong', 'Softcopy belum ada', '2351760163'),
('diproses', 'Dalam proses', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Softcopy valid', '2024000002');

INSERT INTO serahan_hardcopy (status_pengumpulan_serahan_hardcopy, keterangan_pengumpulan_serahan_hardcopy, nim)
VALUES
('terverifikasi', 'Dokumen lengkap', '2341760162'),
('kosong', 'Dokumen belum diserahkan', '2351760163'),
('diproses', 'Dalam proses', '2361760164'),
('ditolak', 'Dokumen rusak', '2024000001'),
('terverifikasi', 'Dokumen valid', '2024000002');

INSERT INTO hasil_quesioner (status_pengumpulan_hasil_quesioner, keterangan_pengumpulan_hasil_quesioner, nim)
VALUES
('terverifikasi', 'Hasil lengkap', '2341760162'),
('kosong', 'Hasil belum ada', '2351760163'),
('diproses', 'Proses verifikasi', '2361760164'),
('ditolak', 'Hasil invalid', '2024000001'),
('terverifikasi', 'Hasil valid', '2024000002');


INSERT INTO bebas_pinjam_perpustakaan (status_pengumpulan_bebas_pinjam_perpustakaan, keterangan_pengumpulan_bebas_pinjam_perpustakaan, nim)
VALUES
('terverifikasi', 'Bebas pinjam perpustakaan valid', '2341760162'),
('kosong', 'Belum menyerahkan dokumen', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Dokumen tidak sesuai', '2024000001'),
('terverifikasi', 'Bebas pinjam valid', '2024000002');


-- prodi

INSERT INTO bebas_kompen (status_pengumpulan_bebas_kompen, keterangan_pengumpulan_bebas_kompen, nim)
VALUES
('terverifikasi', 'Bebas kompen diterima', '2341760162'),
('kosong', 'Belum menyerahkan dokumen', '2351760163'),
('diproses', 'Proses verifikasi', '2361760164'),
('ditolak', 'Dokumen tidak valid', '2024000001'),
('terverifikasi', 'Bebas kompen valid', '2024000002');

INSERT INTO kebenaran_data (status_pengumpulan_kebenaran_data, keterangan_pengumpulan_kebenaran_data, nim)
VALUES
('terverifikasi', 'Data benar', '2341760162'),
('kosong', 'Data belum diunggah', '2351760163'),
('diproses', 'Proses validasi', '2361760164'),
('ditolak', 'Data tidak sesuai', '2024000001'),
('terverifikasi', 'Data valid', '2024000002');

INSERT INTO serahan_pkl (status_pengumpulan_serahan_pkl, keterangan_pengumpulan_serahan_pkl, nim)
VALUES
('terverifikasi', 'PKL diterima', '2341760162'),
('kosong', 'PKL belum ada', '2351760163'),
('diproses', 'Proses verifikasi', '2361760164'),
('ditolak', 'Dokumen tidak sesuai', '2024000001'),
('terverifikasi', 'PKL valid', '2024000002');


INSERT INTO serahan_skripsi (status_pengumpulan_serahan_skripsi, keterangan_pengumpulan_serahan_skripsi, nim)
VALUES
('terverifikasi', 'Skripsi diterima', '2341760162'),
('kosong', 'Skripsi belum diunggah', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Skripsi valid', '2024000002');


INSERT INTO toeic (status_pengumpulan_toeic, keterangan_pengumpulan_toeic, nim)
VALUES
('terverifikasi', 'TOEIC diterima', '2341760162'),
('kosong', 'TOEIC belum ada', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Dokumen tidak valid', '2024000001'),
('terverifikasi', 'TOEIC valid', '2024000002');



--jurusan


INSERT INTO program_aplikasi (status_pengumpulan_program_aplikasi, keterangan_pengumpulan_program_aplikasi, nim)
VALUES
('terverifikasi', 'Aplikasi diterima', '2341760162'),
('kosong', 'Aplikasi belum ada', '2351760163'),
('diproses', 'Proses evaluasi', '2361760164'),
('ditolak', 'Aplikasi ditolak', '2024000001'),
('terverifikasi', 'Aplikasi valid', '2024000002');

INSERT INTO skripsi (status_pengumpulan_skripsi, keterangan_pengumpulan_skripsi, nim)
VALUES
('terverifikasi', 'Skripsi lengkap', '2341760162'),
('kosong', 'Skripsi belum ada', '2351760163'),
('diproses', 'Proses verifikasi', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Skripsi valid', '2024000002');

INSERT INTO publikasi_jurnal (status_pengumpulan_publikasi_jurnal, keterangan_pengumpulan_publikasi_jurnal, nim)
VALUES
('terverifikasi', 'Jurnal lengkap', '2341760162'),
('kosong', 'Jurnal belum ada', '2351760163'),
('diproses', 'Proses verifikasi', '2361760164'),
('ditolak', 'Format salah', '2024000001'),
('terverifikasi', 'Jurnal valid', '2024000002');



-- TAMBAH ADMIN 
INSERT into [login] (username, password, position) VALUES(
    '20000001', 'admin', 'a.pusat'
)
INSERT into .[login] (username, password, position) VALUES(
    '20000002', 'admin', 'a.jurusan'
)
INSERT into .[login] (username, password, position) VALUES(
    '20000003', 'admin', 'a.prodi'
)
INSERT into .[login] (username, password, position) VALUES(
    '20000004', 'admin', 'a.perpus'
)

SELECT * from .[login]

use bebastanggungan

INSERT INTO [dbo].[adminJurusan_konfirmasi] (nim, tanggal_adminJurusan_konfirmasi)
VALUES 
('2341760162', '2024-12-01'), 
('2351760163', '2024-12-02');

-- Insert data ke tabel adminlt7_konfirmasi
INSERT INTO [dbo].[adminProdi_konfirmasi] (nim, tanggal_adminProdi_konfirmasi)
VALUES 
('2341760162', '2024-12-01'), 
('2351760163', '2024-12-02');

-- Insert data ke tabel adminPusat_konfirmasi
INSERT INTO [dbo].[adminPusat_konfirmasi] (nim, tanggal_adminPusat_konfirmasi)
VALUES 
('2341760162', '2024-12-01'), 
('2351760163', '2024-12-02');

-- Insert data ke tabel adminPerpus_konfirmasi
INSERT INTO [dbo].[adminPerpus_konfirmasi] (nim, tanggal_adminPerpus_konfirmasi)
VALUES 
('2341760162', '2024-12-01'), 
('2351760163', '2024-12-02');


select * from toeic;

UPDATE ta_softcopy
SET [status_pengumpulan_ta_softcopy] = 'ditolak'
where NIM = '2341760162'
go

UPDATE serahan_pkl
SET [status_pengumpulan_serahan_pkl] = 'ditolak'
where NIM = '2341760162'
go

UPDATE bebas_kompen
SET [status_pengumpulan_bebas_kompen] = 'ditolak'
where NIM = '2341760162'
go

UPDATE toeic
SET [status_pengumpulan_toeic] = 'ditolak'
where NIM = '2341760162'
go

UPDATE kebenaran_data
SET [status_pengumpulan_kebenaran_data] = 'ditolak'
where NIM = '2341760162'
go

UPDATE bebas_pinjam_perpustakaan
SET [status_pengumpulan_bebas_pinjam_perpustakaan] = 'terverifikasi'
where NIM = '2341760162'
go
UPDATE publikasi_jurnal
SET [status_pengumpulan_publikasi_jurnal] = 'ditolak'
where NIM = '2341760162'
go



