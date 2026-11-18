{{-- Lencana metode + status pembayaran sebuah pesanan. Pemakaian: @include('partials.badge-bayar', ['p' => $pesanan]) --}}
@php
  $m  = $p->metode_pembayaran ?? 'TUNAI';
  $sb = $p->status_pembayaran ?: 'BELUM_BAYAR';
@endphp
@if ($m === 'TUNAI')
  <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border bg-stone-100 text-stone-700 border-stone-200">Tunai</span>
@else
  <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $sb === 'LUNAS' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($sb === 'DITOLAK' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-800 border-amber-200') }}">{{ $m === 'TRANSFER' ? 'Transfer' . ($p->bank_tujuan ? ' ' . $p->bank_tujuan : '') : 'QRIS' }} &middot; {{ strtoupper(\App\Models\Pesanan::STATUS_PEMBAYARAN[$sb] ?? $sb) }}</span>
@endif
