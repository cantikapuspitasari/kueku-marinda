/**
 * Perhitungan kembalian real-time untuk form pembayaran tunai.
 * Dipakai bersama oleh halaman struk (admin, pickup) dan panel kurir (delivery).
 *
 * Markup yang dibutuhkan pada <form data-bayar data-total="75000">:
 *   [data-bayar-input]            input teks yang diketik (diformat 100.000)
 *   [data-bayar-nilai]            input hidden name="uang_diterima" (angka murni, yang dikirim)
 *   [data-bayar-diterima]         teks "Uang diterima"
 *   [data-bayar-kembalian]        teks nominal kembalian
 *   [data-bayar-pesan]            teks info / peringatan
 *   [data-bayar-submit]           tombol simpan
 * Setiap perhitungan juga memicu event "bayar:hitung" pada form.
 *
 * Ini hanya bantuan tampilan. Validasi dan perhitungan resmi dilakukan ulang di server.
 */
(function () {
  'use strict';

  function rp(n) {
    return 'Rp' + Number(n).toLocaleString('id-ID');
  }

  function pasang(form) {
    var total = parseInt(form.getAttribute('data-total'), 10) || 0;
    var input = form.querySelector('[data-bayar-input]');
    var nilai = form.querySelector('[data-bayar-nilai]');
    var submit = form.querySelector('[data-bayar-submit]');
    var pesan = form.querySelector('[data-bayar-pesan]');
    var elDiterima = form.querySelector('[data-bayar-diterima]');
    var elKembalian = form.querySelector('[data-bayar-kembalian]');
    if (!input || !nilai || !submit) return;

    function hitung() {
      // Hanya angka, maksimal 8 digit (sama dengan batas validasi server)
      var digit = input.value.replace(/\D/g, '').slice(0, 8);
      var uang = digit === '' ? 0 : parseInt(digit, 10);

      input.value = digit === '' ? '' : uang.toLocaleString('id-ID');
      nilai.value = digit;

      var kosong = digit === '';
      var kurang = !kosong && uang < total;
      // Kembalian tidak pernah negatif
      var kembalian = kosong || kurang ? 0 : uang - total;

      if (elDiterima) elDiterima.textContent = rp(uang);
      if (elKembalian) elKembalian.textContent = rp(kembalian);

      if (pesan) {
        pesan.classList.remove('bayar-salah', 'bayar-info');
        if (kosong) {
          pesan.textContent = '';
        } else if (kurang) {
          pesan.textContent = 'Uang pembayaran masih kurang ' + rp(total - uang) + '.';
          pesan.classList.add('bayar-salah');
        } else if (kembalian === 0) {
          pesan.textContent = 'Uang pas, tidak ada kembalian.';
          pesan.classList.add('bayar-info');
        } else {
          pesan.textContent = '';
        }
      }

      // Simpan hanya boleh bila uang diterima cukup (lunas)
      submit.disabled = kosong || kurang;

      form.dispatchEvent(new CustomEvent('bayar:hitung', {
        detail: { uang: uang, total: total, kembalian: kembalian, kosong: kosong, kurang: kurang }
      }));
    }

    input.addEventListener('input', hitung);
    form.addEventListener('submit', function (e) {
      hitung();
      if (submit.disabled) e.preventDefault();
    });
    hitung();
  }

  function mulai() {
    var daftar = document.querySelectorAll('form[data-bayar]');
    for (var i = 0; i < daftar.length; i++) pasang(daftar[i]);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mulai);
  } else {
    mulai();
  }
})();
