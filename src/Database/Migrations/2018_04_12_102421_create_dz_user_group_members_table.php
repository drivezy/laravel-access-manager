<?php

use Drivezy\LaravelAccessManager\Models\UserGroup;
use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDzUserGroupMembersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('dz_user_group_members', function (Blueprint $table) {
            $userTable = LaravelUtility::getUserTable();
            $groupTable = ( new UserGroup() )->getTable();

            $table->increments('id');

            $table->unsignedInteger('user_group_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('user_group_id')->references('id')->on($groupTable);
            $table->foreign('user_id')->references('id')->on($userTable);

            $table->foreign('created_by')->references('id')->on($userTable);
            $table->foreign('updated_by')->references('id')->on($userTable);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('dz_user_group_members');
    }
}
