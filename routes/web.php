<?php
Route::get('', 'HomeController@index');
Route::get('ajaxtest','ajaxController@test');
Route::get('test', function(){
    return view('test');
});

Route::get('/setting','HomeController@setting');
Route::post('/setting','HomeController@upsetting');

Route::get('register/dich_vu_luu_tru','HomeController@regdvlt');
Route::get('checkrgmasothue','HomeController@checkrgmasothue');
Route::get('checkrguser','HomeController@checkrguser');
Route::get('mauexcel','bangluongController@getDownload');


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

    Route::group(['prefix'=>'chuc_vu_cq'],function(){
        Route::get('index','dmchucvucqController@index');
        Route::get('del/{id}','dmchucvucqController@destroy');
        Route::get('add','dmchucvucqController@store');
        Route::get('update','dmchucvucqController@update');
        Route::get('get','dmchucvucqController@getinfo');
    });

    Route::group(['prefix'=>'chuc_vu_d'],function(){
        Route::get('index','dmchucvudController@index');
        Route::get('store','dmchucvudController@store');
        Route::get('del/{id}','dmchucvudController@destroy');
    });

    Route::group(['prefix'=>'dan_toc'],function(){
        Route::get('index','dmdantocController@index');
        Route::get('store','dmdantocController@store');
        Route::get('del/{id}','dmdantocController@destroy');
    });

    Route::group(['prefix'=>'quan_he_gd'],function(){
        Route::get('index','dmquanhegdController@index');
        Route::get('store','dmquanhegdController@store');
        Route::get('del/{id}','dmquanhegdController@destroy');
    });

    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('index','dmphucapController@index');
        Route::get('store','dmphucapController@store');
        Route::get('del/{id}','dmphucapController@destroy');
    });

    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('index','dmphanloaictController@index');
        Route::get('store','dmphanloaictController@store');
        Route::get('del/{id}','dmphanloaictController@destroy');
    });

    Route::group(['prefix'=>'don_vi'],function(){
        Route::get('maso={maso}','dmdonviController@index');
        Route::get('store','dmdonviController@store');
        Route::get('del/{id}','dmdonviController@destroy');
        Route::get('change/maso={madv}','dmdonviController@change');
    });

    Route::group(['prefix'=>'khoi_pb'],function(){
        Route::get('ma_so={level}','dmkhoipbController@index');
        Route::get('store','dmkhoipbController@store');
        Route::get('del/{id}','dmkhoipbController@destroy');
    });

    Route::group(['prefix'=>'bao_mat'],function(){
        Route::get('index','dmbaomatController@index');
        Route::get('get','dmbaomatController@getinfo');
        Route::get('add','dmbaomatController@store');
        Route::get('update','dmbaomatController@update');
        Route::get('del/{id}','dmbaomatController@destroy');
    });

    Route::group(['prefix'=>'khu_vuc'],function(){
        Route::get('ma_so={level}','dmdonvibaocaoController@index');
        Route::get('get','dmdonvibaocaoController@getinfo');
        Route::get('add','dmdonvibaocaoController@store');
        Route::get('update','dmdonvibaocaoController@update');
        Route::get('del/{maso}','dmdonvibaocaoController@destroy');

        Route::get('ma_so={makhuvuc}/list_unit','dmdonvibaocaoController@list_donvi');
        Route::get('ma_so={makhuvuc}&don_vi={madonvi}/edit','dmdonvibaocaoController@show_donvi');
        Route::get('ma_so={makhuvuc}/create','dmdonvibaocaoController@create_donvi');

        Route::patch('update_donvi','dmdonvibaocaoController@update_donvi');
        Route::post('store_donvi','dmdonvibaocaoController@store_donvi');
        Route::get('del_donvi/{madv}','dmdonvibaocaoController@destroy_donvi');
        Route::get('get_list_unit','dmdonvibaocaoController@get_list_unit');
        Route::get('set_management','dmdonvibaocaoController@set_management');
    });

    Route::group(['prefix'=>'tai_khoan'],function(){
        Route::get('list_user','UsersController@list_users');
        Route::get('ma_so={madv}/create','UsersController@create');
        Route::post('add_user','UsersController@store');

        Route::get('ma_so={taikhoan}/permission','UsersController@permission');
        Route::post('ma_so={taikhoan}/uppermission','UsersController@uppermission');
        Route::get('del_taikhoan/{madv}','UsersController@destroy');
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

        Route::get('phucap','hosocanboController@phucap');
        Route::get('get_phucap','hosocanboController@get_phucap');
        Route::get('del_phucap','hosocanboController@detroys_phucap');

        Route::get('syll/{id}','hosocanboController@syll');
        Route::get('ttts/{id}','hosocanboController@tomtatts');
        Route::post('bsll/{id}','hosocanboController@bsll');

        Route::get('thoi_cong_tac','hosocanboController@index_thoicongtac');
    });

    Route::group(['prefix'=>'quan_ly'],function(){
        Route::group(['prefix'=>'tai_lieu'],function(){
            Route::get('/maso={macanbo}','hosotailieuController@index');
            Route::get('del/{id}','hosotailieuController@destroy');
        });
        Route::group(['prefix'=>'quan_he_bt'],function(){
            Route::get('/maso={macanbo}','hosoquanhegdController@index_bt');
            Route::get('del/{id}','hosoquanhegdController@destroy_bt');
        });
        Route::group(['prefix'=>'quan_he_vc'],function(){
            Route::get('/maso={macanbo}','hosoquanhegdController@index_vc');
            Route::get('del/{id}','hosoquanhegdController@destroy_vc');
        });
        Route::group(['prefix'=>'dieu_dong'],function(){
            Route::get('/maso={macanbo}','hosoluanchuyenController@index_dd');
            Route::get('del/{id}','hosoluanchuyenController@destroy_dd');
        });
        Route::group(['prefix'=>'chuc_vu'],function(){
            Route::get('/maso={macanbo}','hosochucvuController@index');
            Route::get('del/{id}','hosochucvuController@destroy');
        });
        Route::group(['prefix'=>'bhyt'],function(){
            Route::get('/maso={macanbo}','hosobaohiemyteController@index');
            Route::get('del/{id}','hosobaohiemyteController@destroy');
        });
        Route::group(['prefix'=>'llvt'],function(){
            Route::get('/maso={macanbo}','hosollvtController@index');
            Route::get('del/{id}','hosollvtController@destroy');
        });
        Route::group(['prefix'=>'chi_tieu'],function(){
            Route::get('danh_sach','chitieubiencheController@index');
            Route::get('del/{id}','hosollvtController@destroy');
        });
        Route::group(['prefix'=>'du_toan'],function(){
            Route::get('danh_sach','dutoanluongController@index');
            Route::get('del/{id}','dutoanluongController@destroy');
        });
    });

    Route::group(['prefix'=>'qua_trinh'],function(){
        Route::group(['prefix'=>'dao_tao'],function(){
            Route::get('/maso={macanbo}','hosodaotaoController@index');
            Route::get('del/{id}','hosodaotaoController@destroy');
        });
        Route::group(['prefix'=>'cong_tac'],function(){
            Route::get('/maso={macanbo}','hosocongtacController@index');
            Route::get('del/{id}','hosocongtacController@destroy');
        });
        Route::group(['prefix'=>'cong_tac_nn'],function(){
            Route::get('/maso={macanbo}','hosocongtacnnController@index');
            Route::get('del/{id}','hosocongtacnnController@destroy');
        });
        Route::group(['prefix'=>'luong'],function(){
            Route::get('/maso={macanbo}','hosoluongController@index');
            Route::get('del/{id}','hosoluongController@destroy');
        });
        Route::group(['prefix'=>'phu_cap'],function(){
            Route::get('/maso={macanbo}','hosophucapController@index');
            Route::get('del/{id}','hosophucapController@destroy');
        });
    });

    Route::group(['prefix'=>'danh_gia'],function(){
        Route::group(['prefix'=>'binh_bau'],function(){
            Route::get('/maso={macanbo}','hosobinhbauController@index');
            Route::get('del/{id}','hosobinhbauController@destroy');
        });
        Route::group(['prefix'=>'khen_thuong'],function(){
            Route::get('/maso={macanbo}','hosokhenthuongController@index');
            Route::get('del/{id}','hosokhenthuongController@destroy');
        });
        Route::group(['prefix'=>'ky_luat'],function(){
            Route::get('/maso={macanbo}','hosokyluatController@index');
            Route::get('del/{id}','hosokyluatController@destroy');
        });
        Route::group(['prefix'=>'thanh_tra'],function(){
            Route::get('/maso={macanbo}','hosothanhtraController@index');
            Route::get('del/{id}','hosothanhtraController@destroy');
        });
        Route::group(['prefix'=>'nhan_xet'],function(){
            Route::get('/maso={macanbo}','hosonhanxetdgController@index');
            Route::get('del/{id}','hosonhanxetdgController@destroy');
        });
    });
});

