create database bebastanggungan;

use bebastanggungan;


-- MAHASISWA


CREATE TABLE mahasiswa
(
    nim NVARCHAR (10) NOT NULL,
    nama_mhs NVARCHAR (50) NOT NULL,
    no_telp_mhs NVARCHAR (20) NULL,
    jurusan_mhs NVARCHAR (20) NULL,
    prodi_mhs NVARCHAR (20) NULL,
    jenis_kelamin_mhs CHAR(1) CHECK (jenis_kelamin_mhs IN ('L', 'P')),
    tahun_angkatan_mhs DATE NULL,
    tgl_lahir_mhs DATE NULL,
    CONSTRAINT PK_mhs PRIMARY KEY CLUSTERED (nim ASC)
);

-- ADMIN

CREATE TABLE admin
(
    id_adm NVARCHAR (10) NOT NULL,
    nip NVARCHAR (10) NOT NULL,
    nama_adm NVARCHAR (50) NOT NULL,
    no_telp_adm NVARCHAR (20) NULL,
    alamat_adm NVARCHAR (50) NULL,
    tgl_lahir_adm DATE NULL,
    jenis_kelamin_adm CHAR(1) CHECK (jenis_kelamin_adm IN ('L', 'P')),
    CONSTRAINT PK_adm PRIMARY KEY CLUSTERED (id_adm ASC)
);
ALTER TABLE admin DROP COLUMN ttd_adm;
ALTER TABLE admin ALTER COLUMN ttd_adm VARCHAR(MAX);



DELETE FROM admin
WHERE id_adm = '1';
DELETE FROM admin
WHERE id_adm = '2';
DELETE FROM admin
WHERE id_adm = '3';

select * from admin;





-- PUSAT


