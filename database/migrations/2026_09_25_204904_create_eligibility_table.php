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
    Schema::create('eligibility', function (Blueprint $table) {
        $table->id();
        $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
        $table->foreignId('project_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['unchecked', 'eligible', 'not_eligible'])->default('unchecked');
        $table->enum('method', ['manual', 'auto'])->default('manual');
        $table->timestamp('checked_at')->nullable();
        $table->timestamps();

        $table->unique(['wallet_id', 'project_id']); // one status per wallet+project pair
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eligibility');
    }
};
