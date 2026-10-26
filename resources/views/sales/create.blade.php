@extends('layouts.app')

@section('title', Auth::user()->isCustomer() ? 'Pemesanan Suku Cadang' : 'Transaksi Penjualan Baru')
@section('page_title', Auth::user()->isCustomer() ? 'Formulir Pemesanan Suku Cadang' : 'Kasir Penjualan / Stock-Out')

@section('topbar_actions')
    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Pesanan
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            @if(Auth::user()->isCustomer())
                <i class="fa-solid fa-cart-shopping text-primary"></i>
                Formulir Pemesanan Suku Cadang Resmi
            @else
                <i class="fa-solid fa-cash-register"></i>
                Formulir Penjualan Suku Cadang & Barang Keluar
            @endif
        </div>
        <span class="badge badge-success">Stok Berkurang Otomatis (Validasi Ketat Server)</span>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
            @csrf

            <!-- Customer & Transaction Header -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <div class="form-group">
                    <label class="form-label" for="invoice_no">Nomor Faktur / Invoice (Otomatis)</label>
                    <input type="text" id="invoice_no" name="invoice_no" class="form-control font-mono" value="{{ $defaultInvoice }}" readonly style="background-color: #f8fafc; font-weight: 700;">
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label" for="customer_name" style="margin-bottom: 0;">Nama Pelanggan / Perusahaan <span class="required">*</span></label>
                        @if(!Auth::user()->isCustomer() && isset($customers) && $customers->count() > 0)
                            <select id="registered_customer_select" class="form-select" style="font-size: 0.76rem; padding: 3px 8px; width: auto; max-width: 180px;" onchange="handleSelectRegisteredCustomer(this)">
                                <option value="">-- Pilih Akun Terdaftar --</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->name }}" data-phone="{{ $cust->phone }}">{{ $cust->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    @if(Auth::user()->isCustomer())
                        <input type="text" id="customer_name" name="customer_name" class="form-control font-weight-bold" value="{{ Auth::user()->name }}" readonly style="background-color: #f8fafc; font-weight: 700;">
                    @else
                        <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" placeholder="Ketik nama pembeli atau pilih dari akun" required list="customerList">
                        <datalist id="customerList">
                            @if(isset($customers))
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->name }}">{{ $cust->phone ? '('.$cust->phone.')' : '' }}</option>
                                @endforeach
                            @endif
                        </datalist>
                    @endif
                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="customer_phone">Nomor Telepon / PIC</label>
                    <input type="text" id="customer_phone" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', Auth::user()->isCustomer() ? Auth::user()->phone : '') }}" placeholder="08xxxxxxxxxx" {{ Auth::user()->isCustomer() && Auth::user()->phone ? 'readonly style=background-color:#f8fafc;' : '' }}>
                    @error('customer_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="sale_date">Tanggal Penjualan <span class="required">*</span></label>
                    <input type="date" id="sale_date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                    @error('sale_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Product Selection Table -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 0.95rem; margin-bottom: 0;">
                        <i class="fa-solid fa-boxes-stacked me-1"></i> Rincian Produk Dijual (Stock-Out)
                    </label>
                    <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                        <i class="fa-solid fa-plus"></i> Tambah Baris Produk
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="itemsTable" style="border: 1px solid var(--border); border-radius: 8px;">
                        <thead>
                            <tr style="background-color: #f1f5f9;">
                                <th style="width: 42%;">Pilih Produk (Hanya Stok > 0)</th>
                                <th style="width: 14%;">Stok Tersedia</th>
                                <th style="width: 14%;">Kuantitas Beli</th>
                                <th style="width: 15%;">Harga Jual (Rp)</th>
                                <th style="width: 15%;">Subtotal (Rp)</th>
                                <th style="width: 5%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <!-- Injected by JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #fafbfc; font-weight: 700;">
                                <td colspan="4" style="text-align: right; padding: 16px;">TOTAL TRANSAKSI:</td>
                                <td colspan="2" class="font-mono" style="font-size: 1.25rem; color: #059669; padding: 16px;">
                                    Rp <span id="grandTotalDisplay">0</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment & Notes Section -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px; background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border);">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="payment_method">Metode Pembayaran <span class="required">*</span></label>
                    <select id="payment_method" name="payment_method" class="form-select" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="tempo" {{ old('payment_method') == 'tempo' ? 'selected' : '' }}>Tempo / Kredit</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="paid_amount">Nominal Pembayaran Diterima (Rp)</label>
                    <input type="number" step="0.01" min="0" id="paid_amount" name="paid_amount" class="form-control font-mono" value="{{ old('paid_amount') }}" placeholder="Contoh: 1000000" oninput="calculateChange()">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Kembalian (Rp)</label>
                    <div class="font-mono" id="changeDisplay" style="padding: 10px 14px; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-md); font-weight: 700; color: #0284c7;">
                        Rp 0
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Catatan Transaksi / Instruksi Pasang & Kirim (Opsional)</label>
                <textarea id="notes" name="notes" rows="2" class="form-control" placeholder="Pasang langsung di bengkel, kirim via Gosend/JNE, tipe/plat nomor motor konsumen...">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; justify-content: space-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    @if(Auth::user()->isCustomer())
                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Pemesanan & Dapatkan Faktur
                    @else
                        <i class="fa-solid fa-receipt me-1"></i> Selesaikan Penjualan & Cetak Faktur
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Product options template for JS -->
<template id="productOptionsTemplate">
    <option value="">-- Pilih Produk --</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}" data-unit="{{ $product->unit }}">
            [{{ $product->sku }}] {{ $product->name }} (Sisa: {{ $product->stock }} {{ $product->unit }})
        </option>
    @endforeach
