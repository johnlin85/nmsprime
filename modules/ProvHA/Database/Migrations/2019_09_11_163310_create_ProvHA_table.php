<?php

use Illuminate\Database\Schema\Blueprint;

class CreateProvHATable extends BaseMigration
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

        // at time of install there is no information about own state…
        $master = 'master.not.set';
        $slave = 'slave.not.set';

        // set ID explicitly to 1 – in some runs this entry has been created with 3 causing the GUI to crash…
        DB::update("INSERT INTO $this->tablename (id, created_at, updated_at, master, slaves) VALUES(1, NOW(), NOW(), '$master', '$slave');");

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
