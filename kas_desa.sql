CREATE DATABASE kas_des;
USE kas_desa;

CREATE TABLE tagihan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_tagihan VARCHAR(20) UNIQUE NOT NULL,
    nama_pembayar VARCHAR(100) NOT NULL,
    alamat TEXT,
    jenis_tagihan ENUM('Iuran Wajib', 'Sumbangan', 'Lainnya') NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    tanggal_tagihan DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    status ENUM('Belum Bayar', 'Lunas', 'Terlambat') DEFAULT 'Belum Bayar',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pembayaran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tagihan_id INT,
    tanggal_bayar DATE NOT NULL,
    jumlah_bayar DECIMAL(12,2) NOT NULL,
    metode_bayar ENUM('Tunai', 'Transfer') DEFAULT 'Tunai',
    bukti_bayar VARCHAR(255),
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tagihan_id) REFERENCES tagihan(id) ON DELETE CASCADE
);