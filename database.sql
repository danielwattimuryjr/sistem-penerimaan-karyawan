CREATE DATABASE IF NOT EXISTS `sistem_penerimaan_karyawan`;

USE `sistem_penerimaan_karyawan`;

CREATE TABLE IF NOT EXISTS jabatan (
    id_jabatan INT(11) AUTO_INCREMENT,
    nama_jabatan VARCHAR(100),
    PRIMARY KEY (id_jabatan)
);

CREATE TABLE IF NOT EXISTS user (
    id_user INT(11) AUTO_INCREMENT NOT NULL,
    jabatan INT(11),
    user_name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('General Manager', 'Departement', 'HRD', 'Pelamar', 'Admin') NOT NULL DEFAULT 'Pelamar',
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    -- Optional Info
    jenis_kelamin BOOLEAN NULL,
    pendidikan_terakhir ENUM('SMA/SMK', 'Diploma', 'Sarjana') NULL,
    nomor_telepon VARCHAR(13) NULL,
    alamat TEXT NULL,
    tempat_lahir VARCHAR(50) NULL,
    tanggal_lahir DATE NULL,
    PRIMARY KEY (id_user),
    CONSTRAINT fk_user_jabatan
     FOREIGN KEY (jabatan)
     REFERENCES jabatan (id_jabatan)
     ON DELETE SET NULL
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS permintaan (
    id_permintaan INT(11) AUTO_INCREMENT,
    id_user INT(11),
    posisi VARCHAR(50),
    -- TRUE = PRIA
    -- FALSE = WANITA
    jenis_kelamin SET('Laki-laki', 'Perempuan') NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NULL,
    jumlah_permintaan INT(11),
    status_kerja ENUM('daily-worker', 'karyawan-kontrak'),
    status_permintaan ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending',
    keperluan LONGTEXT,
    tanggal_permintaan DATE,
    PRIMARY KEY (id_permintaan),
    CONSTRAINT fk_permintaan_user FOREIGN KEY (id_user) REFERENCES user (id_user) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS lowongan (
    id_lowongan INT(11) AUTO_INCREMENT,
    id_permintaan INT(11),
    nama_lowongan VARCHAR(100),
    poster_lowongan VARCHAR(255),
    deskripsi LONGTEXT,
    tgl_mulai DATE,
    tgl_selesai DATE,
    closed BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id_lowongan),
    CONSTRAINT fk_lowongan_permintaan
     FOREIGN KEY (id_permintaan)
     REFERENCES permintaan (id_permintaan)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS faktor_penilaian (
    id_faktor INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_lowongan INT (11) NOT NULL,
    nama_faktor VARCHAR(50) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    UNIQUE KEY (id_lowongan, nama_faktor),
    CONSTRAINT fk_faktor_penilaian_lowongan
     FOREIGN KEY (id_lowongan)
     REFERENCES lowongan(id_lowongan)
     ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS persyaratan (
    id_persyaratan INT(11) AUTO_INCREMENT,
    id_lowongan INT(11),
    pengalaman_kerja INT(11),
    umur INT(11),
    pendidikan ENUM('SMA/SMK', 'Diploma', 'Sarjana'),
    PRIMARY KEY (id_persyaratan),
    CONSTRAINT fk_persyaratan_lowongan
     FOREIGN KEY (id_lowongan)
     REFERENCES lowongan (id_lowongan)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS pelamaran (
    id_pelamaran INT(11) AUTO_INCREMENT,
    id_user INT(11),
    id_lowongan INT(11),
    pengalaman_kerja LONGTEXT,
    curiculum_vitae VARCHAR(255),
    PRIMARY KEY (id_pelamaran),
    CONSTRAINT fk_pelamaran_user
     FOREIGN KEY (id_user)
     REFERENCES user (id_user)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
    CONSTRAINT fk_pelamaran_lowongan
     FOREIGN KEY (id_lowongan)
     REFERENCES lowongan (id_lowongan)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS penilaian (
    id_penilaian INT(11) AUTO_INCREMENT,
    id_pelamaran INT(11),
    id_faktor INT(11),
    nilai TINYINT(1),
    UNIQUE KEY (id_pelamaran, id_faktor),
    PRIMARY KEY (id_penilaian),
    CONSTRAINT fk_penilaian_pelamaran FOREIGN KEY (id_pelamaran)
     REFERENCES pelamaran (id_pelamaran)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
    CONSTRAINT fk_penilaian_faktor_penilaian FOREIGN KEY (id_faktor)
     REFERENCES faktor_penilaian (id_faktor)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS hasil (
    id_hasil INT(11) AUTO_INCREMENT,
    id_pelamaran INT(11),
    status ENUM('Diterima', 'Ditolak') NULL,
    PRIMARY KEY (id_hasil),
    CONSTRAINT fk_hasil_pelamaran
     FOREIGN KEY (id_pelamaran)
     REFERENCES pelamaran (id_pelamaran)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expiry DATETIME NOT NULL,
    CONSTRAINT fk_password_reset_user
     FOREIGN KEY (id_user)
     REFERENCES user(id_user)
     ON DELETE CASCADE
);

INSERT INTO jabatan
(nama_jabatan)
VALUES
    ('J1'),
    ('J2'),
    ('J3');

INSERT INTO user
    (user_name, email, password, role, name)
VALUES
    ('user.admin', 'admin@app.com', 'password', 'Admin', 'Admin'),
    ('user.hrd', 'hrd@app.com', 'password', 'HRD', 'HRD'),
    ('user.general-manager', 'general-manager@app.com', 'password', 'General Manager', 'General Manager'),
    ('user.pelamar', 'pelamar@app.com', 'password', 'Pelamar', 'Pelamar');

CREATE VIEW vektor_s_weighted_product AS
SELECT
   l.id_lowongan,
   u.id_user,
   p.id_pelamaran,
   u.name as nama_pelamar,
   -- Nilai asli
   MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN COALESCE(pn.nilai, 0) END) as nilai_tes_tertulis,
   MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN COALESCE(pn.nilai, 0) END) as nilai_tes_wawancara,
   MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN COALESCE(pn.nilai, 0) END) as nilai_tes_praktek,
   MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN COALESCE(pn.nilai, 0) END) as nilai_tes_psikotes,
   MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN COALESCE(pn.nilai, 0) END) as nilai_tes_kesehatan,
   MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN COALESCE(pn.nilai, 0) END) as nilai_pendidikan,
   MAX(CASE WHEN fp.nama_faktor = 'umur' THEN COALESCE(pn.nilai, 0) END) as nilai_umur,
   MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN COALESCE(pn.nilai, 0) END) as nilai_pengalaman_kerja,

   -- Nilai dipangkatkan bobot
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN fp.bobot END)) as nilai_tes_tertulis_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN fp.bobot END)) as nilai_tes_wawancara_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN fp.bobot END)) as nilai_tes_praktek_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN fp.bobot END)) as nilai_tes_psikotes_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN fp.bobot END)) as nilai_tes_kesehatan_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN fp.bobot END)) as nilai_pendidikan_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'umur' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'umur' THEN fp.bobot END)) as nilai_umur_pow_bobot,
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN fp.bobot END)) as nilai_pengalaman_kerja_pow_bobot,

   -- Jumlah total nilai yang sudah dipangkat bobot (Vektor S)
   (POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'umur' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'umur' THEN fp.bobot END)) *
   POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN COALESCE(pn.nilai, 0) END), 0),
         MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN fp.bobot END))) as vektor_s
