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
    user_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('General Manager', 'Departement', 'HRD', 'Admin', 'Pelamar') NOT NULL,
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
    UNIQUE KEY (user_name, role),
    UNIQUE KEY (email, role),
    CONSTRAINT fk_user_jabatan
     FOREIGN KEY (jabatan)
     REFERENCES jabatan (id_jabatan)
     ON DELETE SET NULL
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS divisi (
    id_divisi INT(11) AUTO_INCREMENT,
    id_user INT(11),
    nama_divisi VARCHAR(100),
    jumlah_personil INT(11),
    PRIMARY KEY (id_divisi),
    CONSTRAINT fk_divisi_user
     FOREIGN KEY (id_user)
     REFERENCES user (id_user)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS karyawan (
    id_karyawan INT(11) AUTO_INCREMENT,
    id_divisi INT(11),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100)  NOT NULL UNIQUE,
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    nomor_telepon VARCHAR(13),
    jenis_kelamin BOOLEAN,
    pendidikan_terakhir ENUM('SMA/SMK', 'Diploma', 'Sarjana'),
    alamat TEXT,
    PRIMARY KEY (id_karyawan),
    CONSTRAINT fk_karyawan_divisi
     FOREIGN KEY (id_divisi)
     REFERENCES divisi (id_divisi)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS permintaan (
    id_permintaan INT(11) AUTO_INCREMENT,
    id_user INT(11),
    id_divisi INT(11),
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
    CONSTRAINT fk_permintaan_user FOREIGN KEY (id_user) REFERENCES user (id_user) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_permintaan_divisi FOREIGN KEY (id_divisi) REFERENCES divisi (id_divisi) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS lowongan (
    id_lowongan INT(11) AUTO_INCREMENT,
    id_permintaan INT(11),
    nama_lowongan VARCHAR(100),
    poster_lowongan VARCHAR(255),
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
     ON UPDATE CASCADE,
    CONSTRAINT fk_pelamaran_user
     FOREIGN KEY (id_user)
     REFERENCES user (id_user)
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

INSERT INTO user
    (user_name, email, password, role, name)
VALUES
    ('user.admin', 'admin@app.com', 'password', 'Admin', 'Admin');

CREATE OR REPLACE VIEW vektor_s_weighted_product AS
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

CREATE OR REPLACE VIEW vektor_v_weighted_product AS
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

CREATE OR REPLACE VIEW divisi_status AS
SELECT
    u.id_user AS id_department,
    u.name AS nama_department,
    d.id_divisi,
    d.nama_divisi,
    d.jumlah_personil,
    COUNT(k.id_karyawan) AS current_karyawan,
    CASE
        WHEN COUNT(k.id_karyawan) < d.jumlah_personil THEN 1
        ELSE 0
    END AS isInNeed
FROM divisi d
LEFT JOIN user u ON d.id_user = u.id_user
LEFT JOIN karyawan k ON d.id_divisi = k.id_divisi
GROUP BY d.id_divisi, u.id_user, u.name, d.nama_divisi, d.jumlah_personil;

CREATE OR REPLACE VIEW view_pelamaran_status AS
SELECT
    p.id_user,
    p.id_pelamaran,
    l.nama_lowongan,
    CASE
        WHEN h.status = 'Diterima' THEN TRUE
        ELSE NULL
    END AS isApproved
FROM pelamaran p
LEFT JOIN lowongan l ON p.id_lowongan = l.id_lowongan
LEFT JOIN hasil h ON p.id_pelamaran = h.id_pelamaran;
