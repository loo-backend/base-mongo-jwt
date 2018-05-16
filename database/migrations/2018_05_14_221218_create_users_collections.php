<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCollections extends Migration
{

    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)
            ->table('users', function (Blueprint $collection) {
                $collection->string('name');
                $collection->string('email')->unique();
                $collection->string('password');
                //->multiLineString('roles');
                //$collection->uuid('uuid');
                $collection->timestamps();

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
            ->table('users', function (Blueprint $collection) {
                //$collection->dropIndex();
                $collection->drop();
            });
    }

}
