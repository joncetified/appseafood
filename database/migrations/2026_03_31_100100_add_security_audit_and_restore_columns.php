<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('whatsapp_number');
            $table->foreignId('created_by')->nullable()->after('remember_token')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            $table->softDeletes()->after('deleted_by');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('business_name');
            $table->string('manager_name')->nullable()->after('logo_path');
            $table->string('contact_person')->nullable()->after('manager_name');
            $table->string('captcha_mode')->default('auto')->after('contact_person');
            $table->text('discord_webhook_url')->nullable()->after('captcha_mode');
            $table->foreignId('created_by')->nullable()->after('weekend_hours')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            $table->softDeletes()->after('deleted_by');
        });

        foreach (['categories', 'seafood_items', 'promotions', 'testimonials', 'orders'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
                $table->softDeletes()->after('deleted_by');
            });
        }
    }

    public function down(): void
    {
        foreach (['orders', 'testimonials', 'promotions', 'seafood_items', 'categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropConstrainedForeignId('deleted_by');
                $table->dropConstrainedForeignId('updated_by');
                $table->dropConstrainedForeignId('created_by');
            });
        }

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['discord_webhook_url', 'captcha_mode', 'contact_person', 'manager_name', 'logo_path']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['is_active', 'whatsapp_number']);
        });
    }
};
