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

        // use data from dhcp failover config to populate table
        $data = file_get_contents('/etc/dhcp-nmsprime/failover.conf');
        $data = explode("\n", $data);
        foreach ($data as $entry) {
            $entry = trim(str_replace(';', '', $entry));
            if (\Str::startsWith($entry, 'address ')) {
                $tmp = explode(' ', $entry);
                $address = array_pop($tmp);
            } elseif (\Str::startsWith($entry, 'peer address ')) {
                $tmp = explode(' ', $entry);
                $peer_address = array_pop($tmp);
            }
        }
        $own_state = getenv('PROVHA__OWN_STATE');
        if ('master' == $own_state) {
            $master = $address;
            $slave = $peer_address;
        } elseif ('slave' == $own_state) {
            $master = $peer_address;
            $slave = $address;
        } else {
            $master = 'n/a';
            $slave = 'n/a';
        }

        DB::update("INSERT INTO $this->tablename (created_at, updated_at, master, slaves) VALUES(NOW(), NOW(), '$master', '$slave');");     // insert IP – not sure if hostname can change later on

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

