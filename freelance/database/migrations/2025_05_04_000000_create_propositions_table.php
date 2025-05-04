<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropositionsTable extends Migration
{
    public function up()
    {
        Schema::create('propositions', function (Blueprint $table) {
            $table->id();
            $table->text('contenu');
            $table->decimal('budget', 10, 2);
            $table->date('date_creation');
            $table->date('date_fin');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('propositions');
    }
}