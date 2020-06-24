<?php

use Illuminate\Database\Schema\Blueprint;

class UpdateProvhaStoreDnsKey extends BaseMigration
{
    protected $tablename = 'provha';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create columns to store DNS update key
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->string('master_dns_password')->after('master');
        });
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->string('slave_dns_password')->after('slaves');
        });

        $dns_password = 'n/a';
        // get the current DNS password
        if (preg_match('/secret +"?(.*)"?;/', file_get_contents('/etc/named-nmsprime.conf'), $matches)) {
            $dns_password = str_replace('"', '', $matches[1]);
        }

        // or create a new one
        if (
            (substr($dns_password, -1) != '=')
            &&
            (preg_match('/secret "?(.*)"?;/', shell_exec('ddns-confgen -a hmac-md5 -r /dev/urandom | grep secret'), $matches))
        ) {
            $dns_password = str_replace('"', '', $matches[1]);
        }

        // or at least give a hint
        if (substr($dns_password, -1) != '=') {
            $dns_password = 'to be set';
        }

        // store in database
        DB::update("UPDATE $this->tablename SET master_dns_password='$dns_password'");
        DB::update("UPDATE $this->tablename SET slave_dns_password='$dns_password'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->dropColumn([
                'master_dns_password',
                'slave_dns_password',
            ]);
        });
    }
}
