<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // add_soft_deletes_to_projects_table
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
