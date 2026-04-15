<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Drug;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pharmapos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Pharmacist',
            'email' => 'pharmacist@pharmapos.com',
            'password' => bcrypt('password'),
            'role' => 'pharmacist',
        ]);

        // Create categories
        $categories = [
            ['name' => 'Antibiotics', 'slug' => 'antibiotics', 'description' => 'Antibacterial medications'],
            ['name' => 'Painkillers', 'slug' => 'painkillers', 'description' => 'Pain relief medications'],
            ['name' => 'Vitamins', 'slug' => 'vitamins', 'description' => 'Vitamin supplements'],
            ['name' => 'Cardiovascular', 'slug' => 'cardiovascular', 'description' => 'Heart and blood pressure medications'],
            ['name' => 'Diabetes', 'slug' => 'diabetes', 'description' => 'Diabetes management'],
            ['name' => 'Respiratory', 'slug' => 'respiratory', 'description' => 'Asthma and respiratory medications'],
            ['name' => 'Digestive', 'slug' => 'digestive', 'description' => 'Digestive system medications'],
            ['name' => 'Topical', 'slug' => 'topical', 'description' => 'Creams, ointments, and gels'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create suppliers
        $suppliers = [
            ['name' => 'MediSupply Ltd', 'contact_person' => 'John Smith', 'phone' => '+255 123 456 789', 'email' => 'info@medisupply.com'],
            ['name' => 'PharmaDist Co', 'contact_person' => 'Jane Doe', 'phone' => '+255 987 654 321', 'email' => 'sales@pharmadist.com'],
            ['name' => 'Global Meds', 'contact_person' => 'Mike Johnson', 'phone' => '+255 555 123 456', 'email' => 'orders@globalmeds.com'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create sample drugs
        $drugs = [
            [
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'sku' => 'DRG-001',
                'barcode' => '1234567890123',
                'category_id' => 1,
                'supplier_id' => 1,
                'dosage_form' => 'capsule',
                'strength' => '500mg',
                'unit' => 'strip',
                'cost_price' => 2500,
                'selling_price' => 3500,
                'quantity_in_stock' => 150,
                'reorder_level' => 20,
                'requires_prescription' => true,
            ],
            [
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Paracetamol',
                'sku' => 'DRG-002',
                'barcode' => '1234567890124',
                'category_id' => 2,
                'supplier_id' => 1,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'strip',
                'cost_price' => 500,
                'selling_price' => 800,
                'quantity_in_stock' => 500,
                'reorder_level' => 50,
                'requires_prescription' => false,
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'sku' => 'DRG-003',
                'barcode' => '1234567890125',
                'category_id' => 2,
                'supplier_id' => 2,
                'dosage_form' => 'tablet',
                'strength' => '400mg',
                'unit' => 'strip',
                'cost_price' => 800,
                'selling_price' => 1200,
                'quantity_in_stock' => 200,
                'reorder_level' => 30,
                'requires_prescription' => false,
            ],
            [
                'name' => 'Vitamin C 1000mg',
                'generic_name' => 'Ascorbic Acid',
                'sku' => 'DRG-004',
                'barcode' => '1234567890126',
                'category_id' => 3,
                'supplier_id' => 2,
                'dosage_form' => 'tablet',
                'strength' => '1000mg',
                'unit' => 'bottle',
                'cost_price' => 5000,
                'selling_price' => 7500,
                'quantity_in_stock' => 80,
                'reorder_level' => 15,
                'requires_prescription' => false,
            ],
            [
                'name' => 'Amlodipine 5mg',
                'generic_name' => 'Amlodipine',
                'sku' => 'DRG-005',
                'barcode' => '1234567890127',
                'category_id' => 4,
                'supplier_id' => 3,
                'dosage_form' => 'tablet',
                'strength' => '5mg',
                'unit' => 'strip',
                'cost_price' => 3000,
                'selling_price' => 4500,
                'quantity_in_stock' => 100,
                'reorder_level' => 25,
                'requires_prescription' => true,
            ],
            [
                'name' => 'Metformin 500mg',
                'generic_name' => 'Metformin',
                'sku' => 'DRG-006',
                'barcode' => '1234567890128',
                'category_id' => 5,
                'supplier_id' => 3,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'strip',
                'cost_price' => 1500,
                'selling_price' => 2500,
                'quantity_in_stock' => 180,
                'reorder_level' => 30,
                'requires_prescription' => true,
            ],
            [
                'name' => 'Salbutamol Inhaler',
                'generic_name' => 'Salbutamol',
                'sku' => 'DRG-007',
                'barcode' => '1234567890129',
                'category_id' => 6,
                'supplier_id' => 1,
                'dosage_form' => 'inhaler',
                'strength' => '100mcg/dose',
                'unit' => 'piece',
                'cost_price' => 15000,
                'selling_price' => 22000,
                'quantity_in_stock' => 25,
                'reorder_level' => 10,
                'requires_prescription' => true,
            ],
            [
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'sku' => 'DRG-008',
                'barcode' => '1234567890130',
                'category_id' => 7,
                'supplier_id' => 2,
                'dosage_form' => 'capsule',
                'strength' => '20mg',
                'unit' => 'strip',
                'cost_price' => 2000,
                'selling_price' => 3000,
                'quantity_in_stock' => 120,
                'reorder_level' => 20,
                'requires_prescription' => false,
            ],
            [
                'name' => 'Hydrocortisone Cream 1%',
                'generic_name' => 'Hydrocortisone',
                'sku' => 'DRG-009',
                'barcode' => '1234567890131',
                'category_id' => 8,
                'supplier_id' => 3,
                'dosage_form' => 'cream',
                'strength' => '1%',
                'unit' => 'tube',
                'cost_price' => 3500,
                'selling_price' => 5000,
                'quantity_in_stock' => 45,
                'reorder_level' => 10,
                'requires_prescription' => false,
            ],
            [
                'name' => 'Ciprofloxacin 500mg',
                'generic_name' => 'Ciprofloxacin',
                'sku' => 'DRG-010',
                'barcode' => '1234567890132',
                'category_id' => 1,
                'supplier_id' => 1,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'strip',
                'cost_price' => 4000,
                'selling_price' => 6000,
                'quantity_in_stock' => 8,
                'reorder_level' => 15,
                'requires_prescription' => true,
            ],
        ];

        foreach ($drugs as $drug) {
            Drug::create($drug);
        }

        // Create sample customers
        $customers = [
            ['name' => 'Mary Johnson', 'phone' => '+255 777 111 222', 'email' => 'mary@email.com'],
            ['name' => 'Peter Williams', 'phone' => '+255 777 333 444', 'email' => 'peter@email.com'],
            ['name' => 'Sarah Brown', 'phone' => '+255 777 555 666', 'email' => 'sarah@email.com'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}