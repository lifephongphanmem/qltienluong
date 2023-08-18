<?php

use App\Http\Controllers\baocao\donvi\dutoanluongController;
use App\Http\Controllers\baocaobangluongController;
use App\Http\Controllers\baocaonhucaukinhphi_donviController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'bao_cao'], function () {
    Route::group(['prefix' => 'don_vi'], function () {
        Route::group(['prefix' => 'du_toan'], function () {
            Route::post('tonghopcanboxa', [dutoanluongController::class, 'tonghopcanboxa']);
            Route::post('kinhphikhongchuyentrach', [dutoanluongController::class, 'kinhphikhongchuyentrach']);
            Route::post('tonghopbienche', [dutoanluongController::class, 'tonghopbienche']);
            Route::post('tonghophopdong', [dutoanluongController::class, 'tonghophopdong']);
        });
    });
    /*
    Route::group(['prefix'=>'don_vi'],function(){
        Route::get('','baocaoController@donvi');
        Route::post('mausl1','baocaoController@BcSLCBm1');
        Route::post('mausl2','baocaoController@BcSLCBm2');
        Route::post('mausl3','baocaoController@BcSLCBm3');
        Route::post('maudv','baocaoController@BcCLDangVien');
    });

    Route::group(['prefix'=>'mau_chuan'],function(){
        Route::get('','baocaoController@mauchuan');
        Route::post('BcDSTuyenDungTT08','baocaoController@BcDSTuyenDungTT08');
        Route::post('BcDSTuyenDungTT10','baocaoController@BcDSTuyenDungTT10');
        Route::post('BcDSCC','baocaoController@BcDSCC');
        Route::post('BcDSVC','baocaoController@BcDSVC');
        Route::post('BcSLCLCC','baocaoController@BcSLCLCC');
        Route::post('BcSLCLVC','baocaoController@BcSLCLVC');
        Route::post('BcDSCCCVCC','baocaoController@BcDSCCCVCC');
        Route::post('BcDSVCCVCC','baocaoController@BcDSVCCVCC');
        Route::post('BcSLCLCC_TT11','baocaoController@BcSLCLCC_TT11');
    });
    */
    Route::group(['prefix' => 'thong_tu_67'], function () {
        Route::group(['prefix' => 'don_vi'], function () {
            Route::post('mau2a1', 'baocaonhucaukinhphi_donviController@mau2a1');
            Route::post('mau2a2', 'baocaonhucaukinhphi_donviController@mau2a2');
            // Route::post('mau2a2_kh','baocaonhucaukinhphi_donviController@mau2a2_kh');
            //Route::get('mau2c','baocaothongtu67Controller@mau2c_donvi');
            // Route::get('mau2d','baocaothongtu67Controller@mau2d_donvi');
            Route::post('mau4a', [baocaonhucaukinhphi_donviController::class, 'mau4a']);
            Route::post('mau4b', 'baocaonhucaukinhphi_donviController@mau4b');
            //Route::get('mau2e','baocaothongtu67Controller@mau2e_donvi');
            // Route::get('mau2g','baocaothongtu67Controller@mau2g_donvi');
            //Route::get('mau2h','baocaothongtu67Controller@mau2h_donvi');

            //Mẫu Vạn Ninh
            Route::post('mau2a2_kh', [baocaonhucaukinhphi_donviController::class, 'mau2a2_kh']);
            Route::post('mau2b', 'baocaonhucaukinhphi_donviController@mau2b_donvi');
            Route::post('mau2đ', 'baocaonhucaukinhphi_donviController@mau2đ_donvi');
            Route::post('mau2e', 'baocaonhucaukinhphi_donviController@mau2e_donvi');
            Route::post('mau2g', 'baocaonhucaukinhphi_donviController@mau2g_donvi');
        });

        Route::group(['prefix' => 'khoi'], function () {
            Route::get('mau2a1', 'baocaothongtu67Controller@mau2a1_khoi');
            Route::get('mau2a2', 'baocaothongtu67Controller@mau2a2_khoi');
            Route::get('mau2b', 'baocaothongtu67Controller@mau2b_donvi');
            Route::get('mau2c', 'baocaothongtu67Controller@mau2c_khoi');
            Route::get('mau2d', 'baocaothongtu67Controller@mau2d_donvi');
            Route::get('mau4a', 'baocaothongtu67Controller@mau4a_khoi');
            Route::get('mau4b', 'baocaothongtu67Controller@mau4b_khoi');
        });

        Route::group(['prefix' => 'huyen'], function () {
            Route::post('mau2a1', 'baocaott67huyenController@mau2a1_huyen');
            Route::post('mau2a1excel', 'baocaott67huyenController@mau2a1_huyen_excel');
            Route::post('mau2a2', 'baocaott67huyenController@mau2a2_huyen');
            Route::post('mau2a2excel', 'baocaott67huyenController@mau2a2_huyen_excel');
            Route::post('mau2b', 'baocaott67huyenController@mau2b_huyen');
            Route::post('mau2bexcel', 'baocaott67huyenController@mau2b_huyen_excel');
            Route::post('mau2c', 'baocaott67huyenController@mau2c_huyen');
            Route::post('mau2cexcel', 'baocaott67huyenController@mau2c_huyen_excel');
            Route::post('mau2d', 'baocaott67huyenController@mau2d_huyen');
            Route::post('mau2dexcel', 'baocaott67huyenController@mau2d_huyen_excel');
            Route::post('mau2dd', 'baocaott67huyenController@mau2đ_huyen');
            Route::post('mau2e', 'baocaott67huyenController@mau2e_huyen');
            Route::post('mau2eexcel', 'baocaott67huyenController@mau2e_huyen_excel');
            Route::post('mau2g', 'baocaott67huyenController@mau2g_huyen');
            Route::post('mau2gexcel', 'baocaott67huyenController@mau2g_huyen_excel');
            Route::post('mau2h', 'baocaott67huyenController@mau2h_huyen');
            Route::post('mau2hexcel', 'baocaott67huyenController@mau2h_huyen_excel');
            Route::post('mau4a', 'baocaott67huyenController@mau4a_huyen');
            Route::post('mau4aexcel', 'baocaott67huyenController@mau4a_huyen_excel');
            Route::post('mau4b', 'baocaott67huyenController@mau4b_huyen');
            Route::post('mau4bexcel', 'baocaott67huyenController@mau4b_huyen_excel');
            Route::post('mau4bbs', 'baocaott67huyenController@mau4bbs_huyen');
            Route::post('mau4bbsexcel', 'baocaott67huyenController@mau4bbs_huyen_excel');
            Route::post('mau2a', 'baocaott67huyenController@mau2a_huyen');
        });
    });

    Route::group(['prefix' => 'bang_luong'], function () {
        Route::get('tong_hop', [baocaobangluongController::class, 'index_th']);
        Route::get('', 'baocaobangluongController@index'); //Form chung
        //Các mẫu báo cáo tại đơn vị
        Route::group(['prefix' => 'don_vi'], function () {
            Route::post('mauc02ahd', 'baocaobangluongController@mauc02ahd');
            Route::post('mauc02ahd_mau2', 'baocaobangluongController@mauc02ahd_mau2');
            Route::post('mauc02ahd_mau3', 'baocaobangluongController@mauc02ahd_mau3');
            Route::post('mauc02x', 'baocaobangluongController@mauc02x');
            Route::post('maubaohiem', 'baocaobangluongController@maubaohiem');
            Route::post('chitraluong', 'baocaobangluongController@chitraluong');
            // Route::post('dutoanluong', 'baocaobangluongController@dutoanluong'); //11/02/2023
            Route::post('nangluong', 'baocaobangluongController@nangluong');
            Route::post('dangkyluong', 'baocaobangluongController@dangkyluong');

            Route::post('dsnangluong', 'baocaobangluongController@dsnangluong');
            Route::post('chitratheonkp', 'baocaobangluongController@chitratheonkp');
            Route::post('chitratheocb', 'baocaobangluongController@chitratheocb');
            Route::post('dscanbo', 'baocaobangluongController@dscanbo');
        });

        Route::group(['prefix' => 'khoi'], function () {
            Route::post('chitraluong_ct', 'baocaobangluongController@chitraluong_ct_khoim');
            Route::post('chitraluong_ctpl', 'baocaobangluongController@chitraluong_ct_pl');
            Route::post('chitraluong_ctexcel', 'baocaobangluongController@chitraluong_ct_khoim_excel');
            //Route::post('chitraluong_ct','baocaobangluongController@chitraluong_ct_khoi');
            //Route::post('chitraluong_th','baocaobangluongController@chitraluong_th_khoi');
            Route::post('chitraluong_th', 'baocaobangluongController@chitraluong_th_khoim');
            Route::post('chitraluong_thexcel', 'baocaobangluongController@chitraluong_th_khoim_excel');
            Route::post('dutoanluong', 'baocaobangluongController@dutoanluong_khoi');
            Route::post('baocaohesoluong', 'baocaobangluongController@baocaohesoluong_khoi');
            Route::post('baocaohesoluongexcel', 'baocaobangluongController@baocaohesoluong_khoi_excel');
        });

        Route::group(['prefix' => 'huyen'], function () {
            Route::post('chitraluong_ct', 'baocaobangluongController@chitraluong_ct_huyen');
            Route::post('chitraluong_ct_CR', 'baocaobangluongController@chitraluong_ct_huyenCR');
            Route::post('chitraluong_ctexcel', 'baocaobangluongController@chitraluong_ct_huyen_excel');
            Route::post('chitraluong_th', 'baocaobangluongController@chitraluong_th_huyen');
            Route::post('chitraluong_thexcel', 'baocaobangluongController@chitraluong_th_huyen_excel');
            Route::post('dutoanluong', 'baocaobangluongController@dutoanluong_huyen');
            Route::post('dutoanluongCR', 'baocaobangluongController@dutoanluong_huyen_CR');
            Route::post('tonghopluongCR', 'baocaobangluongController@tonghopluong_huyen_CR');
            Route::post('nguonkinhphiCR', 'baocaobangluongController@nguonkinhphi_huyen_CR');
            Route::post('baocaohesoluong', 'baocaobangluongController@baocaohesoluong');
            Route::post('baocaohesoluongexcel', 'baocaobangluongController@baocaohesoluongexcel');

            //Tạm cho huyện Vạn Ninh
            Route::post('tonghopluong_th', 'baocaobangluongController@tonghopluong_huyen_th');
            Route::post('tonghopluong_vn', 'baocaobangluongController@tonghopluong_vn');            
        });

        Route::group(['prefix' => 'tinh'], function () {
            Route::post('dutoanluongCR', 'baocaobangluongController@dutoanluong_tinh_CR');
        });

        Route::get('dutoanluong_th', 'baocaobangluongController@dutoanluong_th');
        Route::post('chitraluong_th', 'baocaobangluongController@chitraluong_th');
        Route::post('mauc02ahd_th', 'baocaobangluongController@mauc02ahd_th');
        Route::post('mauc02x_th', 'baocaobangluongController@mauc02ahd_th'); //chưa xây dựng
        Route::post('maubaohiem_th', 'baocaobangluongController@maubaohiem'); //chưa xây dựng
    });
});
