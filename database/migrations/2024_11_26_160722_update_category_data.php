<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class UpdateCategoryData extends Migration
{
    public function up()
    {
        // Update bestehende Kategorie 'Salary' in 'Gehalt'
        Category::where('name', 'Salary')->update(['name' => 'Gehalt']);

        // Neue Kategorie hinzufügen
        Category::updateOrCreate(['name' => 'Sonstige Einnahme'], ['type' => 'income']);
    }

    public function down()
    {
        // Rollback: Ändere 'Gehalt' zurück zu 'Salary'
        Category::where('name', 'Gehalt')->update(['name' => 'Salary']);

        // Entferne 'Sonstige Einnahme'
        Category::where('name', 'Sonstige Einnahme')->delete();
    }
}
