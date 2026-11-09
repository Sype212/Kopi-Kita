<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Tampilkan daftar menu.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('id', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->orderBy('name')->paginate(8)->withQueryString();
        $totalMenu = Product::count();
        $menuTersedia = Product::where('is_active', true)->where('stock', '>', 0)->count();
        $menuHabis = Product::where('stock', '<=', 0)->count();

        $lastUpdated = Product::latest('updated_at')->value('updated_at');

        // Menu terpilih untuk panel edit di kanan
        $selectedProduct = null;
        if ($request->filled('edit')) {
            $selectedProduct = Product::find($request->edit);
        }
        if (! $selectedProduct && $products->isNotEmpty()) {
            $selectedProduct = $products->first();
        }

        return view('menu.index', compact(
            'products',
            'categories',
            'totalMenu',
            'menuTersedia',
            'menuHabis',
            'lastUpdated',
            'selectedProduct',
        ));
    }

    /**
     * Simpan menu baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'category_id.required' => 'Kategori menu wajib dipilih.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'image.image' => 'File harus berupa gambar foto.',
            'image.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 20 MB.',
            'image.uploaded' => 'Ukuran foto terlalu besar atau gagal diunggah oleh server.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        Product::create(array_merge($validated, [
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]));

        return redirect()->route('menu.index')
            ->with('success', "Menu {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Update data menu.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:20480'],
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'category_id.required' => 'Kategori menu wajib dipilih.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'image.image' => 'File harus berupa gambar foto.',
            'image.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran foto maksimal 20 MB.',
            'image.uploaded' => 'Ukuran foto terlalu besar atau gagal diunggah oleh server.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        // Nonaktifkan otomatis jika stok habis
        if ((int) $validated['stock'] <= 0) {
            $validated['is_active'] = false;
        }

        $product->update($validated);

        return redirect()->route('menu.index')
            ->with('success', "Menu {$product->name} berhasil diperbarui.");
    }

    /**
     * Hapus menu.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('menu.index')
            ->with('success', "Menu {$name} berhasil dihapus.");
    }
}
