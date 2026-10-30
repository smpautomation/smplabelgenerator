<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{     public function run(): void
    {
        // slug => [name, [type => method|null]]  (null = not available)
        $sample = [
            'denso' => ['DENSO', ['tray' => 'tformer', 'package' => null, 'box' => 'sbpl', 'pallet' => null]],
            'toyota' => ['TOYOTA MOTOR', ['tray' => null, 'package' => 'tformer', 'box' => null, 'pallet' => null]],
            'tesla' => ['TESLA', ['tray' => null, 'package' => null, 'box' => 'tformer', 'pallet' => null]],
            'ferrari' => ['FERRARI', ['tray' => 'tformer', 'package' => null, 'box' => 'sbpl', 'pallet' => null]],
            'honda' => ['HONDA MOTOR', ['tray' => null, 'package' => null, 'box' => null, 'pallet' => 'sbpl']],
            'enercon' => ['ENERCON GLOBAL', ['tray' => null, 'package' => null, 'box' => 'sbpl', 'pallet' => null]],
            'mitsubishi' => ['MITSUBISHI ELECTRIC MOBILITY', ['tray' => null, 'package' => 'sbpl', 'box' => 'tformer', 'pallet' => null]],
            'daihatsu' => ['DAIHATSU MOTOR', ['tray' => null, 'package' => null, 'box' => null, 'pallet' => 'tformer']],
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
