<?php
Route::get('', 'HomeController@index');
Route::get('test', 'bangluong_inController@test');
Route::get('ajaxtest','ajaxController@test');


Route::get('/setting','HomeController@setting');
Route::post('/setting','HomeController@upsetting');
Route::get('danh_sach_tai_khoan','HomeController@listusers');
Route::get('fix_pc','dmphucapController@fix_mapc');
//Route::get('fix_ct','dmphucapController@fix_ct');
//Route::get('fix_dv','hosocanboController@upd_dm');


// <editor-fold defaultstate="collapsed" desc="--Hệ thống--">
    // <editor-fold defaultstate="collapsed" desc="--Danh mục--">

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="--Quản lý chung--">

    // </editor-fold>
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="--Setting--">
Route::get('cau_hinh_he_thong','GeneralConfigsController@index');
Route::get('cau_hinh_he_thong/{id}/edit','GeneralConfigsController@edit');
Route::patch('cau_hinh_he_thong/{id}','GeneralConfigsController@update');

//Users
Route::get('login','UsersController@login');
Route::post('signin','UsersController@signin');
Route::get('/change-password','UsersController@cp');
Route::post('/change-password','UsersController@cpw');
Route::get('/checkpass','UsersController@checkpass');
Route::get('/checkuser','UsersController@checkuser');
Route::get('/checkmasothue','UsersController@checkmasothue');
Route::get('logout','UsersController@logout');
Route::get('users/{id}/edit','UsersController@edit');
Route::patch('users/{id}','UsersController@update');
Route::get('users/{id}/phan-quyen','UsersController@permission');
Route::post('users/phan-quyen','UsersController@uppermission');
Route::post('users/delete','UsersController@destroy');
Route::get('users/lock/{id}/{pl}','UsersController@lockuser');
Route::get('users/unlock/{id}/{pl}','UsersController@unlockuser');
//EndUsers
// </editor-fold>//End Setting

Route::group(['prefix'=>'user'],function(){
    Route::post('signin','usersController@signin');
    Route::get('logout','usersController@logout');
});

Route::group(['prefix'=>'danh_muc'],function(){
    Route::group(['prefix'=>'phong_ban'],function(){
        Route::get('index','dmphongbanController@index');
        Route::get('del/{id}','dmphongbanController@destroy');
        Route::get('add','dmphongbanController@store');
        Route::get('update','dmphongbanController@update');
        Route::get('get','dmphongbanController@getinfo');
    });

    Route::group(['prefix'=>'chuc_vu'],function(){
        Route::get('index','dmchucvucqController@index_donvi');
        Route::get('del/{id}','dmchucvucqController@destroy_donvi');
        Route::get('add','dmchucvucqController@store');
        Route::get('update','dmchucvucqController@update');
        Route::get('get','dmchucvucqController@getinfo');
    });

    Route::group(['prefix'=>'dan_toc'],function(){
        Route::get('index','dmdantocController@index');
        Route::get('store','dmdantocController@store');
        Route::get('del/{id}','dmdantocController@destroy');
    });

    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('don_vi','dmphucapController@index_donvi');
        Route::get('don_vi/edit','dmphucapController@edit_donvi');
        Route::post('don_vi/update','dmphucapController@update_donvi');

        Route::get('don_vi/anhien','dmphucapController@anhien');
        Route::post('don_vi/default_pc','dmphucapController@default_pc');
    });

    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('index','dmphanloaictController@index');
        Route::get('del/{id}','dmphanloaictController@destroy');
        Route::get('add','dmphanloaictController@store');
        Route::get('update','dmphanloaictController@update');
        Route::get('get','dmphanloaictController@getinfo');

        Route::get('ma_so={macongtac}','dmphanloaictController@detail');
        Route::get('del_detail/{id}','dmphanloaictController@destroy_detail');
        Route::get('add_detail','dmphanloaictController@store_detail');
        Route::get('update_detail','dmphanloaictController@update_detail');
        Route::get('get_detail','dmphanloaictController@getinfo_detail');
    });

    Route::group(['prefix'=>'don_vi'],function(){
        Route::get('maso={maso}','dmdonviController@index');
        Route::get('store','dmdonviController@store');
        Route::get('del/{id}','dmdonviController@destroy');
        Route::get('change/maso={madv}','dmdonviController@change');
    });

    Route::group(['prefix'=>'khoi_pb'],function(){
        Route::get('index','dmkhoipbController@index');
        Route::get('store','dmkhoipbController@store');
        Route::get('del/{id}','dmkhoipbController@destroy');
    });

    Route::group(['prefix'=>'khu_vuc'],function(){
        Route::get('danh_sach','dmdonvibaocaoController@index');
        Route::get('get','dmdonvibaocaoController@getinfo');
        Route::get('add','dmdonvibaocaoController@store');
        Route::get('update','dmdonvibaocaoController@update');
        Route::get('del/{maso}','dmdonvibaocaoController@destroy');

        Route::get('chi_tiet','dmdonvibaocaoController@list_donvi');
        Route::get('ma_so={makhuvuc}&don_vi={madonvi}/edit','dmdonvibaocaoController@show_donvi');
        Route::get('create','dmdonvibaocaoController@create_donvi');

        Route::patch('update_donvi','dmdonvibaocaoController@update_donvi');
        Route::post('store_donvi','dmdonvibaocaoController@store_donvi');
        Route::get('del_donvi/{madv}','dmdonvibaocaoController@destroy_donvi');
        Route::get('get_list_unit','dmdonvibaocaoController@get_list_unit');
        Route::get('set_management','dmdonvibaocaoController@set_management');

        Route::get('getPhanLoai','ajaxController@getPhanLoai');
    });

    Route::group(['prefix'=>'tai_khoan'],function(){
        Route::get('list_user','UsersController@list_users');
        Route::get('ma_so={madv}/create','UsersController@create');
        Route::post('add_user','UsersController@store');

        Route::get('ma_so={taikhoan}/permission','UsersController@permission');
        Route::post('ma_so={taikhoan}/uppermission','UsersController@uppermission');
        Route::get('del_taikhoan/{madv}','UsersController@destroy');
    });

    Route::group(['prefix'=>'nguon_kinh_phi'],function(){
        Route::get('index','dmnguonkinhphiController@index');
        Route::get('del/{id}','dmnguonkinhphiController@destroy');
        Route::get('add','dmnguonkinhphiController@store');
        Route::get('update','dmnguonkinhphiController@update');
        Route::get('get','dmnguonkinhphiController@getinfo');
    });

    Route::group(['prefix'=>'pl_don_vi'],function(){
        Route::get('index','dmphanloaidonviController@index');
        Route::get('del/{id}','dmphanloaidonviController@destroy');
        Route::get('add','dmphanloaidonviController@store');
        Route::get('update','dmphanloaidonviController@update');
        Route::get('get','dmphanloaidonviController@getinfo');

        Route::get('','dmphanloaidonviController@phucap');
        Route::get('edit','dmphanloaidonviController@edit_phucap');
        Route::post('phu_cap/update','dmphanloaidonviController@update_phucap');
        Route::get('anhien','dmphanloaidonviController@anhien');
    });

    Route::group(['prefix'=>'ngach_bac'],function(){
        Route::get('index','dmngachluongController@index');
        Route::get('del/{id}','dmngachluongController@destroy');
        Route::post('store','dmngachluongController@store');//insert + update
        Route::get('get','dmngachluongController@getinfo');

        Route::get('ma_so={macongtac}','dmngachluongController@detail');
        Route::get('del_detail/{id}','dmngachluongController@destroy_detail');
        Route::post('store_detail','dmngachluongController@store_detail');//insert + update
        Route::get('get_detail','dmngachluongController@getinfo_detail');
    });

    Route::group(['prefix'=>'tieu_muc'],function(){
        Route::get('index','dmtieumuc_defaultController@index');
        Route::get('store','dmtieumuc_defaultController@store');

        Route::get('del/{id}','dmtieumuc_defaultController@destroy');
        Route::get('get','dmtieumuc_defaultController@getinfo');
    });

    Route::group(['prefix'=>'thai_san'],function(){
        Route::get('danh_sach','dmphucap_thaisanController@index');
        Route::post('store','dmphucap_thaisanController@store');
        Route::get('del/{id}','dmphucap_thaisanController@destroy');
    });
});