</template>

@push('scripts')
<script>
    let rowIndex = 0;
    const itemsBody = document.getElementById('itemsBody');
    const optionsHtml = document.getElementById('productOptionsTemplate').innerHTML;

    function addRow(productId = '', quantity = 1, unitPrice = 0) {
        const tr = document.createElement('tr');
        tr.id = `row-${rowIndex}`;
        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required onchange="handleProductChange(this, ${rowIndex})">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <span class="stock-badge badge badge-info" id="stock-badge-${rowIndex}">-</span>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="number" min="1" name="items[${rowIndex}][quantity]" class="form-control qty-input font-mono" value="${quantity}" required oninput="handleQtyInput(${rowIndex})">
                    <span class="unit-label" style="font-size: 0.75rem; color: #64748b; min-width: 25px;">pcs</span>
                </div>
                <div class="stock-error" id="stock-error-${rowIndex}" style="display: none; color: var(--danger); font-size: 0.72rem; margin-top: 3px; font-weight: 600;">
                    Melebihi stok!
                </div>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${rowIndex}][unit_price]" class="form-control price-input font-mono" value="${unitPrice}" required oninput="calculateSubtotal(${rowIndex})">
            </td>
            <td>
                <span class="font-mono subtotal-display" id="subtotal-${rowIndex}" style="font-weight: 700; color: #059669;">Rp 0</span>
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
        const currentStock = parseInt(selectedOption.getAttribute('data-stock') || 0);
        const unit = selectedOption.getAttribute('data-unit') || 'pcs';

        const row = document.getElementById(`row-${idx}`);
        const priceInput = row.querySelector('.price-input');
        const unitLabel = row.querySelector('.unit-label');
        const stockBadge = document.getElementById(`stock-badge-${idx}`);
        const qtyInput = row.querySelector('.qty-input');

        priceInput.value = parseFloat(defaultPrice).toFixed(0);
        unitLabel.innerText = unit;
        stockBadge.innerText = `${currentStock} ${unit}`;
        qtyInput.max = currentStock;

        handleQtyInput(idx);
    }

    function handleQtyInput(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (!row) return;

        const select = row.querySelector('.product-select');
        const selectedOption = select.options[select.selectedIndex];
        const currentStock = parseInt(selectedOption.getAttribute('data-stock') || 0);
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        const stockError = document.getElementById(`stock-error-${idx}`);

        if (currentStock > 0 && qty > currentStock) {
            stockError.style.display = 'block';
            stockError.innerText = `Maksimal: ${currentStock}`;
        } else {
            stockError.style.display = 'none';
        }

        calculateSubtotal(idx);
    }

    function calculateSubtotal(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (!row) return;

        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = Math.max(0, qty * price);

        row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#itemsBody tr');
        rows.forEach(r => {
            const qty = parseFloat(r.querySelector('.qty-input').value) || 0;
            const price = parseFloat(r.querySelector('.price-input').value) || 0;
            total += (qty * price);
        });

        document.getElementById('grandTotalDisplay').innerText = total.toLocaleString('id-ID');
        calculateChange();
    }

    function calculateChange() {
        const totalText = document.getElementById('grandTotalDisplay').innerText.replace(/\./g, '');
        const grandTotal = parseFloat(totalText) || 0;
        const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;

        const change = Math.max(0, paidAmount - grandTotal);
        document.getElementById('changeDisplay').innerText = 'Rp ' + change.toLocaleString('id-ID');
    }

    function handleSelectRegisteredCustomer(selectElem) {
        if (!selectElem.value) return;
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const phone = selectedOption.getAttribute('data-phone') || '';
        document.getElementById('customer_name').value = selectElem.value;
        if (phone) {
            document.getElementById('customer_phone').value = phone;
        }
    }

    document.getElementById('addItemBtn').addEventListener('click', () => {
        addRow();
    });

    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>
@endpush
@endsection