FROM lowongan l
JOIN pelamaran p ON l.id_lowongan = p.id_lowongan
JOIN user u ON p.id_user = u.id_user
JOIN faktor_penilaian fp ON l.id_lowongan = fp.id_lowongan
LEFT JOIN penilaian pn ON p.id_pelamaran = pn.id_pelamaran
   AND fp.id_faktor = pn.id_faktor
GROUP BY l.id_lowongan, p.id_pelamaran, u.name;

CREATE VIEW vektor_v_weighted_product AS
SELECT
    vswp.id_lowongan,
    p.id_pelamaran,
    h.id_hasil, -- Tambahkan id_hasil dari tabel hasil
    u.id_user,
    u.name AS nama_pelamar,
    vswp.vektor_s,
    (SELECT SUM(vswp2.vektor_s)
     FROM vektor_s_weighted_product vswp2
     WHERE vswp2.id_lowongan = vswp.id_lowongan) AS jumlah_vektor_s,
    vswp.vektor_s /
    (SELECT SUM(vswp2.vektor_s)
     FROM vektor_s_weighted_product vswp2
     WHERE vswp2.id_lowongan = vswp.id_lowongan) AS vektor_y,
    RANK() OVER (PARTITION BY vswp.id_lowongan ORDER BY
        vswp.vektor_s /
        (SELECT SUM(vswp2.vektor_s)
         FROM vektor_s_weighted_product vswp2
         WHERE vswp2.id_lowongan = vswp.id_lowongan) DESC) AS peringkat
FROM vektor_s_weighted_product vswp
JOIN pelamaran p ON vswp.id_lowongan = p.id_lowongan
JOIN user u ON p.id_user = u.id_user
LEFT JOIN hasil h ON p.id_pelamaran = h.id_pelamaran; -- Tambahkan LEFT JOIN ke tabel hasil
