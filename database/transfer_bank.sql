-- ============================================================
-- Kueku Marinda - Pembayaran Transfer Bank (simulasi)
-- Jalankan SEKALI di phpMyAdmin: pilih database kuekumarinda_db > tab SQL > Go
-- Aman diulang & aman walau qris_simulasi.sql belum pernah dijalankan.
-- Butuh MariaDB 10.4+ (XAMPP: MariaDB 10.4.32 sudah cukup).
-- Pilih SALAH SATU: file SQL ini ATAU php artisan migrate (isinya sama).
-- ============================================================

-- 1. Kolom metode pembayaran: TUNAI, QRIS, TRANSFER
ALTER TABLE `pesanan`
  ADD COLUMN IF NOT EXISTS `metode_pembayaran` ENUM('TUNAI','QRIS','TRANSFER') NOT NULL DEFAULT 'TUNAI'
  AFTER `jenis_pengambilan`;

ALTER TABLE `pesanan`
  MODIFY `metode_pembayaran` ENUM('TUNAI','QRIS','TRANSFER') NOT NULL DEFAULT 'TUNAI';

-- 2. Bank tujuan transfer (BCA / BRI / BNI / MANDIRI), kosong untuk Tunai & QRIS
ALTER TABLE `pesanan`
  ADD COLUMN IF NOT EXISTS `bank_tujuan` VARCHAR(10) NULL
  AFTER `metode_pembayaran`;

-- 3. Status pembayaran: tambah MENUNGGU_VERIFIKASI dan DITOLAK
ALTER TABLE `pesanan`
  ADD COLUMN IF NOT EXISTS `status_pembayaran` ENUM('BELUM_BAYAR','MENUNGGU_VERIFIKASI','DITOLAK','LUNAS') NOT NULL DEFAULT 'BELUM_BAYAR'
  AFTER `bank_tujuan`;

ALTER TABLE `pesanan`
  MODIFY `status_pembayaran` ENUM('BELUM_BAYAR','MENUNGGU_VERIFIKASI','DITOLAK','LUNAS') NOT NULL DEFAULT 'BELUM_BAYAR';

ALTER TABLE `pesanan`
  ADD COLUMN IF NOT EXISTS `waktu_bayar` DATETIME NULL
  AFTER `status_pembayaran`;

-- 4. Tabel bukti transfer (riwayat unggah + hasil verifikasi Admin)
CREATE TABLE IF NOT EXISTS `bukti_transfer` (
  `id_bukti`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan`        BIGINT UNSIGNED NOT NULL,
  `bank`              VARCHAR(10)  NOT NULL,
  `nominal`           BIGINT       NOT NULL,
  `file_path`         VARCHAR(255) NOT NULL,
  `nama_asli`         VARCHAR(150) NULL,
  `status`            ENUM('MENUNGGU','DITERIMA','DITOLAK') NOT NULL DEFAULT 'MENUNGGU',
  `catatan_admin`     VARCHAR(255) NULL,
  `id_verifikator`    BIGINT UNSIGNED NULL,
  `diunggah_pada`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diverifikasi_pada` DATETIME NULL,
  PRIMARY KEY (`id_bukti`),
  KEY `idx_bukti_pesanan` (`id_pesanan`),
  KEY `idx_bukti_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Pesanan tunai/QRIS lama yang sudah tercatat dibayar -> LUNAS (aman diulang)
UPDATE `pesanan` p
JOIN `pembayaran` y ON y.`id_pesanan` = p.`id_pesanan`
SET p.`status_pembayaran` = 'LUNAS',
    p.`waktu_bayar`       = COALESCE(p.`waktu_bayar`, y.`tanggal_bayar`)
WHERE p.`status_pembayaran` = 'BELUM_BAYAR';
