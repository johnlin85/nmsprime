<?php

use Illuminate\Database\Schema\Blueprint;

class CreateProvHATable extends \BaseMigration
{
    // name of the table to create
    protected $tablename = 'provha';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
            $this->up_table_generic($table);

            $table->string('master');
            $table->string('slaves');
            $table->float('load_ratio_master', 6)->default(0.5);
            $table->integer('slave_config_rebuild_interval')->unsigned()->default(3600);

        });

        $own_ip = gethostbyname(gethostname());
        DB::update("INSERT INTO $this->tablename (master) VALUES('$own_ip');");     // insert IP – not sure if hostname can change later on

        return parent::up();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tablename);
    }
}

