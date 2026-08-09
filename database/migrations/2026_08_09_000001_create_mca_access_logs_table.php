<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = (string) config('access-log.table', 'mca_access_logs');

        Schema::create($table, function (Blueprint $table): void {
            $table->id();
            $table->string('ip', 64)->index();
            $table->string('method', 16)->index();
            $table->string('path', 500)->index();
            $table->string('route_name')->nullable()->index();
            $table->unsignedSmallInteger('status_code')->default(0)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_agent', 512)->nullable();
            $table->string('referer', 500)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->boolean('is_blocked')->default(false)->index();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((string) config('access-log.table', 'mca_access_logs'));
    }
};
