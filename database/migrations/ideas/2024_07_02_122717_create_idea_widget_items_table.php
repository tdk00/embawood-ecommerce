<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeaWidgetItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_widget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idea_widget_tab_id')->constrained('idea_widget_tabs')->onDelete('cascade'); // Link to IdeaWidgetTab
            $table->foreignId('sub_idea_id')->constrained()->onDelete('cascade'); // Link to SubIdea
            $table->integer('sort_order')->default(0); // Order of the item within the tab
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('idea_widget_items');
    }
}
