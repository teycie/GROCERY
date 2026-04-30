<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUserRoleEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Changing ENUM columns in Laravel directly can be tricky.
        // A direct DB statement is often the safest way to modify an ENUM.
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('buyer', 'seller', 'rider', 'admin') DEFAULT 'buyer'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('buyer', 'seller') DEFAULT 'buyer'");
    }
}
