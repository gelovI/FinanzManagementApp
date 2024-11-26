<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primärschlüssel
            $table->string('name'); // Name der Kategorie
            $table->enum('type', ['income', 'expense']); // Typ: Einnahme oder Ausgabe
            $table->timestamps(); // Erstellt created_at und updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
