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
        Schema::create('equipment_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtask_id');
            $table->unsignedBigInteger('equipment_id');
            $table->string('unit');
            $table->decimal('qty', 8, 2);
            $table->decimal('rate_with_vat', 8, 2);
            $table->decimal('amount', 8, 2);
            $table->text('remark')->nullable();

            $table->foreign('subtask_id')
            ->references('id')
            ->on('subtasks')
            ->onDelete('cascade');

            $table->foreign('equipment_id')
            ->references('id')
            ->on('equipment')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_costs');
    }
};
