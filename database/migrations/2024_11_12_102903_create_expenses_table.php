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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // Primärschlüssel
            $table->decimal('amount', 10, 2); // Betrag mit zwei Nachkommastellen
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // Kategorie-ID (Fremdschlüssel)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Benutzer-ID (Fremdschlüssel)
            $table->date('date'); // Datum der Ausgabe
            $table->string('description')->nullable(); // Optionale Beschreibung
            $table->timestamps(); // Erstellt created_at und updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
