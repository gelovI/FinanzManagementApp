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
        Schema::create('incomes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id(); // Primärschlüssel
            $table->decimal('amount', 10, 2); // Betrag mit zwei Nachkommastellen
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete(); // Kategorie-ID (Fremdschlüssel)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Benutzer-ID (Fremdschlüssel)
            $table->date('date'); // Datum der Einnahme
            $table->string('description')->nullable(); // Optionale Beschreibung
            $table->timestamps(); // Erstellt created_at und updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
