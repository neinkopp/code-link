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
		Schema::create('profile', function (Blueprint $table) {
			$table->id();
			$table->date('dob');
			$table->json('programming_langs');
			$table->string("occupation");
			$table->string("showcase_code");
			$table->unsignedInteger('user_id')->unique();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('swipes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('from_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreignId('to_user_id')->references('id')->on('users')->onDelete('cascade');
			$table->boolean('liked');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('profile');
		Schema::dropIfExists('swipes');
	}
};
