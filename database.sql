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
    jenis_kelamin jenis_kelamin SET('Laki-laki', 'Perempuan') NOT NULL,
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
    PRIMARY KEY (id_lowongan),
    CONSTRAINT fk_lowongan_permintaan
     FOREIGN KEY (id_permintaan)
     REFERENCES permintaan (id_permintaan)
     ON DELETE CASCADE
     ON UPDATE CASCADE
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
    id_pelamaran INT(11) UNIQUE,
    nilai_tes_tertulis FLOAT,
    nilai_tes_wawancara ENUM('Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'),
    nilai_tes_praktek ENUM('Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'),
    nilai_tes_psikotes FLOAT,
    nilai_tes_kesehatan ENUM('Sangat Kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'),
    PRIMARY KEY (id_penilaian),
    CONSTRAINT fk_penilaian_pelamaran FOREIGN KEY (id_pelamaran)
     REFERENCES pelamaran (id_pelamaran)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS hasil (
    id_hasil INT(11) AUTO_INCREMENT,
    id_penilaian INT(11) UNIQUE,
    vector_s FLOAT,
    hasil_akhir FLOAT,
    peringkat INT(11),
    status ENUM('Diterima', 'Ditolak') NULL,
    PRIMARY KEY (id_hasil),
    CONSTRAINT fk_hasil_penilaian
     FOREIGN KEY (id_penilaian)
     REFERENCES penilaian (id_penilaian)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE password_resets (
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