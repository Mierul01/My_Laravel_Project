<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('image')->nullable();
        });
    }

    public function down()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('image');
        });
    }
};

