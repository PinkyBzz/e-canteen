@extends('layouts.user')
@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7">
            <!-- Status Banner -->
            <div class="rounded-4 p-4 text-white mb-4 text-center"
                 style="background:{{ $order->status === 'ready' ? 'linear-gradient(135deg,#059669,#10b981)' : ($order->status === 'cancelled' ? 'linear-gradient(135deg,#dc2626,#ef4444)' : 'linear-gradient(135deg,#2d6a4f,#52b788)') }}">
                @if($order->status === 'ready')
                    <i class="bi bi-bell-fill fs-2 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1">Pesanan Siap! 🎉</h5>
                    <p class="mb-0 opacity-75">Silakan ambil pesananmu di kantin saat {{ $order->break_time_label }}</p>
                @elseif($order->status === 'preparing')
                    <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                    <h5 class="fw-bold mb-1">Sedang Disiapkan...</h5>
                    <p class="mb-0 opacity-75">Mohon tunggu, pesananmu sedang diproses</p>
                @elseif($order->status === 'pending')
                    <i class="bi bi-hourglass-split fs-2 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1">Menunggu Konfirmasi</h5>
                    <p class="mb-0 opacity-75">Pesananmu telah diterima dan menunggu diproses</p>
                @elseif($order->status === 'completed')
                    <i class="bi bi-check-circle-fill fs-2 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1">Pesanan Selesai</h5>
                    <p class="mb-0 opacity-75">Terima kasih sudah memesan di E-Canteen!</p>
                @else
                    <i class="bi bi-x-circle-fill fs-2 mb-2 d-block"></i>
                    <h5 class="fw-bold mb-1">Pesanan Dibatalkan</h5>
                @endif
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row g-2 mb-3 text-center">
                        <div class="col-6 border-end">
                            <div class="text-muted small mb-1">Kode Pesanan</div>
                            <div class="fw-bold small">{{ $order->order_code }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Waktu Pengambilan</div>
                            <div class="fw-bold small">{{ $order->break_time_label }}</div>
                        </div>
                    </div>
                    <div class="text-muted small text-center mb-3">
                        Dipesan: {{ $order->created_at->format('d M Y, H:i') }}
                        @if($order->notes)
                        <br><span class="fst-italic">Catatan: {{ $order->notes }}</span>
                        @endif
                    </div>

                    <hr>

                    <!-- Items -->
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-semibold small">{{ $item->menu->name ?? 'Menu dihapus' }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="fw-semibold small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <span class="fw-bold">Total Dibayar</span>
                        <span class="fw-bold text-success fs-6">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('user.orders.history') }}" class="btn btn-light flex-fill">
                    <i class="bi bi-arrow-left me-1"></i>Riwayat
                </a>
                <a href="{{ route('user.home') }}" class="btn btn-primary flex-fill">
                    <i class="bi bi-plus me-1"></i>Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
