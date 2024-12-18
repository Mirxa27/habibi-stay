<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Pricing fields
            $table->decimal('base_price', 10, 2)->after('description');
            $table->decimal('cleaning_fee', 10, 2)->default(0)->after('base_price');
            $table->decimal('security_deposit', 10, 2)->default(0)->after('cleaning_fee');
            
            // Availability fields
            $table->json('blocked_dates')->nullable()->after('security_deposit');
            $table->integer('min_nights')->default(1)->after('blocked_dates');
            $table->integer('max_nights')->default(30)->after('min_nights');
            $table->integer('advance_notice')->default(0)->after('max_nights');
            $table->json('ical_feeds')->nullable()->after('advance_notice');
            
            // Channel manager fields
            $table->boolean('instant_booking')->default(false)->after('ical_feeds');
            $table->json('sync_logs')->nullable()->after('instant_booking');
            $table->timestamp('last_synced_at')->nullable()->after('sync_logs');
            
            // Statistics fields
            $table->decimal('total_revenue', 12, 2)->default(0)->after('last_synced_at');
            $table->float('occupancy_rate')->default(0)->after('total_revenue');
            $table->integer('total_bookings')->default(0)->after('occupancy_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'base_price',
                'cleaning_fee',
                'security_deposit',
                'blocked_dates',
                'min_nights',
                'max_nights',
                'advance_notice',
                'ical_feeds',
                'instant_booking',
                'sync_logs',
                'last_synced_at',
                'total_revenue',
                'occupancy_rate',
                'total_bookings',
            ]);
        });
    }
};
