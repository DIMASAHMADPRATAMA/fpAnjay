<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'kode_order')) {
            $table->string('kode_order')->nullable()->after('id');
        }

        if (!Schema::hasColumn('orders', 'name')) {
            $table->string('name')->nullable()->after('user_id');
        }

        if (!Schema::hasColumn('orders', 'phone')) {
            $table->string('phone')->nullable();
        }

        if (!Schema::hasColumn('orders', 'postal_code')) {
            $table->string('postal_code')->nullable();
        }

        if (!Schema::hasColumn('orders', 'courier')) {
            $table->string('courier')->nullable();
        }
    });
}

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kode_order', 'name', 'phone', 'postal_code', 'courier']);
        });
    }
};
