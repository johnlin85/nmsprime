<?php

use Illuminate\Database\Schema\Blueprint;

class CreateDebtTable extends BaseMigration
{
    protected $tablename = 'debt';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
            $this->up_table_generic($table);

            // Relations
            $table->integer('contract_id');
            // $table->string('mandateref');
            $table->integer('sepamandate_id')->nullable();
            $table->integer('invoice_id')->nullable();

            $table->date('date');           // Date of transaction
            $table->float('amount', 10, 4);
            $table->float('bank_fee', 10, 4);
            $table->float('total_fee', 10, 4);
            $table->string('description')->nullable();

            $table->foreign('contract_id')->references('id')->on('contract')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sepamandate_id')->references('id')->on('sepamandate')->onDelete('set null')->onUpdate('cascade');

            return parent::up();
        });
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
