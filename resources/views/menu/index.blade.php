<x-layouts.app :pageTitle="'Data Menu'" :breadcrumb="'Operasional'">
    <div class="flex flex-col lg:flex-row h-full overflow-y-auto lg:overflow-hidden animate-fade-in">
        <!-- KIRI: Daftar Menu -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:pr-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-[#1C1F1E]">Manajemen data menu</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Atur menu, harga, dan ketersediaan stok dalam satu tempat.</p>
                </div>
                <button type="button" onclick="openAddForm()"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-xl text-sm font-semibold transition-all shadow-xs card-hover btn-interactive">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah menu
                </button>
            </div>

            <!-- Stats bar -->
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-4">
                <span class="text-xs text-gray-600 font-semibold bg-white border border-[#E2DDD6] px-3 py-1 rounded-full">{{ $totalMenu }} total menu</span>
                <span class="text-xs text-[#244B3B] font-semibold bg-[#F0F7F3] border border-[#244B3B]/20 px-3 py-1 rounded-full">{{ $menuTersedia }} tersedia</span>
                <span class="text-xs text-red-600 font-semibold bg-red-50 border border-red-200 px-3 py-1 rounded-full">{{ $menuHabis }} stok habis</span>
                @if($lastUpdated)
                <span class="text-xs text-gray-400 sm:ml-auto">Terakhir diperbarui hari ini, {{ \Carbon\Carbon::parse($lastUpdated)->format('H.i') }}</span>
                @endif
            </div>

            <!-- Flash Error Message -->
            @if(isset($errors) && $errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 animate-slide-up shadow-2xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 flex-shrink-0">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>Gagal menyimpan data menu:</span>
                </div>
                <ul class="list-disc pl-6 text-xs space-y-0.5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Flash Success Message -->
            @if(session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 flex items-center gap-2.5 animate-slide-up shadow-2xs">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 flex-shrink-0">
                    <path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Table Card -->
            <div class="bg-white rounded-xl border border-[#E2DDD6] overflow-hidden shadow-xs">
                <!-- Search & Filter -->
                <div class="p-4 border-b border-[#E2DDD6] flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" placeholder="Cari nama menu..."
                               value="{{ request('search') }}"
                               oninput="filterMenu(this.value)"
                               class="w-full pl-9 pr-3 py-2 rounded-lg border border-[#E2DDD6] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]">
                    </div>
                    <select onchange="handleCategoryFilter(this.value)"
                            class="px-3 py-2 rounded-lg border border-[#E2DDD6] text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] text-gray-700 bg-white">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Responsive Table Wrapper -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm min-w-[560px]">
                        <thead>
                            <tr class="border-b border-[#F0EDE7] text-[10px] font-semibold uppercase tracking-wider text-gray-400 bg-[#FAFAF8]">
                                <th class="py-2.5 px-4 font-semibold">Nama</th>
                                <th class="py-2.5 px-3 font-semibold w-28">Kategori</th>
                                <th class="py-2.5 px-3 font-semibold w-28">Harga</th>
                                <th class="py-2.5 px-3 font-semibold w-28">Stok</th>
                                <th class="py-2.5 px-4 font-semibold w-32 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F5F3EE]" id="menuTableBody">
                            @forelse($products as $product)
                            <tr id="row-product-{{ $product->id }}"
                                class="product-row hover:bg-[#FAFAF8] transition-colors {{ $selectedProduct && $selectedProduct->id === $product->id ? 'bg-[#F0F7F3] border-l-4 border-l-[#244B3B]' : '' }}">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="w-8 h-8 rounded-lg object-cover border border-[#E2DDD6] flex-shrink-0">
                                        @else
                                        <div class="w-8 h-8 rounded-lg bg-[#F5F3EE] border border-[#E2DDD6] flex items-center justify-center text-sm flex-shrink-0">
                                            {{ $product->category->slug === 'kopi' ? '☕' : ($product->category->slug === 'non-kopi' ? '🍵' : '🥐') }}
                                        </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-semibold text-[#1C1F1E] truncate">{{ $product->name }}</div>
                                            <div class="text-[10px] {{ $product->is_active && $product->stock > 0 ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                                {{ $product->is_active && $product->stock > 0 ? '● Tersedia di kasir' : '○ Tidak tersedia' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-xs text-gray-600 font-medium">{{ $product->category->name }}</td>
                                <td class="py-3 px-3 text-xs sm:text-sm font-bold text-[#1C1F1E]">{{ $product->formattedPrice() }}</td>
                                <td class="py-3 px-3">
                                    @if($product->isOutOfStock())
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">0 {{ $product->unit }} · Habis</span>
                                    @elseif($product->isLowStock())
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700">{{ $product->stock }} {{ $product->unit }} · Menipis</span>
                                    @else
                                    <span class="text-xs text-gray-700 font-medium">{{ $product->stock }} {{ $product->unit }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-category="{{ $product->category_id }}"
                                                data-price="{{ $product->price }}"
                                                data-stock="{{ $product->stock }}"
                                                data-unit="{{ $product->unit }}"
                                                data-active="{{ $product->is_active ? '1' : '0' }}"
                                                data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                                onclick="openEditFormFromEl(this)"
                                                class="px-3 py-1 rounded-lg border border-[#244B3B] text-[#244B3B] text-[11px] font-semibold hover:bg-[#244B3B] hover:text-white transition-all btn-interactive">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('menu.destroy', $product) }}" onsubmit="return confirm('Hapus menu {{ addslashes($product->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-lg border border-red-200 text-red-500 text-[11px] font-semibold hover:bg-red-50 transition-all btn-interactive">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">☕</div>
                                    <p class="text-sm">Belum ada menu yang ditambahkan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-[#E2DDD6] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="text-xs text-gray-500 font-medium">Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} menu</span>
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

            <!-- Keterangan Sistem (Bukan Pesan Error) -->
            <div class="mt-4 p-3 rounded-xl bg-[#F5F3EE] border border-[#E2DDD6] text-xs text-gray-500 flex items-center gap-2.5 shadow-2xs">
                <span class="px-2 py-0.5 rounded-md bg-[#EDE9E0] text-gray-700 text-[10px] font-bold tracking-wider uppercase">Info Sistem</span>
                <span>Stok 0 otomatis menonaktifkan menu di kasir. Perubahan harga berlaku untuk transaksi berikutnya.</span>
            </div>
        </div>

        <!-- KANAN / BAWAH (Responsive): Edit/Add Form -->
        <div class="w-full lg:w-80 lg:min-w-80 bg-white border-t lg:border-t-0 lg:border-l border-[#E2DDD6] overflow-y-auto" id="menuFormPanel">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-[#1C1F1E]" id="formTitle">
                            {{ $selectedProduct ? 'Edit menu' : 'Tambah menu' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5" id="formSubtitle">
                            {{ $selectedProduct ? 'Perbarui informasi menu terpilih.' : 'Isi informasi menu yang akan ditambahkan.' }}
                        </p>
                    </div>
                    <button type="button" onclick="closeForm()" class="text-gray-400 hover:text-gray-600 transition-colors p-1" title="Tambah Menu Baru">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form id="menuForm" method="POST"
                      action="{{ $selectedProduct ? route('menu.update', $selectedProduct) : route('menu.store') }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="{{ $selectedProduct ? 'PUT' : 'POST' }}">

                    <!-- Nama -->
                    <div>
                        <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Nama menu</label>
                        <input type="text" name="name" id="fieldName"
                               value="{{ $selectedProduct ? $selectedProduct->name : '' }}"
                               placeholder="Contoh: Kopi Susu Gula Aren"
                               class="w-full px-3.5 py-2 rounded-lg border border-[#E2DDD6] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]"
                               required>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Kategori</label>
                        <div class="relative">
                            <select name="category_id" id="fieldCategory"
                                    class="w-full px-3.5 py-2 rounded-lg border border-[#E2DDD6] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] appearance-none bg-white"
                                    required>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $selectedProduct && $selectedProduct->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Harga & Stok -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Harga (Rp)</label>
                            <input type="number" name="price" id="fieldPrice"
                                   value="{{ $selectedProduct ? $selectedProduct->price : '' }}"
                                   placeholder="24000" min="0"
                                   class="w-full px-3 py-2 rounded-lg border border-[#E2DDD6] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]"
                                   required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Stok (<span id="unitLabel">{{ $selectedProduct ? $selectedProduct->unit : 'gelas' }}</span>)</label>
                            <input type="number" name="stock" id="fieldStock"
                                   value="{{ $selectedProduct ? $selectedProduct->stock : '' }}"
                                   placeholder="32" min="0"
                                   class="w-full px-3 py-2 rounded-lg border border-[#E2DDD6] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B]"
                                   required>
                        </div>
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Satuan</label>
                        <select name="unit" id="fieldUnit" onchange="document.getElementById('unitLabel').textContent=this.value"
                                class="w-full px-3.5 py-2 rounded-lg border border-[#E2DDD6] text-sm focus:outline-none focus:ring-2 focus:ring-[#244B3B]/30 focus:border-[#244B3B] bg-white">
                            <option value="gelas" {{ $selectedProduct && $selectedProduct->unit == 'gelas' ? 'selected' : '' }}>gelas</option>
                            <option value="porsi" {{ $selectedProduct && $selectedProduct->unit == 'porsi' ? 'selected' : '' }}>porsi</option>
                            <option value="pcs" {{ $selectedProduct && $selectedProduct->unit == 'pcs' ? 'selected' : '' }}>pcs</option>
                        </select>
                    </div>

                    <!-- Foto Menu dengan Live Preview -->
                    <div>
                        <label class="block text-xs font-semibold text-[#1C1F1E] mb-1.5">Foto menu</label>

                        <!-- Box Live Preview Gambar -->
                        <div id="imagePreviewContainer" class="{{ $selectedProduct && $selectedProduct->image ? '' : 'hidden' }} mb-2.5 relative group">
                            <img id="imagePreview"
                                 src="{{ $selectedProduct && $selectedProduct->image ? asset('storage/' . $selectedProduct->image) : '' }}"
                                 alt="Pratinjau Foto"
                                 class="w-full h-36 object-cover rounded-xl border border-[#E2DDD6] shadow-xs">
                            <div class="absolute bottom-2 left-2 right-2 px-2.5 py-1 bg-black/60 backdrop-blur-xs rounded-lg text-white text-[10px] truncate" id="fileNameBadge">
                                {{ $selectedProduct && $selectedProduct->image ? basename($selectedProduct->image) : '' }}
                            </div>
                        </div>

                        <!-- Label File Picker -->
                        <label class="flex flex-col items-center justify-center gap-1.5 p-4 rounded-xl border-2 border-dashed border-[#E2DDD6] hover:border-[#244B3B]/50 cursor-pointer transition-all bg-[#FAFAF8] hover:bg-[#F5F3EE] group">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6 text-gray-400 group-hover:text-[#244B3B] transition-colors">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span class="text-xs text-gray-700 font-semibold group-hover:text-[#244B3B] transition-colors" id="uploadButtonText">
                                {{ $selectedProduct && $selectedProduct->image ? 'Ganti foto menu' : 'Pilih foto menu' }}
                            </span>
                            <span class="text-[10px] text-gray-400">JPG, PNG, atau WebP (Maksimal 20 MB)</span>
                            <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleImageSelected(event)">
                        </label>
                    </div>

                    <!-- Toggle aktif -->
                    <div class="flex items-center justify-between py-2">
                        <label class="text-xs font-semibold text-[#1C1F1E]">Tampilkan di katalog</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="fieldActive" value="1" class="sr-only peer"
                                   {{ $selectedProduct ? ($selectedProduct->is_active ? 'checked' : '') : 'checked' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#244B3B]"></div>
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-2 space-y-2">
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-[#244B3B] hover:bg-[#1a3a2d] text-white rounded-xl font-semibold text-sm transition-all shadow-xs card-hover btn-interactive">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/>
                            </svg>
                            <span id="submitLabel">{{ $selectedProduct ? 'Simpan perubahan' : 'Tambah menu' }}</span>
                        </button>
                        <button type="button" onclick="closeForm()"
                                class="w-full py-2.5 px-4 border border-[#E2DDD6] text-gray-600 rounded-xl font-semibold text-sm hover:bg-[#F5F3EE] transition-all btn-interactive">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function handleImageSelected(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 20 * 1024 * 1024) {
                alert('Ukuran file foto maksimal 20 MB.');
                event.target.value = '';
                return;
            }

            const sizeText = file.size > 1024 * 1024 
                ? (file.size / (1024 * 1024)).toFixed(1) + ' MB' 
                : (file.size / 1024).toFixed(0) + ' KB';

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
                document.getElementById('fileNameBadge').textContent = 'Foto baru: ' + file.name + ' (' + sizeText + ')';
                document.getElementById('uploadButtonText').textContent = 'Ubah foto terpilih';
            };
            reader.readAsDataURL(file);
        }

        function openEditFormFromEl(btn) {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const categoryId = btn.dataset.category;
            const price = btn.dataset.price;
            const stock = btn.dataset.stock;
            const unit = btn.dataset.unit;
            const isActive = btn.dataset.active === '1';
            const imageUrl = btn.dataset.image;

            openEditForm(id, name, categoryId, price, stock, unit, isActive, imageUrl);
        }

        function openEditForm(id, name, categoryId, price, stock, unit, isActive, imageUrl) {
            document.getElementById('formTitle').textContent = 'Edit menu';
            document.getElementById('formSubtitle').textContent = 'Perbarui informasi menu terpilih.';
            document.getElementById('submitLabel').textContent = 'Simpan perubahan';
            document.getElementById('menuForm').action = '/menu/' + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('fieldName').value = name;
            document.getElementById('fieldCategory').value = categoryId;
            document.getElementById('fieldPrice').value = price;
            document.getElementById('fieldStock').value = stock;
            document.getElementById('fieldUnit').value = unit;
            document.getElementById('unitLabel').textContent = unit;
            document.getElementById('fieldActive').checked = isActive;
            document.getElementById('imageInput').value = '';

            // Update photo preview
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImg = document.getElementById('imagePreview');
            const fileNameBadge = document.getElementById('fileNameBadge');
            const uploadButtonText = document.getElementById('uploadButtonText');

            if (imageUrl) {
                previewImg.src = imageUrl;
                previewContainer.classList.remove('hidden');
                fileNameBadge.textContent = 'Foto saat ini';
                uploadButtonText.textContent = 'Ganti foto menu';
            } else {
                previewContainer.classList.add('hidden');
                previewImg.src = '';
                uploadButtonText.textContent = 'Pilih foto menu';
            }

            // Highlight selected row
            document.querySelectorAll('.product-row').forEach(row => {
                row.classList.remove('bg-[#F0F7F3]', 'border-l-4', 'border-l-[#244B3B]');
            });
            const selectedRow = document.getElementById('row-product-' + id);
            if (selectedRow) {
                selectedRow.classList.add('bg-[#F0F7F3]', 'border-l-4', 'border-l-[#244B3B]');
            }

            // Smooth scroll into view on mobile/tablet
            if (window.innerWidth < 1024) {
                document.getElementById('menuFormPanel').scrollIntoView({ behavior: 'smooth' });
            }
        }

        function openAddForm() {
            document.getElementById('formTitle').textContent = 'Tambah menu baru';
            document.getElementById('formSubtitle').textContent = 'Isi informasi menu yang akan ditambahkan.';
            document.getElementById('submitLabel').textContent = 'Tambah menu';
            document.getElementById('menuForm').action = '{{ route('menu.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('fieldName').value = '';
            document.getElementById('fieldPrice').value = '';
            document.getElementById('fieldStock').value = '';
            document.getElementById('fieldUnit').value = 'gelas';
            document.getElementById('unitLabel').textContent = 'gelas';
            document.getElementById('fieldActive').checked = true;
            document.getElementById('imageInput').value = '';

            // Reset image preview
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('imagePreview').src = '';
            document.getElementById('uploadButtonText').textContent = 'Pilih foto menu';

            // Remove row highlights
            document.querySelectorAll('.product-row').forEach(row => {
                row.classList.remove('bg-[#F0F7F3]', 'border-l-4', 'border-l-[#244B3B]');
            });

            // Smooth scroll into view on mobile/tablet
            if (window.innerWidth < 1024) {
                document.getElementById('menuFormPanel').scrollIntoView({ behavior: 'smooth' });
            }
        }

        function closeForm() {
            openAddForm();
        }

        function handleCategoryFilter(categoryId) {
            const url = new URL(window.location.href);
            if (categoryId) {
                url.searchParams.set('category', categoryId);
            } else {
                url.searchParams.delete('category');
            }
            window.location.href = url.toString();
        }

        function filterMenu(value) {
            clearTimeout(window._filterTimer);
            window._filterTimer = setTimeout(() => {
                const url = new URL(window.location.href);
                if (value) {
                    url.searchParams.set('search', value);
                } else {
                    url.searchParams.delete('search');
                }
                window.location.href = url.toString();
            }, 400);
        }
    </script>
    @endpush
</x-layouts.app>
