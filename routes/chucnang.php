<?php

use App\Http\Controllers\bangluong_thuyetminhController;
use App\Http\Controllers\bangluongController;
use App\Http\Controllers\dutoanluong_khoiController;
use App\Http\Controllers\tonghopluong_huyenController;
use App\Http\Controllers\tonghopnguon_huyenController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'chuc_nang'], function () {
    Route::group(['prefix' => 'bang_luong'], function () {
        Route::get('danh_sach',[bangluongController::class,'index']);
        Route::get('chi_tra', 'bangluongController@chitra');
        Route::post('store', 'bangluongController@store');
        Route::post('store_truylinh', 'bangluongController@store_truylinh');
        Route::post('store_truc', 'bangluongController@store_truc');
        Route::post('store_ctp', 'bangluongController@store_ctp');
        Route::post('store_trichnop', 'bangluongController@store_trichnop');

        Route::get('store_mau', 'bangluongController@store_mau');

        Route::get('bang_luong', 'bangluongController@show');
        Route::get('cap_nhat', 'bangluongController@capnhat');
        Route::get('cap_nhat_nkp', 'bangluongController@capnhat_nkp');
        Route::get('tang_giam', 'bangluongController@tanggiam');
        //Route::get('/maso={mabl}','bangluongController@show');
        Route::get('in/maso={mabl}', 'bangluongController@inbangluong');
        //Route::get('inbangluong/maso={mabl}','bangluongController@inbangluong_sotien');
        //Route::get('inmau3/maso={mabl}','bangluongController@inbangluongmau3'); //mẫu làm theo y.c lạng sơn
        //Route::get('inmau4/maso={mabl}','bangluongController@inbangluongmau4'); //mẫu làm theo y.c lạng sơn
        Route::get('in_bh/maso={mabl}', 'bangluongController@inbaohiem');
        Route::get('can_bo', 'bangluongController@detail');
        Route::get('chi_khac/can_bo', 'bangluongController@detail_chikhac');
        Route::post('updatect', 'bangluongController@updatect');
        Route::post('updatect_truylinh', 'bangluongController@updatect_truylinh');
        Route::post('updatect_chikhac', 'bangluongController@updatect_chikhac');
        Route::get('del/{id}', 'bangluongController@destroy');
        Route::post('del_ct', 'bangluongController@destroy_ct');
        Route::get('del_ct_chikhac/{id}', 'bangluongController@destroy_truc');

        Route::get('ThemCanBo', 'bangluongController@ThemCanBo');
        Route::post('ThemCanBo', 'bangluongController@LuuCanBo');
        Route::post('updatect_khenthuong', 'bangluongController@updatect_khenthuong');

        Route::get('get_ct', 'bangluongController@get_ct');
        Route::get('get_chitiet', 'bangluongController@get_chitiet');
        Route::post('update_chitiet', 'bangluongController@update_chitiet');

        Route::post('updatect_plct', 'bangluongController@updatect_plct');
        Route::post('updatect_ngaycong', 'bangluongController@updatect_ngaycong');
        //Route::get('cal','bangluongController@cal'); //Tính toán lại lương cán bộ
        //Route::post('importexcel','bangluongController@importexcel');

        //Tạo in bảng lương theo cách mới
        Route::get('inbangluong', 'bangluongController@printf_mautt107'); //in bảng lương tại các mẫu

        Route::post('mau01', 'bangluongController@printf_mau01');
        Route::post('mautt107', 'bangluongController@printf_mautt107');
        Route::post('mautt107_m2', 'bangluongController@printf_mautt107_m2');
        Route::post('mautt107_m3', 'bangluongController@printf_mautt107_m3');
        Route::post('mautt107_pb', 'bangluongController@printf_mautt107_pb');
        Route::post('mautt107_pb_m2', 'bangluong_inController@printf_mautt107_pb_m2');
        Route::post('mau03', 'bangluongController@printf_mau03');
        Route::post('mau04', 'bangluongController@printf_mau04');
        Route::post('mau05', 'bangluongController@printf_mau05');
        Route::post('mau06', 'bangluongController@printf_mau06');
        Route::post('mau07', 'bangluongController@printf_mau07');
        Route::post('mauquy', 'bangluongController@printf_mauquy');
        Route::post('mauds', 'bangluongController@printf_mauds');
        Route::post('mauds_m2', 'bangluong_inController@printf_mauds_m2');
        Route::post('maubh', 'bangluongController@printf_maubh');
        Route::post('maudbhdnd', 'bangluongController@printf_maudbhdnd');
        Route::post('maublpc', 'bangluongController@printf_maublpc');
        Route::post('maubchd', 'bangluongController@printf_maubchd');
        Route::post('mauqs', 'bangluongController@printf_mauqs');
        Route::get('maucd', 'bangluongController@printf_maucd');
        Route::get('maumc', 'bangluongController@printf_maumc');
        Route::get('mautinhnguyen', 'bangluongController@printf_mautinhnguyen');
        Route::post('maumtm', 'bangluongController@printf_maumtm');
        Route::post('mau09nd11', 'bangluong_inController@printf_mau09nd11');
        Route::post('mautt107_m5', 'bangluong_inController@printf_mautt107_m5');
        Route::post('mauC02_KH', 'bangluongController@printf_mauC02_KH');
        Route::post('mau09_KH', 'bangluongController@printf_mau09_KH');
        Route::post('maublcbct', 'bangluongController@printf_maublcbct'); //mẫu bảng lương cán bộ công chức, chuyên trách
        Route::post('maublcbkct', 'bangluongController@printf_maublcbkct'); //mẫu bảng lương can bộ không chuyên trách
        //mẫu bảng lương lai châu
        Route::post('mautt107_lc', 'bangluong_inController@printf_mautt107_lc');
        Route::post('mautt107_lc_xp', 'bangluong_inController@printf_mautt107_lc_xp');
        Route::post('mautt107_lc_pb', 'bangluong_inController@printf_mautt107_lc_pb');

        //truy lĩnh
        Route::post('mautruylinh', 'bangluongController@printf_mautruylinh');
        Route::post('mautruylinh_m2', 'bangluong_inController@printf_mautruylinh_m2');

        Route::get('mauthpl', 'bangluong_inController@printf_mauthpl');
        Route::get('mauthpc', 'bangluong_inController@printf_mauthpc');
        Route::post('mautt107_m4', 'bangluong_inController@printf_mautt107_m4'); //mẫu cam ranh - Khánh Hòa
        Route::post('dangkyluong', 'bangluong_inController@printf_dangkyluong');

        Route::post('dstangluong', 'bangluong_inController@printf_dstangluong');
        Route::post('dsgiamluong', 'bangluong_inController@printf_dsgiamluong');
        //chi khác (trực, công tác phí)
        Route::get('mauctphi', 'bangluong_inController@printf_mauctphi');
        Route::get('mautruc', 'bangluong_inController@printf_mautruc');
        Route::get('mautruc_m2', 'bangluong_inController@printf_mautruc_m2');

        //tổng hợp
        Route::post('mau185_th', 'bangluong_inController@printf_mau185_th');
        Route::post('mautt107_th', 'bangluong_inController@printf_mautt107_th');
        Route::post('mautt107_th_m2', 'bangluong_inController@printf_mautt107_th_m2');
        Route::post('mautt107_pb_th', 'bangluong_inController@printf_mautt107_pb_th');
        Route::post('mau07_th', 'bangluong_inController@printf_mau07_th');
        Route::post('mauds_th', 'bangluong_inController@printf_mauds_th');
        Route::post('maubh_th', 'bangluong_inController@printf_maubh_th');
        Route::post('mau09nd11_th', 'bangluong_inController@printf_mau09nd11_th'); //Dải theo phân loại công tác
        Route::post('mau09nd11_th_m2', 'bangluong_inController@printf_mau09nd11_th_m2'); //Dải theo cá nhân

        //13/02/2023 => Bỏ
        // Route::post('mau01_excel', 'bangluongController@printf_mau01_excel');
        // Route::post('mautt107_excel', 'bangluongController@printf_mautt107_excel');
        // Route::post('mau03_excel', 'bangluongController@printf_mau03_excel');
        // Route::post('mau04_excel', 'bangluongController@printf_mau04_excel');
        // Route::post('mau05_excel', 'bangluongController@printf_mau05_excel');
        // Route::post('mau07_excel', 'bangluongController@printf_mau07_excel');
        // Route::post('mau08_excel', 'bangluongController@printf_mau08_excel');
        // Route::post('mau06_excel', 'bangluongController@printf_mau06_excel');
        // Route::post('mauds_excel', 'bangluongController@printf_mauds_excel');
        // Route::post('maubh_excel', 'bangluongController@printf_maubh_excel');
        // Route::post('maudbhdnd_excel', 'bangluongController@printf_maudbhdnd_excel');
        // Route::post('maublpc_excel', 'bangluongController@printf_maublpc_excel');
        // Route::post('maubchd_excel', 'bangluongController@printf_maubchd_excel');
        // Route::post('mauqs_excel', 'bangluongController@printf_mauqs_excel');

        //mẫu thanh toán 09 cho vạn ninh
        Route::post('mau09_vn_hc', 'bangluong_inController@mau09_vn_hc');
        //chạy tạm để ko lỗi
        Route::post('mau09_vn_ck_bc', 'bangluong_inController@mau09_vn_hc');
        Route::post('mau09_vn_ck_kct', 'bangluong_inController@mau09_vn_hc');
        Route::post('mau09_vn_tm', 'bangluong_inController@mau09_vn_hc');
        //lưu
        // Route::post('mau09_vn_ck_bc', 'bangluong_inController@mau09_vn_ck_bc');
        // Route::post('mau09_vn_ck_kct', 'bangluong_inController@mau09_vn_ck_kct');
        // Route::post('mau09_vn_tm', 'bangluong_inController@mau09_vn_tm');

        //Thuyết minh chi tiết
        Route::get('ThuyetMinhChiTiet',[bangluong_thuyetminhController::class,'ThuyetMinhChiTiet']);
        Route::post('LuuThuyetMinh',[bangluong_thuyetminhController::class,'LuuThuyetMinh']);
        Route::post('TaoThuyetMinh',[bangluong_thuyetminhController::class,'TaoThuyetMinh']);
        Route::get('LayThuyetMinh',[bangluong_thuyetminhController::class,'LayThuyetMinh']);
        Route::get('XoaThuyetMinhChiTiet',[bangluong_thuyetminhController::class,'XoaThuyetMinhChiTiet']);
        Route::get('XoaThuyetMinh/{id}',[bangluong_thuyetminhController::class,'XoaThuyetMinh']);
       
    });

    Route::group(['prefix' => 'nang_luong'], function () {
        Route::get('danh_sach', 'dsnangluongController@index');
        Route::post('store', 'dsnangluongController@store'); //insert + update danh sách nâng lương

        Route::get('update/{id}', 'dsnangluongController@update');
        Route::get('create', 'dsnangluongController@create');

        Route::get('/maso={manl}', 'dsnangluongController@show');
        Route::get('chi_tiet', 'dsnangluongController@detail');
        Route::post('store_detail', 'dsnangluongController@store_detail'); //insert + update danh sách nâng lương
        Route::get('del/{id}', 'dsnangluongController@destroy');
        Route::get('nang_luong/maso={manl}', 'dsnangluongController@nang_luong');
        Route::get('deldt/{id}', 'dsnangluongController@destroydt');
        Route::post('add_canbo', 'dsnangluongController@add_canbo');
        Route::get('printf', 'dsnangluongController@printf_data');

        Route::get('get_nkp', 'dsnangluongController@getinfor_nkp');
        Route::get('store_nkp', 'dsnangluongController@store_nkp');
        Route::get('del_nkp', 'dsnangluongController@destroy_nkp');
    });

    Route::group(['prefix' => 'tham_nien'], function () {
        Route::get('danh_sach', 'dsnangthamnienController@index');
        Route::post('store', 'dsnangthamnienController@store'); //insert + update danh sách nâng lương
        Route::get('get', 'dsnangthamnienController@getinfo');
        Route::get('update/{id}', 'dsnangthamnienController@update');
        Route::get('create', 'dsnangthamnienController@create');

        Route::get('/maso={manl}', 'dsnangthamnienController@show');
        Route::get('chi_tiet', 'dsnangthamnienController@detail');
        Route::post('store_detail', 'dsnangthamnienController@store_detail'); //insert + update danh sách nâng lương
        Route::get('del/{id}', 'dsnangthamnienController@destroy');
        Route::get('nang_luong/maso={manl}', 'dsnangthamnienController@nang_luong');
        Route::get('deldt/{id}', 'dsnangthamnienController@destroydt');
        Route::post('add_canbo', 'dsnangthamnienController@add_canbo');
        Route::get('printf', 'dsnangthamnienController@printf_data');

        Route::get('get_nkp', 'dsnangthamnienController@getinfor_nkp');
        Route::get('store_nkp', 'dsnangthamnienController@store_nkp');
        Route::get('del_nkp', 'dsnangthamnienController@destroy_nkp');
    });

    Route::group(['prefix' => 'tong_hop_luong'], function () {
        Route::group(['prefix' => 'don_vi'], function () {
            Route::get('index', 'tonghopluong_donviController@index');
            Route::get('tonghop', 'tonghopluong_donviController@tonghop');
            Route::get('detail/ma_so={mathdv}', 'tonghopluong_donviController@detail');
            Route::get('detail_diaban/ma_so={mathdv}', 'tonghopluong_donviController@detail_diaban');

            Route::get('edit_detail', 'tonghopluong_donviController@edit_detail'); //chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban', 'tonghopluong_donviController@edit_detail_diaban'); //chỉnh sửa dữ liêu

            Route::post('store_detail', 'tonghopluong_donviController@store_detail'); //chỉnh sửa dữ liêu
            Route::post('store_detail_diaban', 'tonghopluong_donviController@store_detail_diaban'); //chỉnh sửa dữ liêu

            Route::post('senddata', 'tonghopluong_donviController@senddata'); //gửi dữ liệu
            Route::post('tralai', 'tonghopluong_donviController@tralai'); //trả lại dữ liệu
            Route::get('getlydo', 'tonghopluong_donviController@getlydo'); //lý do trả lại dữ liệu

            //Route::get('del/maso={mathdv}','tonghopluong_donviController@destroy');
            Route::post('destroy', 'tonghopluong_donviController@destroy');
            Route::get('del_detail/{id}', 'tonghopluong_donviController@destroy_detail'); //lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}', 'tonghopluong_donviController@printf_data');
            Route::get('printf_bl/ma_so={mathdv}', 'tonghopluong_donviController@printf_bl');
            Route::get('printf_bl_khoi/ma_so={mathdv}', 'tonghopluong_donviController@printf_bl_khoi');
            Route::get('printf_data_diaban/ma_so={mathdv}', 'tonghopluong_donviController@printf_data_diaban');
        });

        Route::group(['prefix' => 'khoi'], function () {
            Route::get('index', 'tonghopluong_khoiController@index');
            Route::get('tonghop', 'tonghopluong_khoiController@tonghop');
            Route::get('tonghop_khoi', 'tonghopluong_khoiController@tonghop_khoi');
            Route::post('tonghop_khoi', 'tonghopluong_khoiController@tonghop_khoi');
            //Route::get('tonghop','tonghopluong_khoiController@tonghop');
            Route::get('tonghop_diaban', 'tonghopluong_khoiController@tonghop_diaban');

            Route::post('tonghop_chuadaydu', 'tonghopluong_huyenController@tonghop_chuadaydu');
            //Route::post('tonghop_chuadaydu','tonghopluong_khoiController@tonghop_chuadaydu');
            Route::get('detail/ma_so={mathdv}', 'tonghopluong_khoiController@detail');
            Route::post('senddata', 'tonghopluong_khoiController@senddata'); //gửi dữ liệu
            Route::post('tralai', 'tonghopluong_khoiController@tralai'); //trả lại dữ liệu
            Route::get('getlydo', 'tonghopluong_khoiController@getlydo'); //lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}', 'tonghopluong_khoiController@printf_data');
            Route::get('printf_data_diaban/ma_so={mathdv}', 'tonghopluong_khoiController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}', 'tonghopluong_khoiController@detail_diaban');
            Route::get('edit_detail', 'tonghopluong_khoiController@edit_detail'); //chỉnh sửa dữ liêu

            Route::get('edit_detail_diaban', 'tonghopluong_khoiController@edit_detail_diaban'); //chỉnh sửa dữ liêu
            Route::post('store_detail', 'tonghopluong_khoiController@store_detail'); //chỉnh sửa dữ liêu
            Route::post('store_detail_diaban', 'tonghopluong_khoiController@store_detail_diaban'); //chỉnh sửa dữ liêu
            Route::get('inkhoito', 'tonghopluong_khoiController@inkhoito'); //in khối tổ
            Route::get('inkhoito_th', 'tonghopluong_khoiController@tonghop_khoito'); //in khối tổ
            Route::get('printf_bl_khoi', 'tonghopluong_khoiController@printf_bl_khoi'); //in bảng lương
        });

        Route::group(['prefix' => 'huyen'], function () {
            Route::get('index', [tonghopluong_huyenController::class, 'index']);
            Route::get('tonghop', 'tonghopluong_huyenController@tonghop');
            Route::get('tonghop_huyen', 'tonghopluong_huyenController@tonghop_huyen');
            Route::get('tonghopnam', 'tonghopluong_huyenController@tonghopnam');
            Route::post('chitiet_khoi', 'tonghopluong_huyenController@chitiet_khoi');
            Route::get('tonghop_diaban', 'tonghopluong_huyenController@tonghop_diaban');
            Route::post('tonghop_chuadaydu', 'tonghopluong_huyenController@tonghop_chuadaydu');
            //chưa làm
            Route::post('senddata', 'tonghopluong_huyenController@senddata'); //gửi dữ liệu
            Route::post('tralai', 'tonghopluong_huyenController@tralai'); //trả lại dữ liệu
            Route::get('getlydo', 'tonghopluong_huyenController@getlydo'); //lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}', 'tonghopluong_huyenController@printf_data_huyen');
            Route::post('printf_data_huyen', 'tonghopluong_huyenController@printf_data_huyen');
            //Route::get('printf_bl_huyen/ma_so={mathdv}','tonghopluong_huyenController@printf_bl_huyen');
            Route::get('printf_bl_huyen', 'tonghopluong_huyenController@printf_bl_huyen');
            Route::get('printf_bl_huyenCR', 'tonghopluong_huyenController@printf_bl_huyenCR');
            Route::get('printf_data_diaban/ma_so={mathdv}', 'tonghopluong_huyenController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}', 'tonghopluong_huyenController@detail_diaban');
            Route::get('edit_detail', 'tonghopluong_huyenController@edit_detail'); //chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban', 'tonghopluong_huyenController@edit_detail_diaban'); //chỉnh sửa dữ liêu
            Route::post('store_detail', 'tonghopluong_huyenController@store_detail'); //chỉnh sửa dữ liêu
            Route::post('store_detail_diaban', 'tonghopluong_huyenController@store_detail_diaban'); //chỉnh sửa dữ liêu
            Route::get('thanh_toan_CR', 'tonghopluong_huyenController@thanhtoanluongCR');
            Route::get('inkhoito', 'tonghopluong_huyenController@inkhoito');

            //2023.07.14 Mẫu theo yêu STC
            Route::get('TongHop', 'tonghopluong_huyen_baocaoController@TongHop_PhanLoaiDV');
            //Báo cáo khác
            Route::post('DSDonVi', 'baocaobangluongController@DSDonVi');
        });

        Route::group(['prefix' => 'tinh'], function () {
            Route::get('index', 'tonghopluong_tinhController@index');

            //chưa làm
            Route::get('tonghop', 'tonghopluong_huyenController@tonghop');
            Route::get('tonghop_diaban', 'tonghopluong_huyenController@tonghop_diaban');
            Route::post('tonghop_chuadaydu', 'tonghopluong_huyenController@tonghop_chuadaydu');
            //chưa làm
            Route::post('senddata', 'tonghopluong_huyenController@senddata'); //gửi dữ liệu
            Route::post('tralai', 'tonghopluong_tinhController@tralai'); //trả lại dữ liệu
            Route::get('getlydo', 'tonghopluong_huyenController@getlydo'); //lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}', 'tonghopluong_huyenController@printf_data');
            Route::get('printf_data_diaban/ma_so={mathdv}', 'tonghopluong_huyenController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}', 'tonghopluong_huyenController@detail_diaban');
            Route::get('edit_detail', 'tonghopluong_huyenController@edit_detail'); //chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban', 'tonghopluong_huyenController@edit_detail_diaban'); //chỉnh sửa dữ liêu
            Route::post('store_detail', 'tonghopluong_huyenController@store_detail'); //chỉnh sửa dữ liêu
            Route::post('store_detail_diaban', 'tonghopluong_huyenController@store_detail_diaban'); //chỉnh sửa dữ liêu
        });
    });

    Route::group(['prefix' => 'xem_du_lieu'], function () {
        Route::get('index', 'xemdulieucapduoiController@donvi_luong');
        Route::get('huyen', 'xemdulieucapduoiController@index_huyen');
        Route::get('tinh', 'xemdulieucapduoiController@index_tinh');
        Route::get('tinh/solieu', 'xemdulieucapduoiController@tonghop_huyen');
        Route::post('danhsach', 'xemdulieucapduoiController@danhsach');
        Route::post('danhsachth', 'tonghopluong_tinhController@danhsachdv');
        Route::post('danhsach_thh', 'tonghopluong_huyenController@danhsachth');

        Route::group(['prefix' => 'nguon'], function () {
            Route::get('khoi', 'xemdulieu_nguonController@index_khoi');
            Route::get('huyen', 'xemdulieu_nguonController@index_huyen');
            Route::get('tinh', 'xemdulieu_nguonController@index_tinh');
            Route::post('danhsach', 'xemdulieu_nguonController@danhsach');
            
            Route::get('getKhoiToCongTac', 'xemdulieu_nguonController@getKhoiToCongTac');
        });

        Route::group(['prefix' => 'du_toan'], function () {
            Route::get('khoi', 'xemdulieu_dutoanController@index_khoi');
            Route::get('huyen', 'xemdulieu_dutoanController@index_huyen');
            Route::get('tinh', 'xemdulieu_dutoanController@index_tinh');
            Route::post('danhsach', 'xemdulieu_dutoanController@danhsach');
        });
    });

    Route::group(['prefix' => 'tong_hop_nguon'], function () {
        Route::get('index', 'tonghopnguonController@index');
        Route::post('tralai', 'tonghopnguonController@tralai');
        Route::get('ma_so={sohieu}/in_khoi', 'tonghopnguonController@printf_khoi');

        //Route::get('huyen','tonghopnguonController@index_huyen');
        Route::post('tralai_huyen', 'tonghopnguonController@tralai_huyen');
        Route::get('ma_so={sohieu}/in_huyen', 'tonghopnguonController@printf_huyen');

        Route::get('tinh', 'tonghopnguonController@index_tinh');
        Route::post('tralai_tinh', 'tonghopnguonController@tralai_tinh');
        Route::get('/in_tinh', 'tonghopnguonController@printf_tinh');

        Route::group(['prefix' => 'huyen'], function () {
            Route::get('index', [tonghopnguon_huyenController::class,'index']);
            Route::post('tralai', 'tonghopnguon_huyenController@tralai');
            Route::get('ma_so={sohieu}/in', 'tonghopnguon_huyenController@printf');
            //Route::get('tonghop','tonghopnguon_khoiController@tonghop');//tạm
            //Route::get('tonghop', 'tonghopnguon_huyenController@tonghop'); //tạm
            Route::post('senddata', 'tonghopnguon_huyenController@senddata'); //gửi dữ liệu lên tỉnh
            Route::get('getlydo', 'tonghopnguon_huyenController@getlydo');
            //2023.06.13 Thiết kế kết xuất
            Route::post('tonghop', 'tonghopnguon_huyenController@tonghop');
            Route::post('tonghop_m2', 'tonghopnguon_huyenController@tonghop_m2');
            Route::post('tonghop_pldv', 'tonghopnguon_huyenController@tonghop_pldv');

            Route::post('mau2a', 'tonghopnguon_huyenController@mau2a');
            Route::post('mau2a_2', 'tonghopnguon_huyenController@mau2a_2');
            Route::post('mau2a_vn', 'tonghopnguon_huyenController@mau2a_vn');
            Route::post('mau2a_pldv', 'tonghopnguon_huyenController@mau2a_pldv');

            Route::post('mau2b', 'tonghopnguon_huyenController@mau2b');
            Route::post('mau2c', 'tonghopnguon_huyenController@mau2c');
            Route::post('mau2d', 'tonghopnguon_huyenController@mau2d');
            Route::post('mau2dd', 'tonghopnguon_huyenController@mau2dd');
            Route::post('mau2e', 'tonghopnguon_huyenController@mau2e');
            Route::post('mau2g', 'tonghopnguon_huyenController@mau2g');
            Route::post('mau2h', 'tonghopnguon_huyenController@mau2h');
            Route::post('mau2i', 'tonghopnguon_huyenController@mau2i');
            Route::post('mau2k', 'tonghopnguon_huyenController@mau2k');
            Route::post('mau2l', 'tonghopnguon_huyenController@mau2l');
            
            Route::post('mau4a', 'tonghopnguon_huyenController@mau4a');
            Route::post('mau4b', 'tonghopnguon_huyenController@mau4b');
            //2023.06.20 Dữ liệu đơn vị quản lý nhập
            Route::post('dulieu_dvql', 'tonghopnguon_huyenController@luu_dulieu_dvql');
            Route::get('dulieu_dvql', 'tonghopnguon_huyenController@dulieu_dvql');

        });

        Route::group(['prefix' => 'khoi'], function () {
            Route::get('index', 'tonghopnguon_khoiController@index');
            Route::post('tralai', 'tonghopnguon_khoiController@tralai'); //trả về đơn vị
            Route::get('ma_so={sohieu}/in', 'tonghopnguon_khoiController@printf');

            Route::post('senddata', 'tonghopnguon_khoiController@senddata'); //gửi dữ liệu lên huyện
            Route::get('getlydo', 'tonghopnguon_khoiController@getlydo'); //lý do trả lại dữ liệu
            Route::get('tonghop', 'tonghopnguon_khoiController@tonghop'); //tạm
        });
        Route::group(['prefix' => 'tinh'], function () {
            Route::get('index', 'tonghopnguon_tinhController@index');
            Route::post('tralai', 'tonghopnguon_tinhController@tralai');
            Route::get('getlydo', 'tonghopnguon_tinhController@getlydo');
        });
    });

    Route::group(['prefix' => 'du_toan_luong'], function () {

        Route::group(['prefix' => 'huyen'], function () {
            Route::get('index', 'dutoanluong_huyenController@index');
            Route::post('tralai', 'dutoanluong_huyenController@tralai');
            Route::post('senddata', 'dutoanluong_huyenController@senddata'); //gửi dữ liệu lên tỉnh
            Route::get('getlydo', 'dutoanluong_huyenController@getlydo'); //gửi dữ liệu lên tỉnh
            Route::get('tonghop', 'dutoanluong_huyenController@tonghopCR');
            Route::get('tonghopct', 'dutoanluong_huyenController@tonghopct');
            Route::get('printf', 'dutoanluong_huyenController@printf'); //in một khối trong khối
            Route::post('chitietbl', 'dutoanluong_huyenController@chitietbl'); //in chi tiết bảng lương đơn vị
            Route::post('chitietblCR', 'dutoanluong_huyenController@chitietblCR'); //in chi tiết bảng lương đơn vị
            Route::get('nangluongth', 'dutoanluong_huyenController@nangluongth'); //in chi tiết bảng lương đơn vị
            // Route::get('guitn', 'GuiTinNhanController@guitin'); //in chi tiết bảng lương đơn vị
            //Mới làm lại 07/07/2022
            Route::post('tao_du_toan', 'dutoanluong_huyenController@tao_du_toan');

            //Thiết kế mẫu cho Vạn Ninh
            Route::post('kinhphikhongchuyentrach', 'dutoanluong_insolieu_huyenController@kinhphikhongchuyentrach');
            Route::post('tonghopbienche', 'dutoanluong_insolieu_huyenController@tonghopbienche');
            Route::post('tonghopbienche_m2', 'dutoanluong_insolieu_huyenController@tonghopbienche_m2');
            Route::post('tonghophopdong', 'dutoanluong_insolieu_huyenController@tonghophopdong');
            Route::post('tonghophopdong_m2', 'dutoanluong_insolieu_huyenController@tonghophopdong_m2');
            Route::post('tonghopcanboxa', 'dutoanluong_insolieu_huyenController@tonghopcanboxa');
            Route::post('tonghopcanbohdnd', 'dutoanluong_insolieu_huyenController@tonghopcanbohdnd');
            Route::post('danhsachdonvi', 'dutoanluong_insolieu_huyenController@danhsachdonvi');
        });

        Route::group(['prefix' => 'khoi'], function () {
            Route::get('index', [dutoanluong_khoiController::class,'index']);
            Route::post('tralai', 'dutoanluong_khoiController@tralai'); //trả về đơn vị
            Route::post('senddata', 'dutoanluong_khoiController@senddata'); //gửi dữ liệu lên huyện

            Route::get('tonghop', 'dutoanluong_khoiController@tonghop');
            Route::get('printf', 'dutoanluong_khoiController@printf'); //in TH một đơn vị trong khối
            Route::get('chitietbl', 'dutoanluong_khoiController@printfbl'); //in một đơn vị trong khối
        });

        Route::group(['prefix' => 'tinh'], function () {
            Route::get('index', 'dutoanluong_tinhController@index');
            Route::post('tralai', 'dutoanluong_tinhController@tralai');
            Route::post('tao_du_toan', 'dutoanluong_tinhController@tao_du_toan');
        });
    });

    Route::group(['prefix' => 'dang_ky_luong'], function () {
        Route::get('danh_sach', 'bangluongdangkyController@dangky');
        Route::post('store', 'bangluongdangkyController@store');
        Route::get('del/{id}', 'bangluongdangkyController@destroy');
        Route::get('/maso={mabl}', 'bangluongdangkyController@show');
        Route::get('get', 'bangluongdangkyController@getinfo');
        Route::get('', 'bangluongdangkyController@detail');
        Route::post('updatect/{id}', 'bangluongdangkyController@updatect');

        //Tạo in bảng lương theo cách mới
        Route::post('mau01', 'bangluongdangkyController@printf_mau01');
        Route::post('mautt107', 'bangluongdangkyController@printf_mautt107');
        Route::post('mautt107_m2', 'bangluongdangkyController@printf_mautt107_m2');
    });
});