Route::group(['prefix'=>'nghiep_vu'],function(){
    Route::group(['prefix'=>'ho_so'],function(){
        Route::get('danh_sach','hosocanboController@index');
        Route::patch('update/{id}','hosocanboController@update');
        Route::get('create','hosocanboController@create');
        Route::get('maso={id}','hosocanboController@show');
        Route::get('del/maso={id}','hosocanboController@destroy');
        Route::post('store','hosocanboController@store');

        Route::get('nhan_excel','hosocanboController@infor_excel');
        Route::post('create_excel','hosocanboController@create_excel');

        //ajax
        Route::get('get_congtac','hosocanboController@get_congtac');
        Route::get('get_chucvu_bh','hosocanboController@get_chucvu_bh');

        /*
        Route::get('phucap','hosocanboController@phucap');
        Route::get('get_phucap','hosocanboController@get_phucap');
        Route::get('del_phucap','hosocanboController@detroys_phucap');

        Route::get('syll/{id}','hosocanboController@syll');
        Route::get('ttts/{id}','hosocanboController@tomtatts');
        Route::post('bsll/{id}','hosocanboController@bsll');
        */
        Route::get('thoi_cong_tac','hosocanboController@index_thoicongtac');

        Route::get('store_kct','hosocanboController@store_kct');
        Route::get('store_dbhdnd','hosocanboController@store_dbhdnd');
        Route::get('store_qs','hosocanboController@store_qs');
        Route::get('store_cuv','hosocanboController@store_cuv');
        Route::get('store_cd','hosocanboController@store_cd');
        Route::get('store_mc','hosocanboController@store_mc');
        Route::get('store_tn','hosocanboController@store_tn');
        Route::get('getinfor_kn','hosocanboController@getinfor_kn');
        Route::get('delete_kn','hosocanboController@delete_kn');

        Route::get('temp/store_kct','hosocanboController@store_kct_temp');
        Route::get('temp/store_dbhdnd','hosocanboController@store_dbhdnd_temp');
        Route::get('temp/store_qs','hosocanboController@store_qs_temp');
        Route::get('temp/store_cuv','hosocanboController@store_cuv_temp');
        Route::get('temp/store_cd','hosocanboController@store_cd_temp');
        Route::get('temp/store_mc','hosocanboController@store_mc_temp');
        Route::get('temp/store_tn','hosocanboController@store_tn_temp');
        Route::get('temp/getinfor_kn','hosocanboController@getinfor_kn_temp');
        Route::get('temp/delete_kn','hosocanboController@delete_kn_temp_temp');
    });

    Route::group(['prefix'=>'quan_ly'],function(){


        Route::group(['prefix'=>'chi_tieu'],function(){
            Route::get('danh_sach','chitieubiencheController@index');
            Route::get('del/{id}','chitieubiencheController@destroy');
            Route::get('store','chitieubiencheController@store');
            Route::get('get','chitieubiencheController@get_detail');
        });
        Route::group(['prefix'=>'du_toan'],function(){
            Route::get('','dutoanluongController@show');
            Route::get('danh_sach','dutoanluongController@index');
            Route::get('del/{id}','dutoanluongController@destroy');
            Route::post('create','dutoanluongController@create');
            Route::get('checkNamDuToan','dutoanluongController@checkNamDT');
            Route::get('checkBangLuong','dutoanluongController@checkBangLuong');
            Route::post('senddata','dutoanluongController@senddata'); //gửi dữ liệu
            Route::post('tralai','dutoanluongController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','dutoanluongController@getlydo');//lý do trả lại dữ liệu
            Route::get('detail/del/{id}','dutoanluongController@destroy_detail');
            Route::post('detail/update','dutoanluongController@update_detail');
            Route::get('detail/get','dutoanluongController@get_detail');
        });

        Route::group(['prefix'=>'dia_ban_dbkk'],function(){
            Route::get('index','dmdiabandbkkController@index');
            Route::get('del/{id}','dmdiabandbkkController@destroy');
            Route::post('store','dmdiabandbkkController@store');//insert + update
            Route::get('get','dmdiabandbkkController@getinfo');

            Route::get('ma_so={madiaban}','dmdiabandbkkController@detail');
            Route::get('del_detail/{id}','dmdiabandbkkController@destroy_detail');
            Route::get('add_canbo','dmdiabandbkkController@store_detail');
        });
    });

    Route::group(['prefix'=>'tam_ngung'],function(){
        Route::get('danh_sach','hosotamngungtheodoiController@index');
        Route::get('del/{id}','hosotamngungtheodoiController@destroy');

        Route::get('create','hosotamngungtheodoiController@create');
        Route::post('store','hosotamngungtheodoiController@store');
        Route::get('edit','hosotamngungtheodoiController@edit');
        Route::post('update','hosotamngungtheodoiController@update');

        Route::get('get','hosotamngungtheodoiController@getinfo');
    });

    Route::group(['prefix'=>'truy_linh'],function(){
        Route::get('danh_sach','hosotruylinhController@index');
        Route::get('del/{id}','hosotruylinhController@destroy');
        Route::get('create','hosotruylinhController@create');
        Route::post('store','hosotruylinhController@store');
        Route::post('update','hosotruylinhController@update');
        Route::get('get','hosotruylinhController@getinfo');
        Route::get('get_thongtin_canbo','hosotruylinhController@get_thongtin_canbo');
    });

    Route::group(['prefix'=>'da_nghi'],function(){
        Route::get('danh_sach','hosothoicongtacController@index');
        Route::get('del/{id}','hosothoicongtacController@destroy');
        Route::post('store','hosothoicongtacController@store');
        Route::get('get','hosothoicongtacController@getinfo');
    });

    Route::group(['prefix'=>'truc'],function(){
        Route::get('danh_sach','hosotrucController@index');
        Route::get('del/{id}','hosotrucController@destroy');
        Route::post('store','hosotrucController@store');
        Route::get('get','hosotrucController@getinfo');
    });

    Route::group(['prefix'=>'dieu_dong'],function(){
        Route::get('danh_sach','hosodieudongController@index');
        Route::get('create','hosodieudongController@create');
        Route::post('store','hosodieudongController@store');
        Route::get('del/{id}','hosodieudongController@destroy');

        Route::get('/maso={macanbo}','hosoluanchuyenController@index_dd');
    });
});

