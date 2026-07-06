@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-120px)]">
    
    <div class="flex-1 flex flex-col h-full bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-3 bg-surface">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchProduct" onkeyup="filterProducts()" placeholder="Cari nama barang atau kode..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition-all text-slate-700">
            </div>
            <select id="categoryFilter" onchange="filterProducts()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm transition-all text-slate-700 font-medium">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ strtolower($cat->name) }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 overflow-y-auto p-4 bg-slate-50/50">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productGrid">
                @foreach($products as $prod)
                <div class="bg-white border border-slate-200 p-3 rounded-2xl cursor-pointer hover:border-primary hover:shadow-md transition-all product-card flex flex-col h-full group" 
                     data-name="{{ strtolower($prod->name) }}" 
                     data-code="{{ strtolower($prod->code) }}" 
                     data-category="{{ strtolower($prod->category->name ?? 'uncategorized') }}"
                     onclick='openInputModal(@json($prod))'>
                    
                    <div class="w-full h-32 mb-3 bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center border border-slate-100">
                        @if($prod->image)
                            <img src="{{ asset('storage/' . $prod->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $prod->name }}">
                        @else
                            <i class="fas fa-image text-3xl text-slate-300"></i>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-primary transition-colors">{{ $prod->code }}</span>
                        <span class="text-[10px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md font-bold border border-emerald-100">
                            Sisa: {{ fmod($prod->stock, 1) !== 0.00 ? rtrim(rtrim($prod->stock, '0'), '.') : number_format($prod->stock, 0) }} {{ $prod->baseUnit->short_name ?? '' }}
                        </span>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 text-sm mb-1 flex-1 leading-tight group-hover:text-primary transition-colors line-clamp-2">{{ $prod->name }}</h3>
                    <p class="text-primary font-bold text-base mt-1">Rp {{ number_format($prod->price, 0, ',', '.') }}<span class="text-[10px] text-slate-400 font-normal"> / {{ $prod->baseUnit->short_name ?? 'pcs' }}</span></p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-96 flex flex-col bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm h-full shrink-0">
        <div class="p-4 bg-primary text-white flex justify-between items-center shrink-0">
            <h2 class="font-bold text-lg"><i class="fas fa-shopping-cart mr-2"></i> Keranjang</h2>
            <button onclick="clearCart()" class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-xl transition-colors font-medium flex items-center gap-1.5">
                <i class="fas fa-trash-alt"></i> Kosongkan
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/30" id="cartList">
            <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                    <i class="fas fa-cart-arrow-down text-2xl text-slate-400"></i>
                </div>
                <p class="text-sm font-medium">Belum ada barang dipilih</p>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-white shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-slate-500 text-sm uppercase tracking-wider">Total Tagihan</span>
                <span class="font-bold text-2xl text-primary" id="totalAmountLabel">Rp 0</span>
            </div>
            <button onclick="openCheckoutModal()" class="w-full bg-primary hover:bg-[#4a332c] text-white py-3.5 rounded-xl font-bold text-base transition-all shadow-md flex justify-center items-center gap-2">
                <i class="fas fa-cash-register"></i> Proses Pembayaran
            </button>
        </div>
    </div>
</div>