Route::group(['prefix'=>'chuc_nang'],function(){
    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('danh_sach','bangluongController@index');
        Route::get('update/{id}','bangluongController@update');
        Route::get('create','bangluongController@create');
        Route::get('/maso={mabl}','bangluongController@show');
        Route::get('in/maso={mabl}','bangluongController@inbangluong');
        Route::get('in_bh/maso={mabl}','bangluongController@inbaohiem');
        Route::get('/maso={mabl}/can_bo','bangluongController@detail');
        Route::post('updatect/{id}','bangluongController@updatect');
        Route::get('del/{id}','bangluongController@destroy');

        Route::get('get_phucap','bangluongController@get_phucap');
        Route::get('phucap','bangluongController@phucap');
        Route::get('del_phucap','bangluongController@detroys_phucap');

        Route::post('importexcel','bangluongController@importexcel');

    });
    Route::group(['prefix'=>'nang_luong'],function(){
        Route::get('danh_sach','dsnangluongController@index');
        Route::get('update/{id}','dsnangluongController@update');
        Route::get('create','dsnangluongController@create');
        Route::get('/maso={manl}','dsnangluongController@show');
        Route::get('del/{id}','dsnangluongController@destroy');
        Route::get('deldt/{id}','dsnangluongController@destroydt');
    });
    Route::group(['prefix'=>'tong_hop_luong'],function(){
        Route::get('danh_sach','dmdonvibaocaoController@donvi_luong');
        //?&thang={thang}&nam={nam}&khuvuc={makv}&huyen={mahuyen}
        Route::get('/ma_so={macqcq}/don_vi','dmdonvibaocaoController@donvi_luong1');
    });
    Route::group(['prefix'=>'het_tap_su'],function(){
        Route::get('danh_sach','dshettapsuController@index');
        Route::get('update/{id}','dshettapsuController@update');
        Route::get('create','dshettapsuController@create');
        Route::get('/maso={mahts}','dshettapsuController@show');
        Route::get('del/{id}','dshettapsuController@destroy');
    });

    Route::group(['prefix'=>'dao_tao'],function(){
        Route::get('danh_sach','dsdaotaoController@index');
        Route::get('update/{id}','dsdaotaoController@update');
        Route::get('create','dsdaotaoController@create');
        Route::post('store','dsdaotaoController@store');
        Route::get('/maso={mads}','dsdaotaoController@show');
        Route::patch('/maso={mads}','dsdaotaoController@update');
        Route::get('del/{id}','dsdaotaoController@destroy');

        Route::get('get_canbo_temp','dsdaotaoController@get_canbo_temp');
        Route::get('add_canbo_temp','dsdaotaoController@add_canbo_temp');
        Route::get('del_canbo_temp','dsdaotaoController@del_canbo_temp');
    });
    Route::group(['prefix'=>'buoc_thoi_viec'],function(){
        Route::get('danh_sach','dshettapsuController@index');
        Route::get('update/{id}','dshettapsuController@update');
        Route::get('create','dshettapsuController@create');
        Route::get('/maso={mahts}','dshettapsuController@show');
        Route::get('del/{id}','dshettapsuController@destroy');
    });
    Route::group(['prefix'=>'thuyen_chuyen'],function(){
        Route::get('danh_sach','dshettapsuController@index');
        Route::get('update/{id}','dshettapsuController@update');
        Route::get('create','dshettapsuController@create');
        Route::get('/maso={mahts}','dshettapsuController@show');
        Route::get('del/{id}','dshettapsuController@destroy');
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

    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('','baocaobangluongController@index');
        Route::post('mauc02ahd','baocaobangluongController@mauc02ahd');
        Route::post('mauc02x','baocaobangluongController@mauc02x');
        Route::post('maubaohiem','baocaobangluongController@maubaohiem');
        Route::post('chitraluong','baocaobangluongController@chitraluong');
        Route::get('dutoanluong','baocaobangluongController@dutoanluong');

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

});

