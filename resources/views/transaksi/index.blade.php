<x-layouts.app :pageTitle="'Transaksi Kasir'" :breadcrumb="'Operasional'">
    <div class="flex flex-col lg:flex-row h-full overflow-y-auto lg:overflow-hidden animate-fade-in relative">
        <!-- KIRI: Katalog Menu -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:pr-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-[#1C1F1E]">Transaksi kasir</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Pilih menu, periksa pesanan, lalu selesaikan pembayaran.</p>
                </div>
                <div class="flex items-center">
                    <span class="text-xs text-gray-500 bg-white border border-[#E2DDD6] px-3 py-1.5 rounded-full font-medium shadow-2xs">
                        Kasir: <span class="font-bold text-[#244B3B]">{{ auth()->user()->name }}</span>
                    </span>
                </div>
            </div>

            <!-- Search -->
            <div class="relative mb-3.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama menu..."
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#E2DDD6] bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] shadow-2xs">
            </div>

            <!-- Filter Kategori -->
            <div class="flex gap-2 mb-4 flex-wrap">
                <a href="{{ route('transaksi.index') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all btn-interactive {{ !request('category') ? 'bg-[#244B3B] text-white shadow-xs' : 'bg-white border border-[#E2DDD6] text-gray-600 hover:bg-[#F5F3EE]' }}">
                    Semua menu
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('transaksi.index', ['category' => $cat->slug, 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all btn-interactive {{ request('category') === $cat->slug ? 'bg-[#244B3B] text-white shadow-xs' : 'bg-white border border-[#E2DDD6] text-gray-600 hover:bg-[#F5F3EE]' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>

            <!-- Menu Grid Header -->
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-[#1C1F1E]">Menu tersedia</h2>
                <span class="text-xs text-gray-400 font-medium">Menampilkan {{ $products->count() }} dari {{ $totalProducts }} menu</span>
            </div>

            <!-- Grid Menu (1 baris 4 produk di desktop & tablet landscape agar foto tidak gepeng) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5" id="menuGrid">
                @forelse($products as $product)
                <div class="bg-white rounded-2xl border border-[#E2DDD6] overflow-hidden hover:border-[#244B3B]/40 hover:shadow-md transition-all group cursor-pointer card-hover flex flex-col"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->stock }}"
                     onclick="handleCardClick(this)">
                    <!-- Foto Produk (Aspek Rasio 4:3 Tinggi Proporsional, Tidak Gepeng) -->
                    <div class="aspect-[4/3] w-full bg-[#F5F3EE] relative overflow-hidden flex-shrink-0">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-[#F5F1EB] to-[#EAE4D9]">
                            <div class="w-12 h-12 rounded-2xl bg-white/80 shadow-2xs flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                                {{ $product->category->slug === 'kopi' ? '☕' : ($product->category->slug === 'non-kopi' ? '🍵' : '🥐') }}
                            </div>
                            <span class="text-[9px] text-gray-500 font-bold mt-1.5 uppercase tracking-wider">{{ $product->category->name }}</span>
                        </div>
                        @endif
                        <!-- Badges -->
                        <div class="absolute top-2.5 left-2.5 flex gap-1">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wide bg-white/95 text-gray-700 shadow-2xs">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-medium bg-white/95 text-gray-600 shadow-2xs">
                                Stok {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="p-3.5 flex flex-col justify-between flex-1">
                        <div class="font-bold text-sm text-[#1C1F1E] mb-2 leading-snug line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</div>
                        <div class="flex items-center justify-between mt-auto pt-1">
                            <span class="text-sm font-extrabold text-[#1C1F1E]">{{ $product->formattedPrice() }}</span>
                            <button type="button"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}"
                                    onclick="event.stopPropagation(); handleAddButtonClick(this);"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#244B3B] text-white text-xs font-semibold hover:bg-[#1a3a2d] transition-all shadow-xs btn-interactive">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center py-12 text-gray-400 bg-white rounded-2xl border border-[#E2DDD6]">
                    <div class="text-4xl mb-2">☕</div>
                    <p class="text-sm font-medium">Tidak ada menu yang ditemukan.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination & Note -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-5">
                <p class="text-xs text-gray-400 font-medium">
                    @if($hiddenProducts > 0)
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-3.5 h-3.5">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ $hiddenProducts }} menu habis disembunyikan dari katalog. Stok diperbarui setelah pembayaran.
                    </span>
                    @endif
                </p>
                <div class="flex gap-1 flex-wrap">
                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold transition-all btn-interactive {{ $products->currentPage() === $page ? 'bg-[#244B3B] text-white shadow-xs' : 'bg-white border border-[#E2DDD6] text-gray-600 hover:bg-[#F5F3EE]' }}">
                        {{ $page }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Floating Mobile Cart Bar (Hanya tampil di layar ponsel kecil, tidak muncul di desktop & tidak menutupi tombol bayar) -->
        <div id="mobileCartBar" class="hidden lg:!hidden fixed bottom-4 left-4 right-4 z-40 bg-[#244B3B] text-white p-3.5 rounded-2xl shadow-xl items-center justify-between animate-slide-up">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-bold text-sm">
                    <span id="mobileCartCount">0</span>
                </div>
                <div>
                    <div class="text-[11px] text-white/80 font-medium">Pesanan Kasir</div>
                    <div class="text-base font-extrabold" id="mobileCartTotal">Rp0</div>
                </div>
            </div>
            <button onclick="scrollToCartPanel()" class="px-4 py-2 bg-white text-[#244B3B] rounded-xl text-xs font-bold hover:bg-[#F5F3EE] transition-all btn-interactive flex items-center gap-1.5">
                <span>Buka Pembayaran</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>

        <!-- KANAN / BAWAH: Panel Pesanan -->
        <div class="w-full lg:w-80 lg:min-w-80 bg-white border-t lg:border-t-0 lg:border-l border-[#E2DDD6] flex flex-col overflow-y-auto pb-16 lg:pb-6" id="cartPanel">
            <div class="p-4 border-b border-[#E2DDD6]">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-[#1C1F1E]">Pesanan <span id="orderNumber">#{{ $nextOrderNumber }}</span></h2>
                        <p class="text-xs text-gray-400">{{ now()->locale('id')->isoFormat('D MMM YYYY') }} · {{ now()->format('H.i') }}</p>
                    </div>
                    <button onclick="clearCart()" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Kosongkan Pesanan">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <polyline points="3 6 5 6 21 6"/><path d="m19 6-.867 12.142A2 2 0 0 1 16.138 20H7.862a2 2 0 0 1-1.995-1.858L5 6"/>
                            <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Order Type -->
            <div class="p-4 border-b border-[#E2DDD6]">
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <button type="button" onclick="setOrderType('dine_in')" id="btn-dine_in"
                            class="order-type-btn py-2 px-3 rounded-xl text-xs font-bold border-2 border-[#244B3B] bg-[#244B3B] text-white transition-all btn-interactive">
                        Makan di tempat
                    </button>
                    <button type="button" onclick="setOrderType('takeaway')" id="btn-takeaway"
                            class="order-type-btn py-2 px-3 rounded-xl text-xs font-bold border-2 border-[#E2DDD6] text-gray-600 hover:border-[#244B3B]/40 transition-all btn-interactive">
                        Bawa pulang
                    </button>
                </div>
                <div id="tableField">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>
                        </svg>
                        <input type="text" id="tableNumber" placeholder="Meja 04 · Pelanggan umum"
                               class="w-full pl-8 pr-3 py-2 rounded-lg border border-[#E2DDD6] text-xs focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="cartItems">
                <div id="emptyCart" class="text-center py-8">
                    <div class="text-3xl mb-2">🛒</div>
                    <p class="text-xs text-gray-400">Belum ada menu dipilih.<br>Klik menu di sebelah kiri untuk menambah.</p>
                </div>
            </div>

            <!-- Summary -->
            <div class="p-4 border-t border-[#E2DDD6]" id="cartSummary" style="display:none">
                <div class="space-y-1.5 mb-3">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Subtotal · <span id="itemCount">0</span> Item</span>
                        <span id="subtotalDisplay" class="font-medium text-[#1C1F1E]">Rp0</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Pajak (10%)</span>
                        <span id="taxDisplay" class="font-medium text-[#1C1F1E]">Rp0</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-[#1C1F1E] pt-1.5 border-t border-[#E2DDD6]">
                        <span>Total bayar</span>
                        <span class="text-[#244B3B] text-base font-extrabold" id="totalDisplay">Rp0</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <div class="text-xs font-semibold text-gray-600 mb-2">Metode pembayaran</div>
                    <div class="grid grid-cols-3 gap-1.5">
                        @foreach(['tunai' => 'Tunai', 'qris' => 'QRIS', 'debit' => 'Debit'] as $key => $label)
                        <button type="button" onclick="setPayment('{{ $key }}')" id="pay-{{ $key }}"
                                class="pay-btn py-2 rounded-xl text-xs font-bold border-2 transition-all btn-interactive {{ $key === 'tunai' ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-300' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Cash received (hanya jika tunai) -->
                <div id="cashSection" class="mb-3 animate-fade-in">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="text-[10px] text-gray-500 font-semibold mb-1">Uang diterima</div>
                            <input type="number" id="cashReceived" placeholder="Rp100000"
                                   oninput="calcChange()"
                                   class="w-full px-2.5 py-1.5 rounded-lg border border-[#E2DDD6] text-xs focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
                        </div>
                        <div>
                            <div class="text-[10px] text-gray-500 font-semibold mb-1">Kembalian</div>
                            <div id="changeDisplay" class="px-2.5 py-1.5 rounded-lg bg-[#F5F3EE] text-xs font-bold text-[#244B3B]">Rp0</div>
                        </div>
                    </div>
                </div>

                <!-- QRIS Section -->
                <div id="qrisSection" class="mb-3 p-3 bg-[#F8FAF9] rounded-xl border border-[#244B3B]/20 text-center animate-fade-in" style="display:none">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-[#244B3B] uppercase tracking-wider">QRIS Kopi Kita</span>
                        <button type="button" onclick="document.getElementById('qrisFileInput').click()" class="text-[10px] text-[#244B3B] hover:underline flex items-center gap-1 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Unggah QRIS
                        </button>
                        <input type="file" id="qrisFileInput" accept="image/*" class="hidden" onchange="uploadQrisImage(event)">
                    </div>

                    <!-- QR Display -->
                    <div class="relative inline-block bg-white p-2 rounded-lg border border-[#E2DDD6] shadow-sm mb-2 group">
                        <img id="qrisImagePreview"
                             src="{{ $qrisUrl ?? 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=KOPI_KITA_BANDUNG_QRIS' }}"
                             alt="QRIS Kopi Kita"
                             class="w-32 h-32 object-contain mx-auto cursor-pointer rounded"
                             onclick="openQrisModal()">
                        <div onclick="openQrisModal()" class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center text-white text-[10px] font-medium opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            🔍 Klik Perbesar
                        </div>
                    </div>

                    <div class="text-[11px] font-semibold text-[#1C1F1E]">Scan untuk bayar: <span id="qrisAmountText" class="text-[#244B3B] font-bold">Rp0</span></div>
                    <div class="text-[9px] text-gray-400 mt-0.5">Mendukung GoPay, OVO, Dana, BCA, Mandiri, dll.</div>
                </div>

                <!-- Debit Section -->
                <div id="debitSection" class="mb-3 p-3 bg-white rounded-xl border border-[#E2DDD6] animate-fade-in" style="display:none">
                    <div class="text-[11px] font-semibold text-[#1C1F1E] mb-2 flex items-center justify-between">
                        <span>Mesin EDC / Bank</span>
                        <span class="text-[10px] text-gray-400">Pilih EDC</span>
                    </div>

                    <!-- Bank Options -->
                    <div class="grid grid-cols-4 gap-1 mb-2.5">
                        @foreach(['BCA', 'Mandiri', 'BRI', 'BNI'] as $bank)
                        <button type="button" onclick="selectBank('{{ $bank }}')" id="bank-btn-{{ $bank }}"
                                class="bank-choice-btn py-1 px-1 rounded-lg text-[10px] font-bold border transition-all btn-interactive {{ $loop->first ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-400' }}">
                            {{ $bank }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedBank" value="BCA">

                    <!-- Reference / Card Number -->
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 font-semibold">No. Kartu / Ref. EDC (opsional)</label>
                        <input type="text" id="referenceNumber" placeholder="Contoh: 4 digit terakhir / Ref #12345"
                               class="w-full px-2.5 py-1.5 rounded-lg border border-[#E2DDD6] text-xs focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
                    </div>

                    <div class="mt-2 p-2 bg-[#F5F3EE] rounded-lg text-[10px] text-gray-600 flex items-center justify-between">
                        <span>Total gesek di EDC:</span>
                        <span class="font-bold text-[#244B3B]" id="debitAmountText">Rp0</span>
                    </div>
                </div>

                <!-- Bayar Button -->
                <button type="button" onclick="submitOrder()"
                        class="w-full py-3 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-xl font-bold text-sm transition-all shadow-sm hover:shadow-md card-hover btn-interactive flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <path d="m9 12 2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.86 0 3.59.56 5.03 1.52"/>
                    </svg>
                    <span>Bayar <span id="payBtnTotal">Rp0</span></span>
                </button>
                <p class="text-[10px] text-gray-400 text-center mt-2">Nota tersedia untuk dicetak setelah pembayaran.</p>
            </div>
        </div>
    </div>

    <!-- Modal Perbesar QRIS -->
    <div id="qrisModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 animate-fade-in" style="display:none" onclick="closeQrisModal()">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center shadow-2xl relative animate-scale-in" onclick="event.stopPropagation()">
            <button onclick="closeQrisModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="text-xs font-bold text-[#244B3B] uppercase tracking-wider mb-1">QRIS Pembayaran</div>
            <div class="text-lg font-bold text-[#1C1F1E] mb-3">Kopi Kita • Bandung</div>
            <div class="bg-[#F8FAF9] p-4 rounded-xl border border-[#244B3B]/20 inline-block mb-3 shadow-xs">
                <img id="modalQrisImage"
                     src="{{ $qrisUrl ?? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=KOPI_KITA_BANDUNG_QRIS' }}"
                     alt="QRIS Kopi Kita"
                     class="w-64 h-64 object-contain mx-auto rounded-lg">
            </div>
            <div class="text-sm font-bold text-[#1C1F1E]">Total Tagihan: <span id="modalQrisAmount" class="text-[#244B3B] font-extrabold">Rp0</span></div>
            <p class="text-xs text-gray-400 mt-1">Arahkan kamera / aplikasi e-wallet pelanggan ke kode QR</p>
            <button onclick="closeQrisModal()" class="mt-4 w-full py-2.5 bg-[#244B3B] text-white rounded-xl text-xs font-semibold hover:bg-[#1a3a2d] transition-all btn-interactive">Tutup</button>
        </div>
    </div>

    @push('scripts')
    <script>
        let cart = {};
        let orderType = 'dine_in';
        let paymentMethod = 'tunai';

        function handleCardClick(el) {
            const id = parseInt(el.dataset.id);
            const name = el.dataset.name;
            const price = parseInt(el.dataset.price);
            const stock = parseInt(el.dataset.stock);
            addToCart(id, name, price, stock);
        }

        function handleAddButtonClick(btn) {
            const id = parseInt(btn.dataset.id);
            const name = btn.dataset.name;
            const price = parseInt(btn.dataset.price);
            const stock = parseInt(btn.dataset.stock);
            addToCart(id, name, price, stock);
        }

        function scrollToCartPanel() {
            const panel = document.getElementById('cartPanel');
            const mobileBar = document.getElementById('mobileCartBar');
            if (panel) {
                panel.scrollIntoView({ behavior: 'smooth' });
            }
            if (mobileBar) {
                mobileBar.classList.add('hidden');
                mobileBar.classList.remove('flex');
            }
        }

        function updateMobileCartBar(totalQty, totalFormatted) {
            const mobileBar = document.getElementById('mobileCartBar');
            if (!mobileBar) return;

            // Hanya tampil di layar mobile sempit (< 1024px) jika ada item dan tidak sedang di panel bayar
            if (window.innerWidth < 1024 && totalQty > 0) {
                document.getElementById('mobileCartCount').textContent = totalQty;
                document.getElementById('mobileCartTotal').textContent = totalFormatted;
                mobileBar.classList.remove('hidden');
                mobileBar.classList.add('flex');
            } else {
                mobileBar.classList.add('hidden');
                mobileBar.classList.remove('flex');
            }
        }

        function setOrderType(type) {
            orderType = type;
            document.getElementById('btn-dine_in').className = 'order-type-btn py-2 px-3 rounded-xl text-xs font-bold border-2 transition-all btn-interactive ' +
                (type === 'dine_in' ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-300');
            document.getElementById('btn-takeaway').className = 'order-type-btn py-2 px-3 rounded-xl text-xs font-bold border-2 transition-all btn-interactive ' +
                (type === 'takeaway' ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-300');
            document.getElementById('tableField').style.display = type === 'dine_in' ? 'block' : 'none';
        }

        function setPayment(method) {
            paymentMethod = method;
            ['tunai', 'qris', 'debit'].forEach(m => {
                const btn = document.getElementById('pay-' + m);
                btn.className = 'pay-btn py-2 rounded-xl text-xs font-bold border-2 transition-all btn-interactive ' +
                    (m === method ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-300');
            });
            document.getElementById('cashSection').style.display = method === 'tunai' ? 'block' : 'none';
            document.getElementById('qrisSection').style.display = method === 'qris' ? 'block' : 'none';
            document.getElementById('debitSection').style.display = method === 'debit' ? 'block' : 'none';
        }

        function selectBank(bank) {
            document.getElementById('selectedBank').value = bank;
            ['BCA', 'Mandiri', 'BRI', 'BNI'].forEach(b => {
                const btn = document.getElementById('bank-btn-' + b);
                if (btn) {
                    btn.className = 'bank-choice-btn py-1 px-1 rounded-lg text-[10px] font-bold border transition-all btn-interactive ' +
                        (b === bank ? 'border-[#244B3B] bg-[#244B3B] text-white shadow-xs' : 'border-[#E2DDD6] text-gray-600 hover:border-gray-400');
                }
            });
        }

        function openQrisModal() {
            document.getElementById('qrisModal').style.display = 'flex';
        }

        function closeQrisModal() {
            document.getElementById('qrisModal').style.display = 'none';
        }

        async function uploadQrisImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('qris_image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const res = await fetch('{{ route('transaksi.qris-upload') }}', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('qrisImagePreview').src = data.url;
                    document.getElementById('modalQrisImage').src = data.url;
                    alert('✅ Gambar QRIS berhasil diperbarui!');
                } else {
                    alert('Gagal mengunggah QRIS: ' + (data.message || 'Error'));
                }
            } catch (err) {
                alert('Gagal mengunggah QRIS.');
            }
        }

        function formatRp(amount) {
            return 'Rp' + amount.toLocaleString('id-ID');
        }

        function addToCart(id, name, price, stock) {
            if (cart[id]) {
                if (cart[id].qty >= stock) {
                    alert('Stok ' + name + ' tidak mencukupi.');
                    return;
                }
                cart[id].qty++;
            } else {
                cart[id] = { id, name, price, stock, qty: 1 };
            }
            renderCart();
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }

        function removeItem(id) {
            delete cart[id];
            renderCart();
        }

        function clearCart() {
            cart = {};
            renderCart();
        }

        function renderCart() {
            const items = Object.values(cart);
            const emptyCart = document.getElementById('emptyCart');
            const cartItemsDiv = document.getElementById('cartItems');
            const cartSummary = document.getElementById('cartSummary');

            if (items.length === 0) {
                emptyCart.style.display = 'block';
                cartSummary.style.display = 'none';
                const mobileBar = document.getElementById('mobileCartBar');
                if (mobileBar) {
                    mobileBar.classList.add('hidden');
                    mobileBar.classList.remove('flex');
                }
                cartItemsDiv.innerHTML = '';
                cartItemsDiv.appendChild(emptyCart);
                return;
            }

            emptyCart.style.display = 'none';
            cartSummary.style.display = 'block';

            let html = '';
            let subtotal = 0;
            let totalQty = 0;

            items.forEach(item => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;
                totalQty += item.qty;
                html += `
                <div class="flex items-start justify-between gap-2 p-2 rounded-xl hover:bg-[#FAFAF8] transition-colors">
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-[#1C1F1E] truncate">${item.name}</div>
                        <div class="text-[10px] text-gray-500">${formatRp(item.price)}</div>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded-lg border border-[#E2DDD6] text-gray-700 hover:bg-[#F5F3EE] text-xs font-bold flex items-center justify-center transition-all btn-interactive">-</button>
                        <span class="w-6 text-center text-xs font-bold text-[#1C1F1E]">${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded-lg border border-[#E2DDD6] text-gray-700 hover:bg-[#F5F3EE] text-xs font-bold flex items-center justify-center transition-all btn-interactive">+</button>
                        <button onclick="removeItem(${item.id})" class="w-6 h-6 rounded-lg text-gray-400 hover:text-red-500 text-xs flex items-center justify-center ml-1 transition-colors" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5">
                                <polyline points="3 6 5 6 21 6"/><path d="m19 6-.867 12.142A2 2 0 0 1 16.138 20H7.862a2 2 0 0 1-1.995-1.858L5 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="text-right text-xs font-bold text-[#1C1F1E] px-2 -mt-1 pb-1 border-b border-[#F5F3EE]">${formatRp(itemTotal)}</div>
                `;
            });

            // Re-render
            const existing = cartItemsDiv.querySelectorAll('.cart-line');
            existing.forEach(e => e.remove());
            const wrapper = document.createElement('div');
            wrapper.className = 'cart-line space-y-2';
            wrapper.innerHTML = html;
            cartItemsDiv.appendChild(wrapper);

            const tax = Math.round(subtotal * 0.1);
            const total = subtotal + tax;

            document.getElementById('itemCount').textContent = totalQty;
            document.getElementById('subtotalDisplay').textContent = formatRp(subtotal);
            document.getElementById('taxDisplay').textContent = formatRp(tax);
            document.getElementById('totalDisplay').textContent = formatRp(total);
            document.getElementById('payBtnTotal').textContent = formatRp(total);
            if (document.getElementById('qrisAmountText')) document.getElementById('qrisAmountText').textContent = formatRp(total);
            if (document.getElementById('modalQrisAmount')) document.getElementById('modalQrisAmount').textContent = formatRp(total);
            if (document.getElementById('debitAmountText')) document.getElementById('debitAmountText').textContent = formatRp(total);

            // Update mobile floating bar (Hanya di layar ponsel kecil, otomatis disembunyikan di desktop)
            updateMobileCartBar(totalQty, formatRp(total));

            calcChange();
        }

        function calcChange() {
            const items = Object.values(cart);
            if (items.length === 0) return;
            const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
            const tax = Math.round(subtotal * 0.1);
            const total = subtotal + tax;
            const received = parseInt(document.getElementById('cashReceived').value) || 0;
            const change = received - total;
            document.getElementById('changeDisplay').textContent = change >= 0 ? formatRp(change) : 'Kurang ' + formatRp(Math.abs(change));
        }

        async function submitOrder() {
            const items = Object.values(cart);
            if (items.length === 0) {
                alert('Pilih menu terlebih dahulu.');
                return;
            }

            const tableNumber = document.getElementById('tableNumber').value;
            const cashReceived = paymentMethod === 'tunai' ? parseInt(document.getElementById('cashReceived').value) || null : null;
            const bankName = paymentMethod === 'debit' ? document.getElementById('selectedBank').value : null;
            const referenceNumber = paymentMethod === 'debit' ? document.getElementById('referenceNumber').value : null;

            const payload = {
                order_type: orderType,
                table_number: tableNumber,
                payment_method: paymentMethod,
                bank_name: bankName,
                reference_number: referenceNumber,
                cash_received: cashReceived,
                items: items.map(i => ({ product_id: i.id, quantity: i.qty })),
                _token: document.querySelector('meta[name="csrf-token"]').content,
            };

            try {
                const res = await fetch('{{ route('transaksi.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': payload._token },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.success) {
                    alert('✅ Transaksi ' + data.order_number + ' berhasil!');
                    window.open('/transaksi/' + data.order_id + '/struk', '_blank');
                    clearCart();
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (e) {
                alert('Gagal terhubung ke server.');
            }
        }

        // Search with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set('search', this.value);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 400);
        });

        // Event listener saat ukuran jendela berubah (desktop vs mobile)
        window.addEventListener('resize', () => {
            const items = Object.values(cart);
            if (items.length > 0) {
                const totalQty = items.reduce((s, i) => s + i.qty, 0);
                const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
                const tax = Math.round(subtotal * 0.1);
                updateMobileCartBar(totalQty, formatRp(subtotal + tax));
            } else {
                const mobileBar = document.getElementById('mobileCartBar');
                if (mobileBar) {
                    mobileBar.classList.add('hidden');
                    mobileBar.classList.remove('flex');
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
