<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

const FINGERVAULT_USER_TOKEN_COLUMN_NAME = 'fingervault_user_token';

class AddFingervaultUserTokenToUsersTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string(FINGERVAULT_USER_TOKEN_COLUMN_NAME)
                ->unique()
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn(FINGERVAULT_USER_TOKEN_COLUMN_NAME);
        });
    }
}