Route::group(['prefix'=>'tra_cuu'],function(){
    Route::group(['prefix'=>'ho_so'],function(){
        Route::get('','hosocanboController@search');
        Route::post('ket_qua','hosocanboController@result');
    });
    Route::group(['prefix'=>'cong_tac'],function(){
        Route::get('','hosocongtacController@search');
        Route::post('ket_qua','hosocongtacController@result');
    });
    Route::group(['prefix'=>'dao_tao'],function(){
        Route::get('','hosodaotaoController@search');
        Route::post('ket_qua','hosodaotaoController@result');
    });
    Route::group(['prefix'=>'luong'],function(){
        Route::get('','hosoluongController@search');
        Route::post('ket_qua','hosoluongController@result');
    });
    Route::group(['prefix'=>'phu_cap'],function(){
        Route::get('','hosophucapController@search');
        Route::post('ket_qua','hosophucapController@result');
    });
    Route::group(['prefix'=>'gia_dinh'],function(){
        Route::get('','hosoquanhegdController@search');
        Route::post('ket_qua','hosoquanhegdController@result');
    });
});

Route::group(['prefix'=>'ajax'],function(){
    Route::get('kieuct','ajaxController@getKieuCT');
    Route::get('tenct','ajaxController@getTenCT');
    Route::get('tennb','ajaxController@getTenNB');
    Route::get('bac','ajaxController@getBac');
    Route::get('heso','ajaxController@getHS');
    Route::get('msnb','ajaxController@getMSNB');
    Route::get('checkmadv','ajaxController@checkmadv');
    Route::get('phucap','ajaxController@getPhuCap');

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
    Route::group(['prefix'=>'dieu_dong'],function(){
        Route::get('add','hosoluanchuyenController@store_dd');
        Route::get('update','hosoluanchuyenController@update_dd');
        Route::get('get','hosoluanchuyenController@get_detail');
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
    Route::group(['prefix'=>'bang_luong'],function(){
        Route::get('add','bangluongController@store');
        Route::get('update','bangluongController@update');
        Route::get('get','bangluongController@getinfo');
    });
    Route::group(['prefix'=>'nang_luong'],function(){
        Route::get('add','dsnangluongController@store');
        Route::get('update','dsnangluongController@update');
        Route::get('get','dsnangluongController@getinfo');
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

Route::group(['prefix'=>'luu_du_lieu'],function(){
    Route::get('can_bo','dshuutriController@store');
    Route::get('bang_luong','dshuutriController@update');
    Route::get('het_tap_su','dshuutriController@getinfo');
});

Route::group(['prefix'=>'he_thong'],function(){
    Route::group(['prefix'=>'don_vi'],function(){
        Route::get('don_vi','dmdonviController@information_local');
        Route::get('maso={madv}/edit_local','dmdonviController@edit_local');
        Route::patch('/{madv}','dmdonviController@update_local');

        Route::get('chung','dmdonviController@information_global');
        Route::get('maso={id}/edit_global','dmdonviController@edit_global');
        Route::patch('/{id}/global','dmdonviController@update_global');
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

        Route::get('don_vi/maso={id}/phan_quyen','dmdonviController@permission_list');
        Route::post('don_vi/maso={id}/up_perm','dmdonviController@permission_update');

        Route::get('don_vi/maso={madv}/don_vi','dmdonviController@edit_information');
        Route::patch('don_vi/maso={madv}','dmdonviController@update_information');
    });
});



