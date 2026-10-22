@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna & Hak Akses (Role)')

@section('topbar_actions')
    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
    </a>
@endsection

@section('content')
<div class="filter-card">
    <form action="{{ route('users.index') }}" method="GET" class="filter-grid" style="grid-template-columns: 2fr 1fr 1fr;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari Pengguna</label>
            <input type="text" name="search" class="form-control" placeholder="Nama, username, email, no telepon..." value="{{ $search }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Role Akses</label>
            <select name="role" class="form-select">
                <option value="">-- Semua Role --</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                <option value="staff" {{ $role === 'staff' ? 'selected' : '' }}>Staff Gudang & Kasir</option>
                <option value="customer" {{ $role === 'customer' ? 'selected' : '' }}>Customer / Pembeli</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users-gear"></i>
            Daftar Akun Pengguna Terdaftar ({{ $users->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama & Username</th>
                    <th>Email</th>
                    <th>Hak Akses (Role)</th>
                    <th>Status Akun</th>
                    <th>Telepon</th>
                    <th>Aktivitas Input</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <strong style="color: #0f172a;">{{ $u->name }}</strong>
                        <div class="font-mono" style="font-size: 0.76rem; color: #64748b;">@ {{ $u->username }}</div>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="user-role-badge {{ $u->isAdmin() ? 'role-admin' : ($u->isCustomer() ? 'role-customer' : 'role-staff') }}">
                            {{ strtoupper($u->role) }}
                        </span>
                    </td>
                    <td>
                        @if($u->is_active)
                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> Aktif</span>
                        @else
                            <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $u->phone ?? '-' }}</td>
                    <td>
                        <span style="font-size: 0.78rem; color: var(--text-muted);">
                            {{ $u->purchases_count }} Pembelian | {{ $u->sales_count }} Penjualan
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-secondary" title="Edit Akun">
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </a>
                        @if($u->id !== Auth::id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Akun">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                        Tidak ada akun pengguna ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
