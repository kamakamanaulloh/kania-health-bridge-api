<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('api_logs', function (Blueprint $table) { $table->id(); $table->string('request_id',100)->index(); $table->string('service',50)->index(); $table->string('module',100)->nullable()->index(); $table->string('endpoint')->nullable(); $table->string('method',10)->nullable(); $table->longText('request_payload')->nullable(); $table->longText('response_payload')->nullable(); $table->integer('http_code')->nullable(); $table->enum('status',['success','failed'])->default('success')->index(); $table->integer('duration_ms')->nullable(); $table->string('ip_address',100)->nullable(); $table->text('user_agent')->nullable(); $table->timestamps(); }); }
 public function down(): void { Schema::dropIfExists('api_logs'); }
};
