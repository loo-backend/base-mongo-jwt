<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection($this->connection)
        ->table('users', function (Blueprint $table)
        {

            $table->uuid('user_uuid');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token');
            $table->boolean('is_administrator')->default(false);
            $table->boolean('active')->default(false);
            $table->jsonb('roles');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::connection($this->connection)
        ->table('users', function (Blueprint $table)
        {
            $table->dropIndex();
            $table->drop();
        });

    }
}
