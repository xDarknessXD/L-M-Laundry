<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\Machine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ────────────────────────────────────────
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@jml.com',
            'phone' => '+63 912 345 6789',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@jml.com',
            'phone' => '+63 998 765 4321',
            'password' => Hash::make('staff1234'),
            'role' => 'staff',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Pending User',
            'email' => 'pending@jml.com',
            'password' => Hash::make('pending1234'),
            'role' => 'staff',
            'status' => 'pending',
        ]);

        // ── Services ────────────────────────────────────
        Service::create([
            'name' => 'Regular Wash',
            'type' => 'regular',
            'price_per_kilo' => 65,
            'wash_price' => 40,
            'wash_minutes' => 30,
            'dry_price' => 40,
            'dry_minutes' => 45,
            'fold_price' => 20,
            'minimum_kilos' => 5,
        ]);

        Service::create([
            'name' => 'Self-Service',
            'type' => 'self_service',
            'price_per_kilo' => 50,
            'wash_price' => 30,
            'wash_minutes' => 30,
            'dry_price' => 30,
            'dry_minutes' => 40,
            'fold_price' => 0,
            'minimum_kilos' => 5,
        ]);

        Service::create([
            'name' => 'Rush Service',
            'type' => 'rush',
            'price_per_kilo' => 90,
            'wash_price' => 50,
            'wash_minutes' => 25,
            'dry_price' => 50,
            'dry_minutes' => 35,
            'fold_price' => 25,
            'minimum_kilos' => 3,
        ]);

        Service::create([
            'name' => 'Comforter Wash',
            'type' => 'comforter',
            'price_per_kilo' => 120,
            'wash_price' => 80,
            'wash_minutes' => 45,
            'dry_price' => 80,
            'dry_minutes' => 60,
            'fold_price' => 30,
            'minimum_kilos' => 3,
        ]);

        // ── Inventory Categories & Items ────────────────
        $detergents = InventoryCategory::create(['name' => 'Detergents & Soaps', 'icon' => '🧴']);
        $supplies = InventoryCategory::create(['name' => 'Laundry Supplies', 'icon' => '🧺']);

        // Detergents
        InventoryItem::create([
            'inventory_category_id' => $detergents->id,
            'name' => 'Ariel Powder (1kg)',
            'brand' => 'Ariel',
            'unit' => 'pcs',
            'price' => 15,
            'stock_quantity' => 50,
            'status' => 'available',
        ]);

        InventoryItem::create([
            'inventory_category_id' => $detergents->id,
            'name' => 'Tide Liquid (sachet)',
            'brand' => 'Tide',
            'unit' => 'sachets',
            'price' => 10,
            'stock_quantity' => 120,
            'status' => 'available',
        ]);

        InventoryItem::create([
            'inventory_category_id' => $detergents->id,
            'name' => 'Downy Fabric Softener',
            'brand' => 'Downy',
            'unit' => 'sachets',
            'price' => 8,
            'stock_quantity' => 8,
            'status' => 'low_stock',
        ]);

        InventoryItem::create([
            'inventory_category_id' => $detergents->id,
            'name' => 'Zonrox Bleach (500ml)',
            'brand' => 'Zonrox',
            'unit' => 'bottles',
            'price' => 35,
            'stock_quantity' => 25,
            'status' => 'available',
        ]);

        // Supplies
        InventoryItem::create([
            'inventory_category_id' => $supplies->id,
            'name' => 'Plastic Bag (Large)',
            'brand' => 'Generic',
            'unit' => 'pcs',
            'price' => 5,
            'stock_quantity' => 200,
            'status' => 'available',
        ]);

        InventoryItem::create([
            'inventory_category_id' => $supplies->id,
            'name' => 'Hanger',
            'brand' => 'Generic',
            'unit' => 'pcs',
            'price' => 12,
            'stock_quantity' => 60,
            'status' => 'available',
        ]);

        InventoryItem::create([
            'inventory_category_id' => $supplies->id,
            'name' => 'Stain Remover Spray',
            'brand' => 'Vanish',
            'unit' => 'bottles',
            'price' => 95,
            'stock_quantity' => 3,
            'status' => 'low_stock',
        ]);

        // ── Machines ────────────────────────────────────
        Machine::create([
            'machine_code' => 'WSH-001',
            'name' => 'Washer 1',
            'type' => 'Washer',
            'is_active' => true,
        ]);

        Machine::create([
            'machine_code' => 'WSH-002',
            'name' => 'Washer 2',
            'type' => 'Washer',
            'is_active' => true,
        ]);

        Machine::create([
            'machine_code' => 'DRY-001',
            'name' => 'Dryer 1',
            'type' => 'Dryer',
            'is_active' => true,
        ]);

        Machine::create([
            'machine_code' => 'DRY-002',
            'name' => 'Dryer 2',
            'type' => 'Dryer',
            'is_active' => true,
        ]);
    }
}
