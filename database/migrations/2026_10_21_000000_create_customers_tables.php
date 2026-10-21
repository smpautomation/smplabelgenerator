<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('logo_path')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        // One row per customer + label type. Missing row or is_enabled = false means "Not available".
        // print_method is chosen per row, so Customer A can use TFORMer for Tray and SBPL for Package.
        Schema::create('customer_label_types', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->string('label_type', 20);
            $t->string('print_method', 20);
            $t->boolean('is_enabled')->default(true);
            $t->timestamps();
            $t->unique(['customer_id', 'label_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_label_types');
        Schema::dropIfExists('customers');
    }
};