<div id="modalInputItem" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform shadow-2xl" id="modalInputItemContent">
        <div class="p-5 border-b border-slate-100 bg-surface">
            <h3 class="text-base font-bold text-primary" id="modalInputTitle">Pilih Jumlah</h3>
            <p class="text-xs text-slate-500 mt-1">Stok Gudang: <span id="modalInputStock" class="font-bold text-emerald-600">0</span></p>
        </div>
        
        <div class="p-6 space-y-4">
            <input type="hidden" id="tempProdId">
            <input type="hidden" id="tempProdName">
            <input type="hidden" id="tempProdImage"> <input type="hidden" id="tempBasePrice">
            <input type="hidden" id="tempMaxStock">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Jumlah (Qty)</label>
                    <input type="number" id="inputQty" step="0.01" min="0.01" onkeyup="calcSubtotalItem()" onchange="calcSubtotalItem()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-lg font-bold text-slate-800 transition-all" value="1">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Satuan</label>
                    <select id="inputUnit" onchange="calcSubtotalItem()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm font-bold text-slate-800 transition-all">
                    </select>
                </div>
            </div>

            <div class="p-4 bg-primary/5 rounded-xl border border-primary/10 text-right">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-1">Subtotal</span>
                <h2 class="text-2xl font-bold text-primary" id="modalInputSubtotal">Rp 0</h2>
            </div>

            <div class="flex justify-end gap-3 mt-2">
                <button onclick="closeModal('modalInputItem')" class="px-5 py-3 text-slate-600 text-sm font-semibold hover:bg-slate-100 rounded-xl">Batal</button>
                <button onclick="confirmAddToCart()" class="px-5 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-[#4a332c] shadow-md flex items-center gap-2">
                    <i class="fas fa-cart-plus"></i> Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modalCheckout" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all opacity-0">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 overflow-hidden transform scale-95 transition-transform shadow-2xl" id="modalCheckoutContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-surface">
            <h3 class="text-base font-bold text-primary">Konfirmasi Pembayaran</h3>
            <button onclick="closeModal('modalCheckout')" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fas fa-times"></i></button>
        </div>
        
        <form action="{{ route('kasir.pos.store') }}" method="POST" class="p-6 space-y-5" onsubmit="return validateCheckout()">
            @csrf
            <input type="hidden" name="cart_data" id="formCartData">
            <input type="hidden" name="total_amount" id="formTotalAmount">

            <div class="text-center p-5 bg-primary/5 rounded-xl border border-primary/10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Total Tagihan</p>
                <h2 class="text-3xl font-bold text-primary" id="checkoutTotalLabel">Rp 0</h2>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Uang Diterima (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                    <input type="number" name="cash_given" id="cashInput" onkeyup="calculateChange()" required class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-xl font-bold text-slate-800 transition-all" placeholder="0">
                </div>
            </div>

            <div class="flex justify-between items-center px-2 py-3 bg-slate-50 rounded-lg border border-slate-100">
                <span class="text-sm font-bold text-slate-500">Kembalian</span>
                <span class="text-xl font-bold text-slate-400" id="changeLabel">Rp 0</span>
            </div>

            <button type="submit" id="btnSubmitPayment" disabled class="w-full px-5 py-3.5 bg-primary text-white text-base font-bold rounded-xl hover:bg-[#4a332c] transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed mt-2 flex justify-center items-center gap-2">
                <i class="fas fa-print"></i> Bayar & Cetak Struk
            </button>
        </form>
    </div>
</div>

<script>
    let cart = [];
    let totalAmount = 0;
    const formatRp = (angka) => new Intl.NumberFormat('id-ID').format(angka);

    function openModal(id) {
        const m = document.getElementById(id), c = document.getElementById(id+'Content');
        m.classList.remove('hidden'); m.classList.add('flex');
        setTimeout(() => { m.classList.remove('opacity-0'); c.classList.remove('scale-95'); }, 10);
    }
    function closeModal(id) {
        const m = document.getElementById(id), c = document.getElementById(id+'Content');
        m.classList.add('opacity-0'); c.classList.add('scale-95');
        setTimeout(() => { m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
    }

    function filterProducts() {
        let search = document.getElementById('searchProduct').value.toLowerCase();
        let category = document.getElementById('categoryFilter').value;
        let cards = document.getElementsByClassName('product-card');

        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].getAttribute('data-name');
            let code = cards[i].getAttribute('data-code');
            let cat = cards[i].getAttribute('data-category');

            let matchSearch = name.includes(search) || code.includes(search);
            let matchCategory = (category === 'all' || cat === category);
            cards[i].style.display = (matchSearch && matchCategory) ? "flex" : "none";
        }
    }

    function openInputModal(product) {
        document.getElementById('modalInputTitle').innerText = product.name;
        let stockVal = product.stock % 1 !== 0 ? product.stock : Math.floor(product.stock);
        document.getElementById('modalInputStock').innerText = stockVal + ' ' + (product.base_unit ? product.base_unit.short_name : '');
        
        document.getElementById('tempProdId').value = product.id;
        document.getElementById('tempProdName').value = product.name;
        // Menyimpan path foto jika ada
        document.getElementById('tempProdImage').value = product.image ? '{{ asset("storage") }}/' + product.image : ''; 
        document.getElementById('tempBasePrice').value = product.price;
        document.getElementById('tempMaxStock').value = product.stock;
        document.getElementById('inputQty').value = 1;

        let selectHTML = `<option value="1" data-name="${product.base_unit ? product.base_unit.short_name : 'Pcs'}">${product.base_unit ? product.base_unit.name : 'Satuan Dasar'} (Ecer)</option>`;
        
        if (product.conversions && product.conversions.length > 0) {
            product.conversions.forEach(conv => {
                selectHTML += `<option value="${conv.multiplier}" data-name="${conv.unit ? conv.unit.short_name : 'Grosir'}">${conv.unit ? conv.unit.name : 'Grosir'} (Isi ${conv.multiplier})</option>`;
            });
        }
        document.getElementById('inputUnit').innerHTML = selectHTML;

        calcSubtotalItem();
        openModal('modalInputItem');
        setTimeout(() => { document.getElementById('inputQty').focus(); }, 300);
    }

    function calcSubtotalItem() {
        let qty = parseFloat(document.getElementById('inputQty').value) || 0;
        let basePrice = parseFloat(document.getElementById('tempBasePrice').value) || 0;
        let unitDropdown = document.getElementById('inputUnit');
        let multiplier = parseFloat(unitDropdown.options[unitDropdown.selectedIndex].value) || 1;

        let subtotal = qty * multiplier * basePrice;
        document.getElementById('modalInputSubtotal').innerText = 'Rp ' + formatRp(subtotal);
    }

    function confirmAddToCart() {
        let id = document.getElementById('tempProdId').value;
        let name = document.getElementById('tempProdName').value;
        let image = document.getElementById('tempProdImage').value; // Mengambil path image
        let basePrice = parseFloat(document.getElementById('tempBasePrice').value);
        let maxStock = parseFloat(document.getElementById('tempMaxStock').value);
        
        let inputQty = parseFloat(document.getElementById('inputQty').value) || 0;
        let unitDropdown = document.getElementById('inputUnit');
        let multiplier = parseFloat(unitDropdown.options[unitDropdown.selectedIndex].value);
        let unitName = unitDropdown.options[unitDropdown.selectedIndex].getAttribute('data-name');

        if (inputQty <= 0) {
            Swal.fire('Oops', 'Jumlah tidak boleh kosong!', 'warning'); return;
        }

        let qtyInBase = inputQty * multiplier;
        let subtotal = qtyInBase * basePrice;

        if (qtyInBase > maxStock) {
            Swal.fire('Stok Terbatas!', `Sisa stok di gudang hanya ${maxStock}. Transaksi Anda membutuhkan ${qtyInBase}.`, 'error'); return;
        }

        let cartId = id + '_' + multiplier; 
        let existing = cart.find(i => i.cartId === cartId);

        if (existing) {
            existing.inputQty += inputQty;
            existing.qty_in_base = existing.inputQty * multiplier;
            existing.subtotal = existing.qty_in_base * basePrice;
            if (existing.qty_in_base > maxStock) {
                Swal.fire('Error', 'Melebihi stok gudang jika ditambah lagi!', 'error');
                existing.inputQty -= inputQty; 
                existing.qty_in_base = existing.inputQty * multiplier;
                existing.subtotal = existing.qty_in_base * basePrice;
                return;
            }
        } else {
            // Memasukkan 'image' ke dalam array cart
            cart.push({ cartId: cartId, id: id, name: name, image: image, inputQty: inputQty, unitName: unitName, multiplier: multiplier, qty_in_base: qtyInBase, subtotal: subtotal });
        }

        closeModal('modalInputItem');
        renderCart();
    }

    function removeCartItem(cartId) {
        cart = cart.filter(i => i.cartId !== cartId);
        renderCart();
    }

    function renderCart() {
        let cartList = document.getElementById('cartList');
        totalAmount = 0;

        if (cart.length === 0) {
            cartList.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-70"><div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3"><i class="fas fa-cart-arrow-down text-2xl text-slate-400"></i></div><p class="text-sm font-medium">Belum ada barang dipilih</p></div>`;
            document.getElementById('totalAmountLabel').innerText = 'Rp 0';
            return;
        }

        let html = '';
        cart.forEach(item => {
            totalAmount += item.subtotal;
            
            // 👇 Render Thumbnail Mini di Keranjang 👇
            let thumbHtml = item.image 
                ? `<img src="${item.image}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">` 
                : `<div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 shrink-0"><i class="fas fa-box"></i></div>`;

            html += `
            <div class="bg-white border border-slate-200 p-3 rounded-xl shadow-sm flex items-center gap-3 relative group transition-all hover:border-primary/30">
                ${thumbHtml}
                <div class="flex-1 min-w-0 pr-6">
                    <h4 class="text-sm font-bold text-slate-800 leading-tight truncate">${item.name}</h4>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">${item.inputQty} ${item.unitName}</span>
                        <p class="text-xs font-bold text-primary">Rp ${formatRp(item.subtotal)}</p>
                    </div>
                </div>
                <button onclick="removeCartItem('${item.cartId}')" class="absolute top-1/2 -translate-y-1/2 right-3 w-7 h-7 flex items-center justify-center rounded bg-red-50 text-red-400 hover:text-white hover:bg-red-500 transition-colors"><i class="fas fa-times text-xs"></i></button>
            </div>`;
        });

        cartList.innerHTML = html;
        document.getElementById('totalAmountLabel').innerText = 'Rp ' + formatRp(totalAmount);
    }

    function clearCart() {
        if(cart.length > 0) {
            Swal.fire({ title: 'Kosongkan?', text: "Semua barang akan dihapus.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Kosongkan' })
            .then((res) => { if (res.isConfirmed) { cart = []; renderCart(); } });
        }
    }

    function openCheckoutModal() {
        if(cart.length === 0) { Swal.fire('Kosong', 'Pilih barang dulu!', 'warning'); return; }
        
        document.getElementById('checkoutTotalLabel').innerText = 'Rp ' + formatRp(totalAmount);
        document.getElementById('formCartData').value = JSON.stringify(cart);
        document.getElementById('formTotalAmount').value = totalAmount;
        document.getElementById('cashInput').value = '';
        document.getElementById('changeLabel').innerText = 'Rp 0';
        document.getElementById('btnSubmitPayment').disabled = true;

        openModal('modalCheckout');
        setTimeout(() => { document.getElementById('cashInput').focus(); }, 350);
    }

    function calculateChange() {
        let cash = parseInt(document.getElementById('cashInput').value) || 0;
        let change = cash - totalAmount;
        let btn = document.getElementById('btnSubmitPayment');
        let label = document.getElementById('changeLabel');

        if (change >= 0) {
            label.innerText = 'Rp ' + formatRp(change);
            label.className = 'text-xl font-bold text-emerald-600';
            btn.disabled = false;
        } else {
            label.innerText = 'Uang Kurang!';
            label.className = 'text-xl font-bold text-red-500';
            btn.disabled = true;
        }
    }

    function validateCheckout() {
        if (cart.length === 0 || (parseInt(document.getElementById('cashInput').value) || 0) < totalAmount) return false;
        return true;
    }
</script>

@if(session('print_invoice'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success', title: 'Pembayaran Berhasil!',
            showCancelButton: true, confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk', cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) window.open("{{ route('kasir.pos.print', session('print_invoice')) }}", "_blank");
        });
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire('Gagal!', '{!! addslashes($errors->first()) !!}', 'error');
    });
</script>
@endif
@endsection