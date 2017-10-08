<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangluongctTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bangluong_ct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mabl', 50)->nullable();
            $table->string('macvcq', 50)->nullable();
            $table->string('mapb', 50)->nullable();

            $table->string('msngbac', 50)->nullable();
            $table->string('mact', 50)->nullable();
            $table->string('macanbo', 50)->nullable();
            $table->string('tencanbo', 50)->nullable();
            $table->string('masoms', 50)->nullable();
            $table->double('heso')->default(0);
            $table->double('vuotkhung')->default(0);

            $table->double('pcct')->default(0);
            $table->double('pckct')->default(0);
            $table->double('pck')->default(0);
            $table->double('pccv')->default(0);
            $table->double('pckv')->default(0);
            $table->double('pcth')->default(0);
            $table->double('pcdd')->default(0);
            $table->double('pcdh')->default(0);
            $table->double('pcld')->default(0);
            $table->double('pcdbqh')->default(0);
            $table->double('pcudn')->default(0);
            $table->double('pctn')->default(0);
            $table->double('pctnn')->default(0);
            $table->double('pcdbn')->default(0);
            $table->double('pcvk')->default(0);
            $table->double('pckn')->default(0);
            $table->double('pcdang')->default(0);
            $table->double('pccovu')->default(0);
            $table->double('pclt')->default(0);
            $table->double('pcd')->default(0);
            $table->double('pctr')->default(0);
            $table->double('pctnvk')->default(0);
            $table->double('pcbdhdcu')->default(0);

            $table->double('tonghs')->default(0);
            $table->double('ttl')->default(0);
            $table->double('giaml')->default(0);
            $table->double('bhct')->default(0);
            $table->double('tluong')->default(0);
            $table->double('stbhxh')->default(0);
            $table->double('stbhyt')->default(0);
            $table->double('stkpcd')->default(0);
            $table->double('stbhtn')->default(0);
            $table->double('ttbh')->default(0);
            $table->double('gttncn')->default(0);
            $table->double('luongtn')->default(0);

            $table->double('stbhxh_dv')->default(0);
            $table->double('stbhyt_dv')->default(0);
            $table->double('stkpcd_dv')->default(0);
            $table->double('stbhtn_dv')->default(0);
            $table->double('ttbh_dv')->default(0);
            $table->timestamps();
        });
    }

    public function integer($column, $autoIncrement = false, $unsigned = false)
    {
        return $this->addColumn('integer', $column, compact('autoIncrement', 'unsigned'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bangluong_ct');
    }
}