Route::group(['prefix'=>'du_toan'],function(){
    Route::group(['prefix'=>'luong'],function(){

    });

    Route::group(['prefix'=>'nguon_kinh_phi'],function(){
        Route::get('danh_sach','nguonkinhphiController@index');
        Route::post('create','nguonkinhphiController@create');
        Route::get('get','nguonkinhphiController@getinfo');
        Route::get('ma_so={masodv}','nguonkinhphiController@edit');
        Route::post('update','nguonkinhphiController@update');
        Route::post('senddata','nguonkinhphiController@senddata'); //gửi dữ liệu
        Route::get('ma_so={masodv}/in','nguonkinhphiController@printf');

        Route::get('del/{id}','nguonkinhphiController@destroy');


        Route::group(['prefix'=>'khoi'],function(){
            Route::post('senddata','tonghopluong_khoiController@senddata'); //gửi dữ liệu
            Route::post('tralai','tonghopluong_khoiController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','tonghopluong_khoiController@getlydo');//lý do trả lại dữ liệu
        });
    });
});

Route::group(['prefix'=>'chuc_nang'],function(){
    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('danh_sach','bangluongController@index');
        Route::get('chi_tra','bangluongController@chitra');
        Route::post('store','bangluongController@store');
        Route::post('store_truylinh','bangluongController@store_truylinh');
        Route::post('store_truc','bangluongController@store_truc');

        Route::get('bang_luong','bangluongController@show');
        //Route::get('/maso={mabl}','bangluongController@show');
        Route::get('in/maso={mabl}','bangluongController@inbangluong');
        Route::get('inbangluong/maso={mabl}','bangluongController@inbangluong_sotien');
        Route::get('inmau3/maso={mabl}','bangluongController@inbangluongmau3'); //mẫu làm theo y.c lạng sơn
        Route::get('inmau4/maso={mabl}','bangluongController@inbangluongmau4'); //mẫu làm theo y.c lạng sơn
        Route::get('in_bh/maso={mabl}','bangluongController@inbaohiem');
        Route::get('','bangluongController@detail');
        Route::post('updatect/{id}','bangluongController@updatect');
        Route::post('updatect_truylinh','bangluongController@updatect_truylinh');
        Route::get('del/{id}','bangluongController@destroy');
        Route::get('del_ct/{id}','bangluongController@destroy_ct');
        //Route::get('cal','bangluongController@cal'); //Tính toán lại lương cán bộ
        Route::post('importexcel','bangluongController@importexcel');

        //Tạo in bảng lương theo cách mới
        Route::post('mau01','bangluongController@printf_mau01');
        Route::post('mautt107','bangluongController@printf_mautt107');
        Route::post('mautt107_pb','bangluongController@printf_mautt107_pb');
        Route::post('mau03','bangluongController@printf_mau03');
        Route::post('mau04','bangluongController@printf_mau04');
        Route::post('mau05','bangluongController@printf_mau05');
        Route::post('mau06','bangluongController@printf_mau06');
        Route::post('mau07','bangluongController@printf_mau07');
        Route::post('mau08','bangluongController@printf_mau08');
        Route::post('mauds','bangluongController@printf_mauds');
        Route::post('maubh','bangluongController@printf_maubh');
        Route::post('maudbhdnd','bangluongController@printf_maudbhdnd');
        Route::post('maublpc','bangluongController@printf_maublpc');
        Route::post('maubchd','bangluongController@printf_maubchd');
        Route::post('mauqs','bangluongController@printf_mauqs');
        Route::get('maucd','bangluongController@printf_maucd');
        Route::get('maumc','bangluongController@printf_maumc');
        Route::get('mautruc','bangluongController@printf_mautruc');
        Route::get('mautinhnguyen','bangluongController@printf_mautinhnguyen');

        Route::post('mau01_excel','bangluongController@printf_mau01_excel');
        Route::post('mautt107_excel','bangluongController@printf_mautt107_excel');
        Route::post('mau03_excel','bangluongController@printf_mau03_excel');
        Route::post('mau04_excel','bangluongController@printf_mau04_excel');
        Route::post('mau05_excel','bangluongController@printf_mau05_excel');
        Route::post('mau07_excel','bangluongController@printf_mau07_excel');
        Route::post('mau08_excel','bangluongController@printf_mau08_excel');
        Route::post('mau06_excel','bangluongController@printf_mau06_excel');
        Route::post('mauds_excel','bangluongController@printf_mauds_excel');
        Route::post('maubh_excel','bangluongController@printf_maubh_excel');
        Route::post('maudbhdnd_excel','bangluongController@printf_maudbhdnd_excel');
        Route::post('maublpc_excel','bangluongController@printf_maublpc_excel');
        Route::post('maubchd_excel','bangluongController@printf_maubchd_excel');
        Route::post('mauqs_excel','bangluongController@printf_mauqs_excel');
    });

    Route::group(['prefix'=>'nang_luong'],function(){
        Route::get('danh_sach','dsnangluongController@index');
        Route::post('store','dsnangluongController@store'); //insert + update danh sách nâng lương

        Route::get('update/{id}','dsnangluongController@update');
        Route::get('create','dsnangluongController@create');

        Route::get('/maso={manl}','dsnangluongController@show');
        Route::get('chi_tiet','dsnangluongController@detail');
        Route::post('store_detail','dsnangluongController@store_detail'); //insert + update danh sách nâng lương
        Route::get('del/{id}','dsnangluongController@destroy');
        Route::get('nang_luong/maso={manl}','dsnangluongController@nang_luong');
        Route::get('deldt/{id}','dsnangluongController@destroydt');
    });

    Route::group(['prefix'=>'tham_nien'],function(){
        Route::get('danh_sach','dsnangthamnienController@index');
        Route::post('store','dsnangthamnienController@store'); //insert + update danh sách nâng lương
        Route::get('get','dsnangthamnienController@getinfo');
        Route::get('update/{id}','dsnangthamnienController@update');
        Route::get('create','dsnangthamnienController@create');

        Route::get('/maso={manl}','dsnangthamnienController@show');
        Route::get('chi_tiet','dsnangthamnienController@detail');
        Route::post('store_detail','dsnangthamnienController@store_detail'); //insert + update danh sách nâng lương
        Route::get('del/{id}','dsnangthamnienController@destroy');
        Route::get('nang_luong/maso={manl}','dsnangthamnienController@nang_luong');
        Route::get('deldt/{id}','dsnangthamnienController@destroydt');
    });

    Route::group(['prefix'=>'tong_hop_luong'],function(){
        Route::group(['prefix'=>'don_vi'],function(){
            Route::get('index','tonghopluong_donviController@index');
            Route::get('tonghop','tonghopluong_donviController@tonghop');
            Route::get('detail/ma_so={mathdv}','tonghopluong_donviController@detail');
            Route::get('detail_diaban/ma_so={mathdv}','tonghopluong_donviController@detail_diaban');

            Route::get('edit_detail','tonghopluong_donviController@edit_detail');//chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban','tonghopluong_donviController@edit_detail_diaban');//chỉnh sửa dữ liêu

            Route::post('store_detail','tonghopluong_donviController@store_detail');//chỉnh sửa dữ liêu
            Route::post('store_detail_diaban','tonghopluong_donviController@store_detail_diaban');//chỉnh sửa dữ liêu

            Route::post('senddata','tonghopluong_donviController@senddata'); //gửi dữ liệu
            Route::post('tralai','tonghopluong_donviController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','tonghopluong_donviController@getlydo');//lý do trả lại dữ liệu

            Route::get('del/maso={mathdv}','tonghopluong_donviController@destroy');//lý do trả lại dữ liệu
            Route::get('del_detail/{id}','tonghopluong_donviController@destroy_detail');//lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}','tonghopluong_donviController@printf_data');
            Route::get('printf_bl/ma_so={mathdv}','tonghopluong_donviController@printf_bl');
            Route::get('printf_data_diaban/ma_so={mathdv}','tonghopluong_donviController@printf_data_diaban');
        });

        Route::group(['prefix'=>'khoi'],function(){
            Route::get('index','tonghopluong_khoiController@index');
            Route::get('tonghop','tonghopluong_khoiController@tonghop');
            //Route::get('tonghop','tonghopluong_khoiController@tonghop');
            Route::get('tonghop_diaban','tonghopluong_khoiController@tonghop_diaban');

            Route::post('tonghop_chuadaydu','tonghopluong_huyenController@tonghop_chuadaydu');
            //Route::post('tonghop_chuadaydu','tonghopluong_khoiController@tonghop_chuadaydu');
            Route::get('detail/ma_so={mathdv}','tonghopluong_khoiController@detail');
            Route::post('senddata','tonghopluong_khoiController@senddata'); //gửi dữ liệu
            Route::post('tralai','tonghopluong_khoiController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','tonghopluong_khoiController@getlydo');//lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}','tonghopluong_khoiController@printf_data');
            Route::get('printf_data_diaban/ma_so={mathdv}','tonghopluong_khoiController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}','tonghopluong_khoiController@detail_diaban');
            Route::get('edit_detail','tonghopluong_khoiController@edit_detail');//chỉnh sửa dữ liêu

            Route::get('edit_detail_diaban','tonghopluong_khoiController@edit_detail_diaban');//chỉnh sửa dữ liêu
            Route::post('store_detail','tonghopluong_khoiController@store_detail');//chỉnh sửa dữ liêu
            Route::post('store_detail_diaban','tonghopluong_khoiController@store_detail_diaban');//chỉnh sửa dữ liêu
        });

        Route::group(['prefix'=>'huyen'],function(){
            Route::get('index','tonghopluong_huyenController@index');
            Route::get('tonghop','tonghopluong_huyenController@tonghop');
            Route::get('tonghop_diaban','tonghopluong_huyenController@tonghop_diaban');
            Route::post('tonghop_chuadaydu','tonghopluong_huyenController@tonghop_chuadaydu');
            //chưa làm
            Route::post('senddata','tonghopluong_huyenController@senddata'); //gửi dữ liệu
            Route::post('tralai','tonghopluong_huyenController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','tonghopluong_huyenController@getlydo');//lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}','tonghopluong_huyenController@printf_data');
            Route::get('printf_data_diaban/ma_so={mathdv}','tonghopluong_huyenController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}','tonghopluong_huyenController@detail_diaban');
            Route::get('edit_detail','tonghopluong_huyenController@edit_detail');//chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban','tonghopluong_huyenController@edit_detail_diaban');//chỉnh sửa dữ liêu
            Route::post('store_detail','tonghopluong_huyenController@store_detail');//chỉnh sửa dữ liêu
            Route::post('store_detail_diaban','tonghopluong_huyenController@store_detail_diaban');//chỉnh sửa dữ liêu
        });

        Route::group(['prefix'=>'tinh'],function(){
            Route::get('index','tonghopluong_tinhController@index');

            //chưa làm
            Route::get('tonghop','tonghopluong_huyenController@tonghop');
            Route::get('tonghop_diaban','tonghopluong_huyenController@tonghop_diaban');
            Route::post('tonghop_chuadaydu','tonghopluong_huyenController@tonghop_chuadaydu');
            //chưa làm
            Route::post('senddata','tonghopluong_huyenController@senddata'); //gửi dữ liệu
            Route::post('tralai','tonghopluong_huyenController@tralai'); //trả lại dữ liệu
            Route::get('getlydo','tonghopluong_huyenController@getlydo');//lý do trả lại dữ liệu

            Route::get('printf_data/ma_so={mathdv}','tonghopluong_huyenController@printf_data');
            Route::get('printf_data_diaban/ma_so={mathdv}','tonghopluong_huyenController@printf_data_diaban');

            Route::get('detail_diaban/ma_so={mathdv}','tonghopluong_huyenController@detail_diaban');
            Route::get('edit_detail','tonghopluong_huyenController@edit_detail');//chỉnh sửa dữ liêu
            Route::get('edit_detail_diaban','tonghopluong_huyenController@edit_detail_diaban');//chỉnh sửa dữ liêu
            Route::post('store_detail','tonghopluong_huyenController@store_detail');//chỉnh sửa dữ liêu
            Route::post('store_detail_diaban','tonghopluong_huyenController@store_detail_diaban');//chỉnh sửa dữ liêu
        });
    });

    Route::group(['prefix'=>'xem_du_lieu'],function(){
        Route::get('index','xemdulieucapduoiController@donvi_luong');
        Route::get('huyen','xemdulieucapduoiController@index_huyen');
        Route::get('tinh','xemdulieucapduoiController@index_tinh');
        Route::get('tinh/solieu','xemdulieucapduoiController@tonghop_huyen');

        Route::group(['prefix'=>'nguon'],function(){
            Route::get('khoi','xemdulieu_nguonController@index_khoi');
            Route::get('huyen','xemdulieu_nguonController@index_huyen');
            Route::get('tinh','xemdulieu_nguonController@index_tinh');
        });

        Route::group(['prefix'=>'du_toan'],function(){
            Route::get('khoi','xemdulieu_dutoanController@index_khoi');
            Route::get('huyen','xemdulieu_dutoanController@index_huyen');
            Route::get('tinh','xemdulieu_dutoanController@index_tinh');
        });

    });

    Route::group(['prefix'=>'tong_hop_nguon'],function(){
        Route::get('index','tonghopnguonController@index');
        Route::post('tralai','tonghopnguonController@tralai');
        Route::get('ma_so={sohieu}/in_khoi','tonghopnguonController@printf_khoi');

        //Route::get('huyen','tonghopnguonController@index_huyen');
        Route::post('tralai_huyen','tonghopnguonController@tralai_huyen');
        Route::get('ma_so={sohieu}/in_huyen','tonghopnguonController@printf_huyen');

        Route::get('tinh','tonghopnguonController@index_tinh');
        Route::post('tralai_tinh','tonghopnguonController@tralai_tinh');
        Route::get('/in_tinh','tonghopnguonController@printf_tinh');

        Route::group(['prefix'=>'huyen'],function(){
            Route::get('index','tonghopnguon_huyenController@index');
            Route::post('tralai','tonghopnguon_huyenController@tralai');
            Route::get('ma_so={sohieu}/in','tonghopnguon_huyenController@printf');
            Route::get('tonghop','tonghopnguon_khoiController@tonghop');//tạm
            Route::post('senddata','tonghopnguon_huyenController@senddata'); //gửi dữ liệu lên tỉnh
        });

        Route::group(['prefix'=>'khoi'],function(){
            Route::get('index','tonghopnguon_khoiController@index');
            Route::post('tralai','tonghopnguon_khoiController@tralai');//trả về đơn vị
            Route::get('ma_so={sohieu}/in','tonghopnguon_khoiController@printf');

            Route::post('senddata','tonghopnguon_khoiController@senddata'); //gửi dữ liệu lên huyện

            Route::get('tonghop','tonghopnguon_khoiController@tonghop');//tạm
        });

    });

    Route::group(['prefix'=>'du_toan_luong'],function(){

        Route::group(['prefix'=>'huyen'],function(){
            Route::get('index','dutoanluong_huyenController@index');
            Route::post('tralai','dutoanluong_huyenController@tralai');
            Route::post('senddata','dutoanluong_huyenController@senddata'); //gửi dữ liệu lên tỉnh

            Route::get('tonghop','dutoanluong_huyenController@tonghop');
            Route::get('printf','dutoanluong_huyenController@printf');//in một khối trong khối
        });

        Route::group(['prefix'=>'khoi'],function(){
            Route::get('index','dutoanluong_khoiController@index');
            Route::post('tralai','dutoanluong_khoiController@tralai');//trả về đơn vị
            Route::post('senddata','dutoanluong_khoiController@senddata'); //gửi dữ liệu lên huyện

            Route::get('tonghop','dutoanluong_khoiController@tonghop');
            Route::get('printf','dutoanluong_khoiController@printf');//in một đơn vị trong khối
        });

    });

    Route::group(['prefix'=>'thuyen_chuyen'],function(){
        Route::get('danh_sach','dshettapsuController@index');
        Route::get('update/{id}','dshettapsuController@update');
        Route::get('create','dshettapsuController@create');
        Route::get('/maso={mahts}','dshettapsuController@show');
        Route::get('del/{id}','dshettapsuController@destroy');
    });

    Route::group(['prefix'=>'dang_ky_luong'],function(){
        Route::get('danh_sach','bangluongdangkyController@dangky');
        Route::post('store','bangluongdangkyController@store');
        Route::get('del/{id}','bangluongdangkyController@destroy');
        Route::get('/maso={mabl}','bangluongdangkyController@show');
        Route::get('get','bangluongdangkyController@getinfo');
        Route::get('','bangluongdangkyController@detail');
        Route::post('updatect/{id}','bangluongdangkyController@updatect');

        //Tạo in bảng lương theo cách mới
        Route::post('mau01','bangluongdangkyController@printf_mau01');
        Route::post('mautt107','bangluongdangkyController@printf_mautt107');

    });
});

