<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('submit_id')->nullable()->index();
			$table->string('mobile')->nullable()->index();
			$table->string('name')->nullable();
			$table->unsignedBigInteger('amount');
			$table->string('gateway');
			$table->string('authority')->index();
			$table->string('ref_id')->nullable();
			$table->string('card_pan')->nullable();
			$table->string('status')->default('init');
			$table->json('raw_request')->nullable();
			$table->json('raw_response')->nullable();
			$table->timestamp('verified_at')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('transactions');
	}
};