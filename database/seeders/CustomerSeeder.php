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
            'denso' => ['DENSO', ['tray' => 'tformer', 'package' => 'sbpl', 'box' => 'tformer', 'pallet' => null]],
            'toyota' => ['TOYOTA MOTOR', ['tray' => 'sbpl', 'package' => null, 'box' => 'sbpl', 'pallet' => 'sbpl']],
            'tesla' => ['TESLA', ['tray' => 'tformer', 'package' => 'tformer', 'box' => null, 'pallet' => 'tformer']],
            'ferrari' => ['FERRARI', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
            'honda' => ['HONDA MOTOR', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
            'enercon' => ['ENERCON GLOBAL', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
            'mitsubishi' => ['MITSUBISHI ELECTRIC MOBILITY', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
            'daihatsu' => ['DAIHATSU MOTOR', ['tray' => null, 'package' => 'sbpl', 'box' => 'sbpl', 'pallet' => null]],
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
