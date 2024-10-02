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
        Schema::create('labors', function (Blueprint $table) {
            $table->id(); // Unique identifier for the labor entry
            $table->foreignId('labor_type_id')->constrained('labor_types')->onDelete('cascade'); // Foreign key to labor_types table
            $table->string('first_name'); // First name of the laborer
            $table->string('last_name'); // Last name of the laborer
            $table->string('email')->unique(); // Email address of the laborer
            $table->string('phone_number'); // Contact number of the laborer
            $table->date('hire_date'); // Date when the laborer was hired
            $table->enum('status', ['active', 'inactive']); // Current employment status
            $table->text('skills')->nullable(); // List of specific skills possessed by the laborer
            $table->string('assigned_project')->nullable(); // Projects currently assigned to the laborer
            $table->timestamps(); // CreatedAt and UpdatedAt timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labors');
    }
};
