<?php

namespace App\Http\Controllers;

use App\Enums\LabelType;
use App\Models\Customer;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function __invoke(): Response
    {
        $customers = Customer::query()
            ->where('is_active', true)
            ->with('labelTypes')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Customer $c) => [
                'name' => $c->name,
                'slug' => $c->slug,
                'logo' => $c->logo_url,
                // Always all four types, in a fixed order, so the grid never shifts between customers.
                'options' => collect(LabelType::cases())->map(fn (LabelType $t) => [
                    'type' => $t->value,
                    'label' => $t->label(),
                    'enabled' => (bool) $c->labelTypes->first(fn ($o) => $o->label_type === $t)?->is_enabled,
                ])->values(),
            ]);

        return Inertia::render('Landing', [
            'customers' => $customers,
            'brand' => config('smp'),
        ]);
    }
}
