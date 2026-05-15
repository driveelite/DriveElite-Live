<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('cars', function (Blueprint $table) {
        // Ek naya boolean column add kar rahe hain (Default 'false' hoga)
        $table->boolean('is_wheelchair_accessible')->default(false); 
        // Note: 'after status' ki jagah aap kisi aur column ka naam bhi likh sakte hain jo aapki table mein pehle se majood ho.
    });
}
    public function down()
{
    Schema::table('cars', function (Blueprint $table) {
        $table->dropColumn('is_wheelchair_accessible');
    });
}
};
