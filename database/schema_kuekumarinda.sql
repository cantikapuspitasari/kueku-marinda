CREATE DATABASE IF NOT EXISTS `kuekumarinda_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kuekumarinda_db`;

CREATE TABLE `users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `role` ENUM('ADMIN','OWNER','KURIR') NOT NULL,
    `status_akun` ENUM('AKTIF','NONAKTIF') NOT NULL DEFAULT 'AKTIF',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `pembeli` (
    `id_pembeli` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `no_telepon` VARCHAR(20) NOT NULL,
    `status_akun` ENUM('AKTIF','NONAKTIF') NOT NULL DEFAULT 'AKTIF',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `alamat` (
    `id_alamat` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pembeli` INT NOT NULL,
    `label_alamat` VARCHAR(50) NOT NULL,
    `alamat_lengkap` TEXT NOT NULL,
    `kota` VARCHAR(50) NOT NULL,
    `kode_pos` VARCHAR(10) NULL,
    CONSTRAINT `fk_alamat_pembeli` FOREIGN KEY (`id_pembeli`)
        REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `kategori` (
    `id_kategori` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE `produk` (
    `id_produk` INT AUTO_INCREMENT PRIMARY KEY,
    `id_kategori` INT NOT NULL,
    `nama_produk` VARCHAR(100) NOT NULL,
    `deskripsi` TEXT NULL,
    `harga` DECIMAL(10,2) NOT NULL,
    `stok` INT NOT NULL DEFAULT 0,
    `status_produk` ENUM('TERSEDIA','STOK_HABIS','NONAKTIF') NOT NULL DEFAULT 'TERSEDIA',
    `foto_produk` VARCHAR(255) NULL,
    CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`)
        REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `pesanan` (
    `id_pesanan` INT AUTO_INCREMENT PRIMARY KEY,
    `kode_pesanan` VARCHAR(30) NOT NULL UNIQUE,
    `id_pembeli` INT NOT NULL,
    `id_alamat` INT NULL,
    `tanggal_pesan` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `tanggal_ambil` DATE NOT NULL,
    `jenis_pengambilan` ENUM('PICKUP','DELIVERY') NOT NULL DEFAULT 'PICKUP',
    `status_pesanan` ENUM('PENDING','DIKONFIRMASI','DIPROSES','SELESAI','DIBATALKAN') NOT NULL DEFAULT 'PENDING',
    `total_harga` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pesanan_pembeli` FOREIGN KEY (`id_pembeli`)
        REFERENCES `pembeli` (`id_pembeli`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pesanan_alamat` FOREIGN KEY (`id_alamat`)
        REFERENCES `alamat` (`id_alamat`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `detail_pesanan` (
    `id_detail_pesanan` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pesanan` INT NOT NULL,
    `id_produk` INT NOT NULL,
    `jumlah` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(10,2) NOT NULL,
    CONSTRAINT `fk_detail_pesanan` FOREIGN KEY (`id_pesanan`)
        REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`)
        REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `pembayaran` (
    `id_pembayaran` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pesanan` INT NOT NULL UNIQUE,
    `id_user` INT NOT NULL,
    `jumlah_bayar` DECIMAL(10,2) NOT NULL,
    `tempat_pembayaran` ENUM('TOKO','ALAMAT_PEMBELI') NOT NULL,
    `tanggal_bayar` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pembayaran_pesanan` FOREIGN KEY (`id_pesanan`)
        REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pembayaran_user` FOREIGN KEY (`id_user`)
        REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `pengiriman` (
    `id_pengiriman` INT AUTO_INCREMENT PRIMARY KEY,
    `id_pesanan` INT NOT NULL UNIQUE,
    `id_user` INT NOT NULL,
    `status_pengiriman` ENUM('MENUNGGU','DIAMBIL_KURIR','DIANTAR','TERKIRIM') NOT NULL DEFAULT 'MENUNGGU',
    `waktu_diambil` DATE NULL,
    `waktu_terkirim` DATE NULL,
    CONSTRAINT `fk_pengiriman_pesanan` FOREIGN KEY (`id_pesanan`)
        REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pengiriman_user` FOREIGN KEY (`id_user`)
        REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE INDEX `idx_pesanan_status` ON `pesanan` (`status_pesanan`, `tanggal_ambil`);
CREATE INDEX `idx_produk_status` ON `produk` (`status_produk`);

INSERT INTO `users` (`id_user`,`nama`,`email`,`password`,`no_telepon`,`role`) VALUES
(1,'Bu Marinda','admin@gmail.com','$2y$10$contohHashPassword','082345678901','ADMIN'),
(2,'Pak Sandi','owner@gmail.com','$2y$10$contohHashPassword','083567891200','OWNER'),
(3,'Deni Kurniawan','kurir@gmail.com','$2y$10$contohHashPassword','084567891230','KURIR');

INSERT INTO `pembeli` (`id_pembeli`,`nama`,`email`,`password`,`no_telepon`) VALUES
(1,'Rina Amelia','rina@gmail.com','$2y$10$contohHashPassword','081234567890');

INSERT INTO `alamat` (`id_alamat`,`id_pembeli`,`label_alamat`,`alamat_lengkap`,`kota`,`kode_pos`) VALUES
(1,1,'Rumah','Jl. Melati No. 12, RT 01/RW 05','Bandung','40123');

INSERT INTO `kategori` (`id_kategori`,`nama_kategori`,`deskripsi`) VALUES
(1,'Roti','Berbagai pilihan roti lembut dan lezat dengan berbagai varian rasa, cocok untuk sarapan, camilan, maupun teman bersantai'),
(2,'Kue Kering','Kue kering untuk oleh-oleh dan hampers');

INSERT INTO `produk` (`id_produk`,`id_kategori`,`nama_produk`,`deskripsi`,`harga`,`stok`,`status_produk`) VALUES
(1,1,'Roti Abon Mayo','Roti lembut dengan isian mayones gurih dan taburan abon yang lezat, menghasilkan perpaduan rasa gurih, creamy, dan nikmat di setiap gigitan',35000.00,20,'TERSEDIA'),
(2,2,'Nastar Premium','Nastar premium dengan tekstur lembut dan lumer, berisi selai nanas manis-gurih yang nikmat di setiap gigitan',65000.00,0,'STOK_HABIS'),
(3,1,'Roti Almond','Perpaduan nikmat antara kelembutan roti ditaburi almond yang gurih',20000.00,5,'NONAKTIF');

INSERT INTO `pesanan` (`id_pesanan`,`kode_pesanan`,`id_pembeli`,`id_alamat`,`tanggal_pesan`,`tanggal_ambil`,`jenis_pengambilan`,`status_pesanan`,`total_harga`) VALUES
(1,'KM-20260920-001',1,1,'2026-09-20 09:10:00','2026-09-30','DELIVERY','SELESAI',35000.00);

INSERT INTO `detail_pesanan` (`id_pesanan`,`id_produk`,`jumlah`,`subtotal`) VALUES
(1,1,1,35000.00);

INSERT INTO `pembayaran` (`id_pesanan`,`id_user`,`jumlah_bayar`,`tempat_pembayaran`,`tanggal_bayar`) VALUES
(1,3,35000.00,'ALAMAT_PEMBELI','2026-09-30 15:00:00');

INSERT INTO `pengiriman` (`id_pesanan`,`id_user`,`status_pengiriman`,`waktu_diambil`,`waktu_terkirim`) VALUES
(1,3,'TERKIRIM','2026-09-30','2026-09-30');