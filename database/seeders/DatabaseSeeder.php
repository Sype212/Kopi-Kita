<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Kasir ---
        $kasir = User::create([
            'name' => 'Ayu Pratama',
            'username' => 'ayu.pratama',
            'email' => 'ayu@kopikita.id',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'shift' => '08.00-16.00',
            'branch' => 'Kopi Kita • Bandung',
        ]);

        User::create([
            'name' => 'Admin Kopi Kita',
            'username' => 'admin',
            'email' => 'admin@kopikita.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'shift' => null,
            'branch' => 'Kopi Kita • Bandung',
        ]);

        // --- Kategori ---
        $kopi = Category::create(['name' => 'Kopi', 'slug' => 'kopi']);
        $nonKopi = Category::create(['name' => 'Non-kopi', 'slug' => 'non-kopi']);
        $makanan = Category::create(['name' => 'Makanan', 'slug' => 'makanan']);

        // --- Menu ---
        $products = [
            ['category_id' => $kopi->id, 'name' => 'Kopi Susu Kita', 'price' => 24000, 'stock' => 32, 'unit' => 'gelas', 'is_active' => true],
            ['category_id' => $kopi->id, 'name' => 'Americano', 'price' => 20000, 'stock' => 28, 'unit' => 'gelas', 'is_active' => true],
            ['category_id' => $kopi->id, 'name' => 'Cappuccino', 'price' => 28000, 'stock' => 24, 'unit' => 'gelas', 'is_active' => true],
            ['category_id' => $nonKopi->id, 'name' => 'Matcha Latte', 'price' => 30000, 'stock' => 18, 'unit' => 'gelas', 'is_active' => true],
            ['category_id' => $makanan->id, 'name' => 'Croissant Butter', 'price' => 22000, 'stock' => 8, 'unit' => 'porsi', 'is_active' => true],
            ['category_id' => $makanan->id, 'name' => 'Kentang Goreng', 'price' => 20000, 'stock' => 15, 'unit' => 'porsi', 'is_active' => true],
            ['category_id' => $nonKopi->id, 'name' => 'Cokelat Hangat', 'price' => 26000, 'stock' => 0, 'unit' => 'gelas', 'is_active' => false],
            ['category_id' => $makanan->id, 'name' => 'Roti Bakar', 'price' => 18000, 'stock' => 0, 'unit' => 'porsi', 'is_active' => false],
        ];

        $productModels = [];
        foreach ($products as $product) {
            $productModels[$product['name']] = Product::create($product);
        }

        // --- Sample Orders (sesuai mockup) ---
        $sampleOrders = [
            [
                'order_number' => 'KK-021',
                'user_id' => $kasir->id,
                'order_type' => 'dine_in',
                'table_number' => 'Meja 06',
                'customer_name' => 'Pelanggan umum',
                'payment_method' => 'debit',
                'status' => 'selesai',
                'items' => [
                    ['product' => 'Kopi Susu Kita', 'qty' => 2],
                    ['product' => 'Croissant Butter', 'qty' => 1],
                ],
            ],
            [
                'order_number' => 'KK-022',
                'user_id' => $kasir->id,
                'order_type' => 'dine_in',
                'table_number' => 'Meja 02',
                'customer_name' => 'Pelanggan umum',
                'payment_method' => 'tunai',
                'status' => 'selesai',
                'items' => [
                    ['product' => 'Americano', 'qty' => 1],
                    ['product' => 'Cappuccino', 'qty' => 1],
                ],
            ],
            [
                'order_number' => 'KK-023',
                'user_id' => $kasir->id,
                'order_type' => 'takeaway',
                'table_number' => null,
                'customer_name' => 'Pelanggan umum',
                'payment_method' => 'qris',
                'status' => 'siap_diambil',
                'items' => [
                    ['product' => 'Matcha Latte', 'qty' => 1],
                    ['product' => 'Kentang Goreng', 'qty' => 1],
                    ['product' => 'Americano', 'qty' => 1],
                ],
            ],
            [
                'order_number' => 'KK-024',
                'user_id' => $kasir->id,
                'order_type' => 'dine_in',
                'table_number' => 'Meja 04',
                'customer_name' => 'Pelanggan umum',
                'payment_method' => 'tunai',
                'status' => 'diproses',
                'items' => [
                    ['product' => 'Kopi Susu Kita', 'qty' => 2],
                    ['product' => 'Croissant Butter', 'qty' => 1],
                    ['product' => 'Kentang Goreng', 'qty' => 1],
                ],
            ],
        ];

        foreach ($sampleOrders as $orderData) {
            $subtotal = 0;
            $itemsToInsert = [];
            foreach ($orderData['items'] as $itemData) {
                $product = $productModels[$itemData['product']];
                $itemSubtotal = $product->price * $itemData['qty'];
                $subtotal += $itemSubtotal;
                $itemsToInsert[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['qty'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }
            $tax = (int) round($subtotal * 0.1);
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => $orderData['order_number'],
                'user_id' => $orderData['user_id'],
                'order_type' => $orderData['order_type'],
                'table_number' => $orderData['table_number'],
                'customer_name' => $orderData['customer_name'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $orderData['payment_method'],
                'cash_received' => $orderData['payment_method'] === 'tunai' ? $total + 1000 : null,
                'cash_change' => $orderData['payment_method'] === 'tunai' ? 1000 : null,
                'status' => $orderData['status'],
            ]);

            foreach ($itemsToInsert as $item) {
                OrderItem::create(array_merge(['order_id' => $order->id], $item));
            }
        }
    }
}
