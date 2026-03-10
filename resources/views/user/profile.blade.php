@extends('layouts.user')
@section('title', 'Profil & Saldo')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Profile -->
        <div class="col-12 col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success fw-bold mx-auto mb-3" style="width:80px;height:80px;font-size:1.8rem">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <h6 class="fw-bold mb-1">{{ $user->name }}</h6>
                    <div class="text-muted small mb-3">{{ $user->email }}</div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <div class="text-muted small mb-1">Saldo Virtual</div>
                        <div class="fs-3 fw-bold text-success">Rp {{ number_format($user->balance, 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1">Hubungi admin untuk top up</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-semibold"><i class="bi bi-person me-2"></i>Edit Profil</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Balance History -->
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header fw-semibold"><i class="bi bi-wallet2 me-2 text-primary"></i>Riwayat Saldo</div>
                <div class="card-body p-0">
                    @forelse($balanceHistories as $bh)
                    <div class="d-flex align-items-center gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="rounded-circle d-flex align-items-center justify-content-center {{ $bh->type === 'credit' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}" style="width:40px;height:40px;min-width:40px">
                            <i class="bi {{ $bh->type === 'credit' ? 'bi-plus-lg' : 'bi-dash-lg' }}"></i>
                        </div>
                        <div class="flex-fill">
                            <div class="fw-semibold small">{{ $bh->description }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $bh->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold {{ $bh->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $bh->type === 'credit' ? '+' : '-' }}Rp {{ number_format($bh->amount, 0, ',', '.') }}
                            </div>
                            <div class="text-muted small">Saldo: Rp {{ number_format($bh->balance_after, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-wallet2 fs-2 d-block mb-2"></i>
                        Belum ada riwayat saldo
                    </div>
                    @endforelse
                </div>
                @if($balanceHistories->hasPages())
                <div class="p-3 border-top">{{ $balanceHistories->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
