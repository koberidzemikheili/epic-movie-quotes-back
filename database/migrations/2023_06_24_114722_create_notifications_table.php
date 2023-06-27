<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->foreignId('actor_id')->constrained('users');
			$table->foreignId('receiver_id')->constrained('users');
			$table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
			$table->string('action');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('notifications');
	}
};
