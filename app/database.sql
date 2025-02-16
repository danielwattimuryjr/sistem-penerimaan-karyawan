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
    role ENUM('General Manager', 'Departement', 'HRD', 'Admin') NOT NULL,
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
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    nomor_telepon VARCHAR(13) NOT NULL,
    jenis_kelamin BOOLEAN NOT NULL,
    pendidikan_terakhir ENUM('SMA/SMK', 'Diploma', 'Sarjana') NOT NULL,
    alamat TEXT NOT NULL,
    id_lowongan INT(11),
    pengalaman_kerja LONGTEXT NOT NULL,
    curiculum_vitae VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_pelamaran),
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
    p.id_pelamaran,
    p.name AS nama_pelamar,

    -- Nilai asli setiap faktor
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN pn.nilai END), 0) AS nilai_tes_tertulis,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN pn.nilai END), 0) AS nilai_tes_wawancara,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN pn.nilai END), 0) AS nilai_tes_praktek,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN pn.nilai END), 0) AS nilai_tes_psikotes,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN pn.nilai END), 0) AS nilai_tes_kesehatan,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN pn.nilai END), 0) AS nilai_pendidikan,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'umur' THEN pn.nilai END), 0) AS nilai_umur,
    COALESCE(MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN pn.nilai END), 0) AS nilai_pengalaman_kerja,

    -- Nilai dipangkatkan dengan bobot (untuk referensi)
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'tes_tertulis' THEN fp.bobot END)) AS nilai_tes_tertulis_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'tes_wawancara' THEN fp.bobot END)) AS nilai_tes_wawancara_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'tes_praktek' THEN fp.bobot END)) AS nilai_tes_praktek_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'tes_psikotes' THEN fp.bobot END)) AS nilai_tes_psikotes_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'tes_kesehatan' THEN fp.bobot END)) AS nilai_tes_kesehatan_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'pendidikan' THEN fp.bobot END)) AS nilai_pendidikan_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'umur' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'umur' THEN fp.bobot END)) AS nilai_umur_pow_bobot,
    POWER(NULLIF(MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN pn.nilai END), 0),
          MAX(CASE WHEN fp.nama_faktor = 'pengalaman_kerja' THEN fp.bobot END)) AS nilai_pengalaman_kerja_pow_bobot,

    -- Perhitungan vektor S menggunakan ekspresi log-sum-exp untuk stabilitas
    EXP(SUM(
        COALESCE(
            CASE
                WHEN fp.nama_faktor = 'tes_tertulis' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'tes_wawancara' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'tes_praktek' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'tes_psikotes' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'tes_kesehatan' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'pendidikan' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'umur' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
                WHEN fp.nama_faktor = 'pengalaman_kerja' THEN fp.bobot * LOG(NULLIF(pn.nilai, 0))
            END, 0)
    )) AS vektor_s

FROM pelamaran p
JOIN lowongan l ON p.id_lowongan = l.id_lowongan
JOIN faktor_penilaian fp ON l.id_lowongan = fp.id_lowongan
LEFT JOIN penilaian pn ON p.id_pelamaran = pn.id_pelamaran AND fp.id_faktor = pn.id_faktor
GROUP BY l.id_lowongan, p.id_pelamaran, p.name;

CREATE VIEW vektor_v_weighted_product AS
SELECT
    vswp.id_lowongan,
    p.id_pelamaran,
    h.id_hasil,
    p.name AS nama_pelamar,
    vswp.vektor_s,

    SUM(vswp2.vektor_s) AS jumlah_vektor_s,

    vswp.vektor_s / SUM(vswp2.vektor_s) AS vektor_y,

    DENSE_RANK() OVER (PARTITION BY vswp.id_lowongan ORDER BY vswp.vektor_s DESC) AS peringkat

FROM vektor_s_weighted_product vswp
JOIN pelamaran p ON vswp.id_pelamaran = p.id_pelamaran
LEFT JOIN hasil h ON p.id_pelamaran = h.id_pelamaran
JOIN vektor_s_weighted_product vswp2 ON vswp.id_lowongan = vswp2.id_lowongan
GROUP BY vswp.id_lowongan, p.id_pelamaran, h.id_hasil, p.name, vswp.vektor_s;
