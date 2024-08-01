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
        Schema::create('material_costs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('subtask_id');
        $table->unsignedBigInteger('material_id');
        $table->string('unit');
        $table->decimal('rate_with_vat', 8, 2);
        $table->decimal('qty', 8, 2);
        $table->decimal('amount', 10, 2);
        $table->string('status');
        $table->text('remark')->nullable();
        $table->foreign('subtask_id')
                ->references('id')
                ->on('subtasks')
                ->onDelete('cascade');
        $table->foreign('material_id')
                ->references('id')
                ->on('materials')
                ->onDelete('cascade');
        $table->timestamps();
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_costs');
    }
};
