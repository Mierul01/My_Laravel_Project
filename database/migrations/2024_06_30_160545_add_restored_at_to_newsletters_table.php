<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestoredAtToNewslettersTable extends Migration
{
    public function up()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->timestamp('restored_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('newsletters', function (Blueprint $table) {
            $table->dropColumn('restored_at');
        });
    }
}
