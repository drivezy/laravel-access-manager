<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySysUsersAttemptsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::table('sys_users', function (Blueprint $table) {
            $table->tinyInteger('attempts')->default(0);
            $table->datetime('last_login_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::table('sys_users', function (Blueprint $table) {
            $table->dropColumn('attempts');
            $table->dropColumn('last_login_time');
        });
    }
}
