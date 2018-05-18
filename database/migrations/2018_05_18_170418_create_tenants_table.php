<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
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
        ->table('tenants', function (Blueprint $table)
        {

            $table->uuid('tenant_uuid');
            $table->string('name');
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
        ->table('tenants', function (Blueprint $table)
        {
            $table->dropIndex();
            $table->drop();
        });

    }
}
