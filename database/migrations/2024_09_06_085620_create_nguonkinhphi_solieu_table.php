<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguonkinhphiSolieuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguonkinhphi_solieu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('masodv')->nullable();
            $table->string('mabieumau')->nullable();
            $table->string('noidung')->nullable();
            $table->string('linhvuchoatdong')->nullable();
            $table->string('phanloainguon')->nullable();
            $table->string('maphanloai')->nullable();
            $table->double('solieu01')->default(0);
            $table->double('solieu02')->default(0);
            $table->double('solieu03')->default(0);
            $table->double('solieu04')->default(0);
            $table->double('solieu05')->default(0);
            $table->double('solieu06')->default(0);
            $table->double('solieu07')->default(0);
            $table->double('solieu08')->default(0);
            $table->double('solieu09')->default(0);
            $table->double('solieu10')->default(0);
            $table->double('solieu11')->default(0);
            $table->double('solieu12')->default(0);
            $table->double('solieu13')->default(0);
            $table->double('solieu14')->default(0);
            $table->double('solieu15')->default(0);
            $table->double('solieu16')->default(0);
            $table->double('solieu17')->default(0);
            $table->double('solieu18')->default(0);
            $table->double('solieu19')->default(0);
            $table->string('madv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nguonkinhphi_solieu');
    }
}
