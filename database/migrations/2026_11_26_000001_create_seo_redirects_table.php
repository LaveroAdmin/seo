<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $tableName = config('admin.extensions.seo.redirects_table', 'seo_redirects');

        if (!Schema::connection($connection)->hasTable($tableName)) {
            Schema::connection($connection)->create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('source_path', 255)->unique();
                $table->string('target_url', 500)->nullable();
                $table->unsignedSmallInteger('status_code')->default(301);
                $table->boolean('is_active')->default(true)->index();
                $table->unsignedBigInteger('hits')->default(0);
                $table->timestamp('last_hit_at')->nullable();
                $table->string('note', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $tableName = config('admin.extensions.seo.redirects_table', 'seo_redirects');

        Schema::connection($connection)->dropIfExists($tableName);
    }
};
