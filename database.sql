CREATE DATABASE IF NOT EXISTS `sistem_penerimaan_karyawan`;

USE `sistem_penerimaan_karyawan`;

CREATE TABLE IF NOT EXISTS jabatan (
    id_jabatan INT(11) AUTO_INCREMENT,
    nama_jabatan VARCHAR(100),
    PRIMARY KEY (id_jabatan)
);

INSERT INTO jabatan
    (nama_jabatan)
VALUES
    ('J1'),
    ('J2'),
    ('J3');

CREATE TABLE IF NOT EXISTS divisi (
    id_divisi INT(11) AUTO_INCREMENT,
    nama_divisi VARCHAR(100),
    PRIMARY KEY (id_divisi)
);

CREATE TABLE IF NOT EXISTS kriteria (
    id_kriteria INT(11) AUTO_INCREMENT,
    nama_kriteria VARCHAR(100),
    kode_kriteria VARCHAR(10),
    bobot DECIMAL,
    PRIMARY KEY (id_kriteria)
);

CREATE TABLE IF NOT EXISTS user (
    id_user INT(11) AUTO_INCREMENT,
    jabatan INT(11),
    user_name VARCHAR(50),
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    role ENUM('General Manager', 'Departement', 'HRD', 'Pelamar'),
    password VARCHAR(255),
    PRIMARY KEY (id_user),
    CONSTRAINT fk_user_jabatan
     FOREIGN KEY (jabatan)
     REFERENCES jabatan (id_jabatan)
     ON DELETE SET NULL
     ON UPDATE CASCADE
);
INSERT INTO user
    (jabatan, user_name, nama_lengkap, email, role, password)
VALUES
    (1, 'user.general-manager', 'General Manager', 'general-manager@mail.com', 'General Manager', 'password'),
    (2, 'user.departement', 'Departement', 'departement@mail.com', 'Departement', 'password'),
    (3, 'user.hrd', 'HRD', 'hrd@mail.com', 'HRD', 'password'),
    (1, 'user.pelamar', 'Pelamar', 'pelamar@mail.com', 'Pelamar', 'password');

CREATE TABLE IF NOT EXISTS permintaan (
    id_permintaan INT(11) AUTO_INCREMENT,
    id_divisi INT(11),
    jumlah_permintaan INT(11),
    status_permintaan ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending',
    PRIMARY KEY (id_permintaan),
    CONSTRAINT fk_permintaan_divisi
     FOREIGN KEY (id_divisi)
     REFERENCES divisi (id_divisi)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS lowongan (
    id_lowongan INT(11) AUTO_INCREMENT,
    id_permintaan INT(11),
    nama_lowongan VARCHAR(100),
    deskripsi TEXT,
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
    pengalaman_kerja VARCHAR(255),
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
    pengalaman_kerja VARCHAR(255),
    umur INT(11),
    pendidikan ENUM('SMA/SMK', 'Diploma', 'Sarjana'),
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
    id_kriteria INT(11),
    id_pelamaran INT(11),
    nilai FLOAT,
    PRIMARY KEY (id_penilaian),
    CONSTRAINT fk_penilaian_kriteria FOREIGN KEY (id_kriteria)
     REFERENCES kriteria (id_kriteria)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
    CONSTRAINT fk_penilaian_pelamaran FOREIGN KEY (id_pelamaran)
     REFERENCES pelamaran (id_pelamaran)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS hasil (
    id_hasil INT(11) AUTO_INCREMENT,
    id_penilaian INT(11),
    id_divisi INT(11),
    peringkat INT(11),
    nama_lengkap VARCHAR(255),
    status ENUM('Diterima', 'Ditolak'),
    PRIMARY KEY (id_hasil),
    CONSTRAINT fk_hasil_penilaian
     FOREIGN KEY (id_penilaian)
     REFERENCES penilaian (id_penilaian)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
    CONSTRAINT fk_hasil_divisi
     FOREIGN KEY (id_divisi)
     REFERENCES divisi (id_divisi)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);