Route::group(['prefix'=>'bao_cao'],function(){
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

    Route::group(['prefix'=>'thong_tu_67'],function(){
        Route::group(['prefix'=>'don_vi'],function(){
            Route::get('mau2a1','baocaothongtu67Controller@mau2a1_donvi');
            Route::get('mau2a2','baocaothongtu67Controller@mau2a2_donvi');
            Route::get('mau2b','baocaothongtu67Controller@mau2b_donvi');
            Route::get('mau2c','baocaothongtu67Controller@mau2c_donvi');
            Route::get('mau2d','baocaothongtu67Controller@mau2d_donvi');
            Route::get('mau4a','baocaothongtu67Controller@mau4a_donvi');
            Route::get('mau4b','baocaothongtu67Controller@mau4b_donvi');

            Route::get('mau2e','baocaothongtu67Controller@mau2e_donvi');
            Route::get('mau2g','baocaothongtu67Controller@mau2g_donvi');
            Route::get('mau2h','baocaothongtu67Controller@mau2h_donvi');
        });

        Route::group(['prefix'=>'khoi'],function(){
            Route::get('mau2a1','baocaothongtu67Controller@mau2a1_khoi');
            Route::get('mau2a2','baocaothongtu67Controller@mau2a2_khoi');
            Route::get('mau2b','baocaothongtu67Controller@mau2b_donvi');
            Route::get('mau2c','baocaothongtu67Controller@mau2c_khoi');
            Route::get('mau2d','baocaothongtu67Controller@mau2d_donvi');
            Route::get('mau4a','baocaothongtu67Controller@mau4a_khoi');
            Route::get('mau4b','baocaothongtu67Controller@mau4b_khoi');
        });

        Route::group(['prefix'=>'huyen'],function(){
            Route::get('mau2a1','baocaothongtu67Controller@mau2a1_huyen');
            Route::get('mau2a2','baocaothongtu67Controller@mau2a2_huyen');
            Route::get('mau2b','baocaothongtu67Controller@mau2b_donvi');
            Route::get('mau2c','baocaothongtu67Controller@mau2c_huyen');
            Route::get('mau2d','baocaothongtu67Controller@mau2d_donvi');
            Route::get('mau4a','baocaothongtu67Controller@mau4a_huyen');
            Route::get('mau4b','baocaothongtu67Controller@mau4b_huyen');
        });
    });

    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('tong_hop','baocaobangluongController@index_th');//tạm
        Route::get('','baocaobangluongController@index');//Form chung
        //Các mẫu báo cáo tại đơn vị
        Route::group(['prefix'=>'don_vi'],function(){
            Route::post('mauc02ahd','baocaobangluongController@mauc02ahd');
            Route::post('mauc02ahd_mau2','baocaobangluongController@mauc02ahd_mau2');
            Route::post('mauc02ahd_mau3','baocaobangluongController@mauc02ahd_mau3');
            Route::post('mauc02x','baocaobangluongController@mauc02x');
            Route::post('maubaohiem','baocaobangluongController@maubaohiem');
            Route::post('chitraluong','baocaobangluongController@chitraluong');
            Route::post('dutoanluong','baocaobangluongController@dutoanluong');
        });

        Route::group(['prefix'=>'khoi'],function(){
            Route::post('chitraluong_ct','baocaobangluongController@chitraluong_ct_khoim');
            //Route::post('chitraluong_ct','baocaobangluongController@chitraluong_ct_khoi');
            //Route::post('chitraluong_th','baocaobangluongController@chitraluong_th_khoi');
            Route::post('chitraluong_th','baocaobangluongController@chitraluong_th_khoim');
            Route::post('dutoanluong','baocaobangluongController@dutoanluong_khoi');
            Route::post('baocaohesoluong','baocaobangluongController@baocaohesoluong_khoi');
        });

        Route::group(['prefix'=>'huyen'],function(){
            Route::post('chitraluong_ct','baocaobangluongController@chitraluong_ct_huyen');
            Route::post('chitraluong_th','baocaobangluongController@chitraluong_th_huyen');
            Route::post('dutoanluong','baocaobangluongController@dutoanluong_huyen');
            Route::post('baocaohesoluong','baocaobangluongController@baocaohesoluong');

        });

        Route::get('dutoanluong_th','baocaobangluongController@dutoanluong_th');
        Route::post('chitraluong_th','baocaobangluongController@chitraluong_th');
        Route::post('mauc02ahd_th','baocaobangluongController@mauc02ahd_th');
        Route::post('mauc02x_th','baocaobangluongController@mauc02ahd_th');//chưa xây dựng
        Route::post('maubaohiem_th','baocaobangluongController@maubaohiem');//chưa xây dựng
    });
});

