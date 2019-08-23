<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\dataController as data;

class Delbangluong_ct implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    //public $timeout = 60; //thời gian max chạy Job
    //public $tries = 3; // số lần chạy Job nếu lỗi thì bỏ qua
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $mabl;
    public $thang;

    public function __construct($mabl, $thang)
    {
        $this->mabl = $mabl;
        $this->thang = $thang;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new data())->destroyBangluong_ct($this->thang, $this->mabl);
        //\App\bangluong_ct::where('mabl',$this->mabl)->delete();
    }
}
