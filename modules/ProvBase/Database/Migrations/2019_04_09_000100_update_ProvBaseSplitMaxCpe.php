<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProvBaseSplitMaxCpe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provbase', function (Blueprint $table) {
            $table->renameColumn('max_cpe', 'max_cpe_priv');
        });
        Schema::table('provbase', function (Blueprint $table) {
            $table->smallInteger('max_cpe_pub')->nullable()->after('max_cpe_priv');
        });

        // no where filter is set, as there should be only one row with id 1
        DB::update("UPDATE provbase SET max_cpe_pub = '2';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provbase', function (Blueprint $table) {
            $table->dropColumn(['max_cpe_pub']);
            $table->renameColumn('max_cpe_priv', 'max_cpe');
        });
    }
}