Route::group(['prefix'=>'tong_hop_bao_cao'],function(){
    Route::get('danh_sach','baocaothongtu67Controller@index');

    Route::post('mau2a1_tt67','baocaothongtu67Controller@mau2a1_tt67');
    Route::post('mau2a2_tt67','baocaothongtu67Controller@mau2a2_tt67');
    Route::post('mau2b_tt67','baocaothongtu67Controller@mau2b_tt67');

    Route::post('mau2c_tt67','baocaothongtu67Controller@mau2c_tt67');
    Route::post('mau2d_tt67','baocaothongtu67Controller@mau2d_tt67');
    Route::post('mau2e_tt67','baocaothongtu67Controller@mau2e_tt67');
    Route::post('mau2g_tt67','baocaothongtu67Controller@mau2g_tt67');
    Route::post('mau2h_tt67','baocaothongtu67Controller@mau2h_tt67');
    Route::post('mau4a_tt67','baocaothongtu67Controller@mau4a_tt67');
    Route::post('mau4b_tt67','baocaothongtu67Controller@mau4b_tt67');

});

Route::group(['prefix'=>'tra_cuu'],function(){
    Route::group(['prefix'=>'ho_so'],function(){
        Route::get('','hosocanboController@search');
        Route::post('ket_qua','hosocanboController@result');
    });
    /*
    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('','hosocongtacController@search');
        Route::post('ket_qua','hosocongtacController@result');
    });
    Route::group(['prefix'=>'dao_tao'],function(){
        Route::get('','hosodaotaoController@search');
        Route::post('ket_qua','hosodaotaoController@result');
    });
    */
    Route::group(['prefix'=>'luong'],function(){
        Route::get('','hosoluongController@search');
        Route::post('ket_qua','hosoluongController@result');
    });

    Route::group(['prefix'=>'chi_luong'],function(){
        Route::get('','bangluongController@search');
        Route::post('ket_qua','bangluongController@result');
    });
    /*
    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('','hosophucapController@search');
        Route::post('ket_qua','hosophucapController@result');
    });
    Route::group(['prefix'=>'gia_dinh'],function(){
        Route::get('','hosoquanhegdController@search');
        Route::post('ket_qua','hosoquanhegdController@result');
    });
    */
});

