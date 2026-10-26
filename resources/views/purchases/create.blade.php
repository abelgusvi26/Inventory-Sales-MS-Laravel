@extends('layouts.app')

@section('title', 'Buat Transaksi Pembelian (Stock-In)')
@section('page_title', 'Input Transaksi Pembelian Baru')

@section('topbar_actions')
    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-cart-flatbed"></i>
            Formulir Pembelian / Stock-In Barang Datang
        </div>
        <span class="badge badge-warning">Stok Produk Bertambah Otomatis</span>
    </div>
    <div class="card-body">
        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
            @csrf

            <!-- Header Info -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="reference_no">Nomor Referensi PO (Otomatis)</label>
                    <input type="text" id="reference_no" name="reference_no" class="form-control font-mono" value="{{ $defaultReference }}" readonly style="background-color: #f8fafc; font-weight: 700;">
                </div>

                <div class="form-group">
                    <label class="form-label" for="supplier_id">Pilih Supplier / Pemasok <span class="required">*</span></label>
                    <select id="supplier_id" name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }} {{ $sup->company_name ? "({$sup->company_name})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="purchase_date">Tanggal Transaksi <span class="required">*</span></label>
                    <input type="date" id="purchase_date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    @error('purchase_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Product Items Table -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 0.95rem; margin-bottom: 0;">
                        <i class="fa-solid fa-list-check me-1"></i> Rincian Produk Yang Dibeli
                    </label>
                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Produk
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="itemsTable" style="border: 1px solid var(--border); border-radius: 8px;">
                        <thead>
                            <tr style="background-color: #f1f5f9;">
                                <th style="width: 45%;">Produk / Suku Cadang</th>
                                <th style="width: 15%;">Kuantitas (Qty)</th>
                                <th style="width: 20%;">Harga Beli Satuan (Rp)</th>
                                <th style="width: 15%;">Subtotal (Rp)</th>
                                <th style="width: 5%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <!-- Rows injected via JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #fafbfc; font-weight: 700;">
                                <td colspan="3" style="text-align: right; padding: 16px;">TOTAL PEMBELIAN:</td>
                                <td colspan="2" class="font-mono" style="font-size: 1.15rem; color: #0f172a; padding: 16px;">
                                    Rp <span id="grandTotalDisplay">0</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Catatan Pembelian (Opsional)</label>
                <textarea id="notes" name="notes" rows="2" class="form-control" placeholder="Nomor surat jalan resmi AHM/YGP, kurir cargo logistik, kondisi kemasan sparepart...">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fa-solid fa-check"></i> Simpan Transaksi Pembelian & Update Stok
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Product options template for JS -->
<template id="productOptionsTemplate">
    <option value="">-- Pilih Produk --</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}" data-unit="{{ $product->unit }}">
            [{{ $product->sku }}] {{ $product->name }} (Stok saat ini: {{ $product->stock }} {{ $product->unit }})
        </option>
    @endforeach
</template>

@push('scripts')
<script>
    let rowIndex = 0;
    const itemsBody = document.getElementById('itemsBody');
    const optionsHtml = document.getElementById('productOptionsTemplate').innerHTML;

    function addRow(productId = '', quantity = 1, unitCost = 0) {
        const tr = document.createElement('tr');
        tr.id = `row-${rowIndex}`;
        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required onchange="handleProductChange(this, ${rowIndex})">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="number" min="1" name="items[${rowIndex}][quantity]" class="form-control qty-input font-mono" value="${quantity}" required oninput="calculateSubtotal(${rowIndex})">
                    <span class="unit-label" style="font-size: 0.75rem; color: #64748b; min-width: 25px;">pcs</span>
                </div>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_cost]" class="form-control cost-input font-mono" value="${unitCost}" required oninput="calculateSubtotal(${rowIndex})">
            </td>
            <td>
                <span class="font-mono subtotal-display" id="subtotal-${rowIndex}" style="font-weight: 700; color: #0f172a;">Rp 0</span>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(${rowIndex})" title="Hapus Baris">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </td>
        `;
        itemsBody.appendChild(tr);

        if (productId) {
            const select = tr.querySelector('.product-select');
            select.value = productId;
            handleProductChange(select, rowIndex);
        } else {
            calculateSubtotal(rowIndex);
        }

        rowIndex++;
    }

    function removeRow(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (row) {
            row.remove();
            calculateGrandTotal();
        }
    }

    function handleProductChange(selectElem, idx) {
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const defaultPrice = selectedOption.getAttribute('data-price') || 0;
        const unit = selectedOption.getAttribute('data-unit') || 'pcs';

        const row = document.getElementById(`row-${idx}`);
        const costInput = row.querySelector('.cost-input');
        const unitLabel = row.querySelector('.unit-label');

        costInput.value = parseFloat(defaultPrice).toFixed(0);
        unitLabel.innerText = unit;

        calculateSubtotal(idx);
    }

    function calculateSubtotal(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (!row) return;

        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const subtotal = Math.max(0, qty * cost);

        row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#itemsBody tr');
        rows.forEach(r => {
            const qty = parseFloat(r.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(r.querySelector('.cost-input').value) || 0;
            total += (qty * cost);
        });

        document.getElementById('grandTotalDisplay').innerText = total.toLocaleString('id-ID');
    }

    document.getElementById('addItemBtn').addEventListener('click', () => {
        addRow();
    });

    // Initialize with 1 default row
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>
@endpush
@endsection
