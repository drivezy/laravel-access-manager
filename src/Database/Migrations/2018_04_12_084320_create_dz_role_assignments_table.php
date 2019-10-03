<?php

use Drivezy\LaravelAccessManager\Database\Seeds\RoleSeeder;
use Drivezy\LaravelAccessManager\Models\Role;
use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDzRoleAssignmentsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('dz_role_assignments', function (Blueprint $table) {
            $userTable = LaravelUtility::getUserTable();
            $roleTable = ( new Role() )->getTable();

            $table->bigIncrements('id');

            $table->unsignedBigInteger('role_id')->nullable();

            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();

            $table->string('scope')->nullable();

            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('role_id')->references('id')->on($roleTable);

            $table->foreign('created_by')->references('id')->on($userTable);
            $table->foreign('updated_by')->references('id')->on($userTable);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['source_type', 'source_id']);
            $table->index(['target_type', 'target_id']);
        });

        ( new RoleSeeder() )->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('dz_role_assignments');
    }
}