Route::group(['prefix'=>'ajax'],function(){
    //Route::get('kieuct','ajaxController@getKieuCT');
    //Route::get('tenct','ajaxController@getTenCT');
    //Route::get('tennb','ajaxController@getTenNB');
    Route::get('bac','ajaxController@getBac');
    Route::get('heso','ajaxController@getHS');
    //Route::get('msnb','ajaxController@getMSNB');
    Route::get('checkmadv','ajaxController@checkmadv');
    //Route::get('phucap','ajaxController@getPhuCap');
    /*
    Route::group(['prefix'=>'tai_lieu'],function(){
        Route::get('add','hosotailieuController@store');
        Route::get('update','hosotailieuController@update');
        Route::get('get','hosotailieuController@get_detail');
    });
    Route::group(['prefix'=>'quan_he_gd'],function(){
        Route::get('add_bt','hosoquanhegdController@store_bt');
        Route::get('update_bt','hosoquanhegdController@update_bt');
        Route::get('add_vc','hosoquanhegdController@store_vc');
        Route::get('update_vc','hosoquanhegdController@update_vc');
        Route::get('get','hosoquanhegdController@get_detail');
    });

    Route::group(['prefix'=>'chuc_vu'],function(){
        Route::get('add','hosochucvuController@store');
        Route::get('update','hosochucvuController@update');
        Route::get('get','hosochucvuController@get_detail');
    });
    Route::group(['prefix'=>'bao_hiem'],function(){
        Route::get('add','hosobaohiemyteController@store');
        Route::get('update','hosobaohiemyteController@update');
        Route::get('get','hosobaohiemyteController@get_detail');
    });
    Route::group(['prefix'=>'luc_luong_vu_trang'],function(){
        Route::get('add','hosollvtController@store');
        Route::get('update','hosollvtController@update');
        Route::get('get','hosollvtController@get_detail');
    });
    Route::group(['prefix'=>'chi_tieu'],function(){
        Route::get('add','chitieubiencheController@store');
        Route::get('update','chitieubiencheController@update');
        Route::get('get','chitieubiencheController@get_detail');
        Route::get('getNamChiTieu','chitieubiencheController@getNamChiTieu');
    });
    Route::group(['prefix'=>'dao_tao'],function(){
        Route::get('add','hosodaotaoController@store');
        Route::get('update','hosodaotaoController@update');
        Route::get('get','hosodaotaoController@getinfo');
    });
    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('add','hosocongtacController@store');
        Route::get('update','hosocongtacController@update');
        Route::get('get','hosocongtacController@getinfo');
    });
    Route::group(['prefix'=>'cong_tac_nuoc_ngoai'],function(){
        Route::get('add','hosocongtacnnController@store');
        Route::get('update','hosocongtacnnController@update');
        Route::get('get','hosocongtacnnController@getinfo');
    });
    Route::group(['prefix'=>'luong'],function(){
        Route::get('add','hosoluongController@store');
        Route::get('update','hosoluongController@update');
        Route::get('get','hosoluongController@getinfo');
    });
    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('add','hosophucapController@store');
        Route::get('update','hosophucapController@update');
        Route::get('get','hosophucapController@getinfo');
    });
    Route::group(['prefix'=>'binh_bau'],function(){
        Route::get('add','hosobinhbauController@store');
        Route::get('update','hosobinhbauController@update');
        Route::get('get','hosobinhbauController@getinfo');
    });
    Route::group(['prefix'=>'khen_thuong'],function(){
        Route::get('add','hosokhenthuongController@store');
        Route::get('update','hosokhenthuongController@update');
        Route::get('get','hosokhenthuongController@getinfo');
    });
    Route::group(['prefix'=>'ky_luat'],function(){
        Route::get('add','hosokyluatController@store');
        Route::get('update','hosokyluatController@update');
        Route::get('get','hosokyluatController@getinfo');
    });
    Route::group(['prefix'=>'thanh_tra'],function(){
        Route::get('add','hosothanhtraController@store');
        Route::get('update','hosothanhtraController@update');
        Route::get('get','hosothanhtraController@getinfo');
    });
    Route::group(['prefix'=>'nhan_xet'],function(){
        Route::get('add','hosonhanxetdgController@store');
        Route::get('update','hosonhanxetdgController@update');
        Route::get('get','hosonhanxetdgController@getinfo');
    });


    Route::group(['prefix'=>'het_tap_su'],function(){
        Route::get('add','dshettapsuController@store');
        Route::get('update','dshettapsuController@update');
        Route::get('get','dshettapsuController@getinfo');
    });
    Route::group(['prefix'=>'huu_tri'],function(){
        Route::get('add','dshuutriController@store');
        Route::get('update','dshuutriController@update');
        Route::get('get','dshuutriController@getinfo');
    });
    */
    Route::group(['prefix'=>'nang_luong'],function(){
        Route::get('add','dsnangluongController@store');
        Route::get('update','dsnangluongController@update');
        Route::get('get','dsnangluongController@getinfo');
    });

    Route::group(['prefix'=>'dieu_dong'],function(){
        Route::get('add','hosoluanchuyenController@store_dd');
        Route::get('update','hosoluanchuyenController@update_dd');
        Route::get('get','hosoluanchuyenController@get_detail');
    });

    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('add','bangluongController@store');
        Route::get('update','bangluongController@update');
        Route::get('get','bangluongController@getinfo');
        Route::get('get_nguonkp','bangluongController@getinfor_nguonkp');
    });

    Route::group(['prefix'=>'khoi_pb'],function(){
        Route::get('add','dmkhoipbController@store');
        Route::get('update','dmkhoipbController@update');
        Route::get('get','dmkhoipbController@getinfo');
    });

    Route::group(['prefix'=>'du_toan'],function(){
        Route::get('add','dutoanluongController@store');
        Route::get('update','dutoanluongController@update');
        Route::get('get','dutoanluongController@get_detail');
    });
});
/*
Route::group(['prefix'=>'luu_du_lieu'],function(){
    Route::get('can_bo','dshuutriController@store');
    Route::get('bang_luong','dshuutriController@update');
    Route::get('het_tap_su','dshuutriController@getinfo');
});
*/
Route::group(['prefix'=>'he_thong'],function(){
    Route::group(['prefix'=>'don_vi'],function(){
        Route::get('don_vi','dmdonviController@information_local');
        Route::get('thong_tin','dmdonviController@edit_local');
        Route::post('/{madv}','dmdonviController@update_local');
        Route::get('chung','dmdonviController@information_global');
        Route::get('maso={id}/edit_global','dmdonviController@edit_global');
        Route::patch('/{id}/global','dmdonviController@update_global');
        Route::get('capnhat_bh','dmphanloaicongtac_baohiemController@capnhat');
        Route::get('bao_hiem','dmphanloaicongtac_baohiemController@index');
        Route::get('update_bh','dmphanloaicongtac_baohiemController@update');
        Route::get('get_bh','dmphanloaicongtac_baohiemController@getinfo');
    });

    Route::group(['prefix'=>'quan_tri'],function(){
        Route::get('don_vi','dmdonviController@information_manage');
        Route::get('don_vi/create','dmdonviController@create_manage');
        Route::post('store','dmdonviController@store_manage');

        Route::get('don_vi/maso={madv}','dmdonviController@list_account');
        Route::get('don_vi/maso={madv}/create','dmdonviController@create_account');
        Route::post('don_vi/maso={madv}/store','dmdonviController@store_account');
        Route::get('don_vi/maso={id}/edit','dmdonviController@edit_account');
        Route::patch('don_vi/maso={id}/update','dmdonviController@update_account');
        Route::post('destroy_account','dmdonviController@destroy_account');

        //Route::get('don_vi/maso={id}/phan_quyen','dmdonviController@permission_list');
        //Route::post('don_vi/maso={id}/up_perm','dmdonviController@permission_update');

        Route::get('don_vi/maso={madv}/don_vi','dmdonviController@edit_information');
        Route::patch('don_vi/maso={madv}','dmdonviController@update_information');
    });

    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('index','dmphucapController@index');
        Route::get('del/{id}','dmphucapController@destroy');
        Route::get('get','dmphucapController@getinfo');

        Route::get('create','dmphucapController@create');
        Route::post('update','dmphucapController@update');
        Route::get('edit','dmphucapController@edit');
        Route::post('store','dmphucapController@store');
    });

    Route::group(['prefix'=>'chuc_vu'],function(){
        Route::get('index','dmchucvucqController@index');
        Route::get('del/{id}','dmchucvucqController@destroy');
        Route::get('add','dmchucvucqController@store');
        Route::get('update','dmchucvucqController@update');
        Route::get('get','dmchucvucqController@getinfo');
    });

    Route::group(['prefix'=>'dinh_muc'],function(){
        Route::get('danh_sach','nguonkinhphi_dinhmucController@index');
        Route::get('get','nguonkinhphi_dinhmucController@getinfo');
        Route::get('update','nguonkinhphi_dinhmucController@update');
        Route::get('get_ct','nguonkinhphi_dinhmucController@getinfor_ct');
        Route::get('update_ct','nguonkinhphi_dinhmucController@update_ct');

        Route::get('phu_cap','nguonkinhphi_dinhmucController@phucap');
        Route::post('store_pc','nguonkinhphi_dinhmucController@store_pc');
        Route::get('del/{id}','nguonkinhphi_dinhmucController@destroy_pc');

    });
});



