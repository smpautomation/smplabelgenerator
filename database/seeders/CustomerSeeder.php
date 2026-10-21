<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // slug => [name, [type => method|null]]  (null = not available)
        $sample = [
            'customer-a' => ['Customer A', ['tray' => 'tformer', 'package' => 'sbpl', 'box' => 'tformer', 'pallet' => null]],
            'customer-b' => ['Customer B', ['tray' => 'sbpl', 'package' => null, 'box' => 'sbpl', 'pallet' => 'sbpl']],
            'customer-c' => ['Customer C', ['tray' => 'tformer', 'package' => 'tformer', 'box' => null, 'pallet' => 'tformer']],
            'customer-d' => ['Customer D', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
        ];

        $order = 0;
        foreach ($sample as $slug => [$name, $types]) {
            $customer = Customer::updateOrCreate(['slug' => $slug], ['name' => $name, 'sort_order' => $order++]);
            foreach ($types as $type => $method) {
                $customer->labelTypes()->updateOrCreate(
                    ['label_type' => $type],
                    ['print_method' => $method ?? 'tformer', 'is_enabled' => $method !== null],
                );
            }
        }
    }
}