CREATE TABLE skkm
(
    id_skkm INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_skkm NVARCHAR(50) CHECK (status_pengumpulan_skkm IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_skkm NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_skkm PRIMARY KEY CLUSTERED (id_skkm ASC)
);

CREATE TABLE foto_ijazah
(
    id_foto_ijazah INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_foto_ijazah NVARCHAR(50) CHECK (status_pengumpulan_foto_ijazah IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_foto_ijazah NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_foto_ijazah PRIMARY KEY CLUSTERED (id_foto_ijazah ASC)
);

CREATE TABLE ukt
(
    id_ukt INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_ukt NVARCHAR(50) CHECK (status_pengumpulan_ukt IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_ukt NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_ukt PRIMARY KEY CLUSTERED (id_ukt ASC)
);

CREATE TABLE data_alumni
(
    id_data_alumni INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_data_alumni NVARCHAR(50) CHECK (status_pengumpulan_data_alumni IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_data_alumni NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_data_alumni PRIMARY KEY CLUSTERED (id_data_alumni ASC)
);


-- PERPUSTAKAAN

DESCRIBE skkm;
DESCRIBE foto_ijazah;


CREATE TABLE ta_softcopy
(
    id_ta_softcopy INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_ta_softcopy NVARCHAR(50) CHECK (status_pengumpulan_ta_softcopy IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_ta_softcopy NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_ta_softcopy PRIMARY KEY CLUSTERED (id_ta_softcopy ASC)
);

CREATE TABLE serahan_hardcopy
(
    id_serahan_hardcopy INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_serahan_hardcopy NVARCHAR(50) CHECK (status_pengumpulan_serahan_hardcopy IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_serahan_hardcopy NVARCHAR(50),
    nim NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT PK_serahan_hardcopy PRIMARY KEY CLUSTERED (id_serahan_hardcopy ASC)
);


CREATE TABLE hasil_quesioner
(
    [id_hasil_quesioner] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_hasil_quesioner NVARCHAR(50) CHECK (status_pengumpulan_hasil_quesioner IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_hasil_quesioner NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_hasil_quesioner] PRIMARY KEY CLUSTERED ([id_hasil_quesioner] ASC)
);

CREATE TABLE bebas_pinjam_perpustakaan
(
    [id_bebas_pinjam_perpustakaan] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_bebas_pinjam_perpustakaan NVARCHAR(50) CHECK (status_pengumpulan_bebas_pinjam_perpustakaan IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_bebas_pinjam_perpustakaan NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_bebas_pinjam_perpustakaan] PRIMARY KEY CLUSTERED ([id_bebas_pinjam_perpustakaan] ASC)
);


--PRODI


CREATE TABLE bebas_kompen
(
    [id_bebas_kompen] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_bebas_kompen NVARCHAR(50) CHECK (status_pengumpulan_bebas_kompen IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_bebas_kompen NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_bebas_kompen] PRIMARY KEY CLUSTERED ([id_bebas_kompen] ASC)
);

CREATE TABLE kebenaran_data
(
    [id_kebenaran_data] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_kebenaran_data NVARCHAR(50) CHECK (status_pengumpulan_kebenaran_data IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_kebenaran_data NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_kebenaran_data] PRIMARY KEY CLUSTERED ([id_kebenaran_data] ASC)
);

CREATE TABLE serahan_pkl
(
    [id_serahan_pkl] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_serahan_pkl NVARCHAR(50) CHECK (status_pengumpulan_serahan_pkl IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_serahan_pkl NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_serahan_pkl] PRIMARY KEY CLUSTERED ([id_serahan_pkl] ASC)
);

CREATE TABLE serahan_skripsi
(
    [id_serahan_skripsi] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_serahan_skripsi NVARCHAR(50) CHECK (status_pengumpulan_serahan_skripsi IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_serahan_skripsi NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_serahan_skripsi] PRIMARY KEY CLUSTERED ([id_serahan_skripsi] ASC)
);

CREATE TABLE toeic
(
    [id_toeic] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_toeic NVARCHAR(50) CHECK (status_pengumpulan_toeic IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_toeic NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_toeic] PRIMARY KEY CLUSTERED ([id_toeic] ASC)
);


--JURUSAN

CREATE TABLE program_aplikasi
(
    [id_program_aplikasi] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_program_aplikasi NVARCHAR(50) CHECK (status_pengumpulan_program_aplikasi IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_program_aplikasi NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_program_aplikasi] PRIMARY KEY CLUSTERED ([id_program_aplikasi] ASC)
);

CREATE TABLE skripsi
(
    [id_skripsi] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_skripsi NVARCHAR(50) CHECK (status_pengumpulan_skripsi IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_skripsi NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_skripsi] PRIMARY KEY CLUSTERED ([id_skripsi] ASC)
);

CREATE TABLE publikasi_jurnal
(
    [id_publikasi_jurnal] INT IDENTITY (1, 1) NOT NULL,
    status_pengumpulan_publikasi_jurnal NVARCHAR(50) CHECK (status_pengumpulan_publikasi_jurnal IN ('terverifikasi', 'kosong', 'diproses', 'ditolak')) DEFAULT 'kosong',
    keterangan_pengumpulan_publikasi_jurnal NVARCHAR(50) ,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    CONSTRAINT [PK_publikasi_jurnal] PRIMARY KEY CLUSTERED ([id_publikasi_jurnal] ASC)
);


--MULTIPLE LOGIN

CREATE TABLE login
(
    [id_login] INT IDENTITY (1, 1) NOT NULL,
    username NVARCHAR(50) NOT NULL,
    password NVARCHAR(50) NOT NULL,
    -- Disarankan untuk menyimpan password dalam bentuk hash
    [position] NVARCHAR(20) NULL,
    CONSTRAINT PK_login PRIMARY KEY CLUSTERED (id_login ASC)
);


use bebastanggungan;

CREATE TABLE [dbo].[adminJurusan_konfirmasi]
(
    [id_adminJurusan_konfirmasi] INT IDENTITY (1, 1) NOT NULL,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    [tanggal_adminJurusan_konfirmasi] DATE NULL,
    CONSTRAINT [PK_adminJurusan_konfirmasi] PRIMARY KEY CLUSTERED ([id_adminJurusan_konfirmasi] ASC)
);
CREATE TABLE [dbo].[adminProdi_konfirmasi]
(
    [id_adminProdi_konfirmasi] INT IDENTITY (1, 1) NOT NULL,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    [tanggal_adminProdi_konfirmasi] DATE NULL,
    CONSTRAINT [PK_adminProdi_konfirmasi] PRIMARY KEY CLUSTERED ([id_adminProdi_konfirmasi] ASC)
);
CREATE TABLE [dbo].[adminPusat_konfirmasi]
(
    [id_adminPusat_konfirmasi] INT IDENTITY (1, 1) NOT NULL,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    [tanggal_adminPusat_konfirmasi] DATE NULL,
    CONSTRAINT [PK_adminPusat_konfirmasi] PRIMARY KEY CLUSTERED ([id_adminPusat_konfirmasi] ASC)
);
CREATE TABLE [dbo].[adminPerpus_konfirmasi]
(
    [id_adminPerpus_konfirmasi] INT IDENTITY (1, 1) NOT NULL,
    [nim] NVARCHAR (10) NULL,
    FOREIGN KEY (nim) REFERENCES mahasiswa(nim),
    [tanggal_adminPerpus_konfirmasi] DATE NULL,
    CONSTRAINT [PK_adminPerpus_konfirmasi] PRIMARY KEY CLUSTERED ([id_adminPerpus_konfirmasi] ASC)
);


ALTER TABLE dbo.mahasiswa
ALTER COLUMN prodi_mhs NVARCHAR(50);




SELECT *
FROM login;
SELECT *
FROM mahasiswa;
SELECT *
FROM admin;
SELECT *
FROM skkm;
SELECT *
FROM foto_ijazah;
SELECT *
FROM ukt;
SELECT *
FROM data_alumni;

SELECT *
FROM ta_softcopy;
SELECT *
FROM serahan_hardcopy;
SELECT *
FROM hasil_quesioner;
SELECT *
FROM bebas_pinjam_perpustakaan;

SELECT *
FROM bebas_kompen;
SELECT *
FROM kebenaran_data;
SELECT *
FROM serahan_pkl;
SELECT *
FROM serahan_skripsi;
SELECT *
FROM toeic;

SELECT *
FROM program_aplikasi;
SELECT *
FROM skripsi;
SELECT *
FROM publikasi_jurnal;



CREATE TRIGGER autoAddKonfirmasi 
ON dbo.mahasiswa
AFTER INSERT
AS
BEGIN
    PRINT 'Trigger autoAddKonfirmasiMahasiswa dipanggil!';

    -- Insert ke adminJurusan_konfirmasi
    INSERT INTO dbo.adminJurusan_konfirmasi
        (nim, tanggal_adminJurusan_konfirmasi)
    SELECT nim, GETDATE()
    FROM inserted;

    -- Insert ke adminProdi_konfirmasi
    INSERT INTO dbo.adminProdi_konfirmasi
        (nim, tanggal_adminProdi_konfirmasi)
    SELECT nim, GETDATE()
    FROM inserted;

    -- Insert ke adminPusat_konfirmasi
    INSERT INTO dbo.adminPusat_konfirmasi
        (nim, tanggal_adminPusat_konfirmasi)
    SELECT nim, GETDATE()
    FROM inserted;

    -- Insert ke adminPerpus_konfirmasi
    INSERT INTO dbo.adminPerpus_konfirmasi
        (nim, tanggal_adminPerpus_konfirmasi)
    SELECT nim, GETDATE()
    FROM inserted;
END;

IF OBJECT_ID('dbo.autoAddLoginMahasiswa') IS NOT NULL 
DROP TRIGGER dbo.autoAddLoginMahasiswa;



CREATE TRIGGER autoAddLoginMahasiswa ON dbo.mahasiswa
AFTER INSERT
AS
BEGIN
    PRINT 'Trigger autoAddLoginMahasiswa dipanggil!';

    -- Insert data ke tabel login berdasarkan data yang ada di inserted
    INSERT INTO dbo.login(username, [password], [position])
    SELECT nim, nim, 'mahasiswa'
    FROM inserted;
END;



CREATE TRIGGER trg_InsertMahasiswa
ON [dbo].[mahasiswa]
AFTER INSERT
AS
BEGIN
    -- Deklarasi variabel untuk menyimpan data dari tabel inserted
    DECLARE @nim NVARCHAR(10);

    -- Ambil nilai nim dari tabel inserted
    SELECT @nim = nim FROM inserted;

    INSERT INTO [dbo].[data_alumni] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[skkm] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[foto_ijazah] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[ukt] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[serahan_hardcopy] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[ta_softcopy] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[bebas_pinjam_perpustakaan] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[hasil_quesioner] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[serahan_skripsi] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[serahan_pkl] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[toeic] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[bebas_kompen] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[kebenaran_data] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[publikasi_jurnal] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[program_aplikasi] (nim) SELECT nim FROM inserted;
    INSERT INTO [dbo].[skripsi] (nim) SELECT nim FROM inserted;
END;

SELECT name
FROM sys.tables;

select * from adminPusat_konfirmasi;
