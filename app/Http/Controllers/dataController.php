<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Carbon\Carbon;

class dataController extends Controller
{
    //chay code nang cap db
    function update(){
        $a_pc = [
            'pck',
            'pccv',
            'pckv',
            'pcth',
            'pcdd',
            'pcdh',
            'pcld',
            'pcdbqh',
            'pcudn',
            'pctn',
            'pctnn',
            'pcdbn',
            'pcvk',//dùng để thay thế phụ cấp Đảng uy viên
            'pckn',
            'pcdang',
            'pccovu',
            'pclt', //lưu thay phụ cấp phân loại xã
            'pcd',
            'pctr',
            'pctdt',
            'pctnvk',
            'pcbdhdcu',
            'pcthni',

            'pclade',
            'pcud61',
            'pcxaxe',
            'pcdith',
            'luonghd',
            'pcphth',

        ];

        return view('errors.nangcapdb')
            ->with('a_pc',$a_pc);
    }

    //<editor-fold desc="Bảng chi tiết lương">
    function getBangluong_ct_th($thang, $nam, $madv, $manguonkp, $phanloai){
        //sau này chia bảng
        //for từng bảng rồi cộng vào trả lại mảng array
        /*Cũ 08.08.2019
        if ($manguonkp == null) {
            $model = \App\bangluong::join('bangluong_ct', 'bangluong.mabl', '=', 'bangluong_ct.mabl')
                ->where('bangluong.thang', $thang)
                ->where('bangluong.nam', $nam)
                ->where('bangluong.madv', $madv)
                ->where('bangluong.phanloai', 'BANGLUONG')
                ->select('bangluong_ct.*')
                //->orderby('bangluong_ct.stt')
                ->get()->sortby('stt')->toarray();
        } else {
            $model = \App\bangluong::join('bangluong_ct', 'bangluong.mabl', '=', 'bangluong_ct.mabl')
                ->where('bangluong.thang', $thang)
                ->where('bangluong.nam', $nam)
                ->where('bangluong.madv', $madv)
                ->wherein('bangluong.manguonkp', $manguonkp)
                ->where('bangluong.phanloai', 'BANGLUONG')
                ->select('bangluong_ct.*')
                //->orderby('bangluong_ct.stt')
                ->get()->sortby('stt')->toarray();
        }
        */
        if ($manguonkp == null) {
            $a_bl = \App\bangluong::select('mabl')
                ->where('thang', $thang)
                ->where('nam', $nam)
                ->where('madv', $madv)
                ->where('phanloai', $phanloai)->get();
        } else {
            $a_bl = \App\bangluong::select('mabl')
                ->where('thang', $thang)
                ->where('nam', $nam)
                ->where('madv', $madv)
                ->wherein('manguonkp', $manguonkp)
                ->where('phanloai', $phanloai)->get()->toarray();
        }

        return  \App\bangluong_ct::wherein('mabl',$a_bl)->orderby('stt')->get();
        /*
        switch($thang){
            case '01':{
                return  \App\bangluong_ct_01::where('mabl',$mabl)->get();
                break;
            }
            case '02':{
                return \App\bangluong_ct_02::where('mabl',$mabl)->get();
                break;
            }
            case '03':{
                return \App\bangluong_ct_03::where('mabl',$mabl)->get();
                break;
            }
            case '04':{
                return \App\bangluong_ct_04::where('mabl',$mabl)->get();
                break;
            }
            case '05':{
                return \App\bangluong_ct_05::where('mabl',$mabl)->get();
                break;
            }
            case '06':{
                return \App\bangluong_ct_06::where('mabl',$mabl)->get();
                break;
            }
            case '07':{
                return \App\bangluong_ct_07::where('mabl',$mabl)->get();
                break;
            }
            case '08':{
                return \App\bangluong_ct_08::where('mabl',$mabl)->get();
                break;
            }
            case '09':{
                return \App\bangluong_ct_09::where('mabl',$mabl)->get();
                break;
            }
            case '10':{
                return \App\bangluong_ct_10::where('mabl',$mabl)->get();
                break;
            }
            case '11':{
                return \App\bangluong_ct_11::where('mabl',$mabl)->get();
                break;
            }
            case '12':{
                return \App\bangluong_ct_12::where('mabl',$mabl)->get();
                break;
            }
        }
        */
    }

    function storeBangLuong($thang, $data){
        //chưa chia bảng
        \App\bangluong_ct::insert($data);

        /*
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::insert($data);
                break;
            }
            case '02':{
                \App\bangluong_ct_02::insert($data);
                break;
            }
            case '03':{
                \App\bangluong_ct_03::insert($data);
                break;
            }
            case '04':{
                \App\bangluong_ct_04::insert($data);
                break;
            }
            case '05':{
                \App\bangluong_ct_05::insert($data);
                break;
            }
            case '06':{
                \App\bangluong_ct_06::insert($data);
                break;
            }
            case '07':{
                \App\bangluong_ct_07::insert($data);
                break;
            }
            case '08':{
                \App\bangluong_ct_08::insert($data);
                break;
            }
            case '09':{
                \App\bangluong_ct_09::insert($data);
                break;
            }
            case '10':{
                \App\bangluong_ct_10::insert($data);
                break;
            }
            case '11':{
                \App\bangluong_ct_11::insert($data);
                break;
            }
            case '12':{
                \App\bangluong_ct_12::insert($data);
                break;
            }
        }
        */
    }

    function createBangLuong($thang, $data){
        //chưa chia bảng
        \App\bangluong_ct::create($data);
        /*
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::create($data);
                break;
            }
            case '02':{
                \App\bangluong_ct_02::create($data);
                break;
            }
            case '03':{
                \App\bangluong_ct_03::create($data);
                break;
            }
            case '04':{
                \App\bangluong_ct_04::create($data);
                break;
            }
            case '05':{
                \App\bangluong_ct_05::create($data);
                break;
            }
            case '06':{
                \App\bangluong_ct_06::create($data);
                break;
            }
            case '07':{
                \App\bangluong_ct_07::create($data);
                break;
            }
            case '08':{
                \App\bangluong_ct_08::create($data);
                break;
            }
            case '09':{
                \App\bangluong_ct_09::create($data);
                break;
            }
            case '10':{
                \App\bangluong_ct_10::create($data);
                break;
            }
            case '11':{
                \App\bangluong_ct_11::create($data);
                break;
            }
            case '12':{
                \App\bangluong_ct_12::create($data);
                break;
            }
        }
        */
    }

    function getBangluong_ct($thang, $mabl){
        //chưa chia bảng
        return  \App\bangluong_ct::where('mabl',$mabl)->get();
        /*
        switch($thang){
            case '01':{
                return  \App\bangluong_ct_01::where('mabl',$mabl)->get();
                break;
            }
            case '02':{
                return \App\bangluong_ct_02::where('mabl',$mabl)->get();
                break;
            }
            case '03':{
                return \App\bangluong_ct_03::where('mabl',$mabl)->get();
                break;
            }
            case '04':{
                return \App\bangluong_ct_04::where('mabl',$mabl)->get();
                break;
            }
            case '05':{
                return \App\bangluong_ct_05::where('mabl',$mabl)->get();
                break;
            }
            case '06':{
                return \App\bangluong_ct_06::where('mabl',$mabl)->get();
                break;
            }
            case '07':{
                return \App\bangluong_ct_07::where('mabl',$mabl)->get();
                break;
            }
            case '08':{
                return \App\bangluong_ct_08::where('mabl',$mabl)->get();
                break;
            }
            case '09':{
                return \App\bangluong_ct_09::where('mabl',$mabl)->get();
                break;
            }
            case '10':{
                return \App\bangluong_ct_10::where('mabl',$mabl)->get();
                break;
            }
            case '11':{
                return \App\bangluong_ct_11::where('mabl',$mabl)->get();
                break;
            }
            case '12':{
                return \App\bangluong_ct_12::where('mabl',$mabl)->get();
                break;
            }
        }
        */
    }

    function getBangluong_ct_cb($thang, $id){
        //chưa chia bảng (ko dùng macanbo do 1 cán bộ có thể có nhiều bản ghi - kiêm nhiệm)
        return  \App\bangluong_ct::findorfail($id);
        /*
        switch($thang){
            case '01':{
                return  \App\bangluong_ct_01::findorfail($id);
                break;
            }
            case '02':{
                return \App\bangluong_ct_02::findorfail($id);
                break;
            }
            case '03':{
                return \App\bangluong_ct_03::findorfail($id);
                break;
            }
            case '04':{
                return \App\bangluong_ct_04::findorfail($id);
                break;
            }
            case '05':{
                return \App\bangluong_ct_05::findorfail($id);
                break;
            }
            case '06':{
                return \App\bangluong_ct_06::findorfail($id);
                break;
            }
            case '07':{
                return \App\bangluong_ct_07::findorfail($id);
                break;
            }
            case '08':{
                return \App\bangluong_ct_08::findorfail($id);
                break;
            }
            case '09':{
                return \App\bangluong_ct_09::findorfail($id);
                break;
            }
            case '10':{
                return \App\bangluong_ct_10::findorfail($id);
                break;
            }
            case '11':{
                return \App\bangluong_ct_11::findorfail($id);
                break;
            }
            case '12':{
                return \App\bangluong_ct_12::findorfail($id);
                break;
            }
        }
        */
    }

    //lấy bảng lương chi tiết theo mảng mabl
    function getBangluong_ct_ar($thang, $a_mabl, $a_col = '*'){
        //chưa chia bảng
        return  \App\bangluong_ct::select($a_col)->wherein('mabl',$a_mabl)->get();
        /*
        switch($thang){
            case '01':{
                return  \App\bangluong_ct_01::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '02':{
                return \App\bangluong_ct_02::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '03':{
                return \App\bangluong_ct_03::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '04':{
                return \App\bangluong_ct_04::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '05':{
                return \App\bangluong_ct_05::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '06':{
                return \App\bangluong_ct_06::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '07':{
                return \App\bangluong_ct_07::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '08':{
                return \App\bangluong_ct_08::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '09':{
                return \App\bangluong_ct_09::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '10':{
                return \App\bangluong_ct_10::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '11':{
                return \App\bangluong_ct_11::wherein('mabl',$a_mabl)->get();
                break;
            }
            case '12':{
                return \App\bangluong_ct_12::wherein('mabl',$a_mabl)->get();
                break;
            }
        }
        */
    }

    function destroyBangluong_ct($thang, $mabl){
        //chưa chia bảng
        \App\bangluong_ct::where('mabl', $mabl)->delete();
        /*
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::where('mabl',$mabl)->delete();
                break;
            }
            case '02':{
                \App\bangluong_ct_02::where('mabl',$mabl)->delete();
                break;
            }
            case '03':{
                \App\bangluong_ct_03::where('mabl',$mabl)->delete();
                break;
            }
            case '04':{
                \App\bangluong_ct_04::where('mabl',$mabl)->delete();
                break;
            }
            case '05':{
                \App\bangluong_ct_05::where('mabl',$mabl)->delete();
                break;
            }
            case '06':{
                \App\bangluong_ct_06::where('mabl',$mabl)->delete();
                break;
            }
            case '07':{
                \App\bangluong_ct_07::where('mabl',$mabl)->delete();
                break;
            }
            case '08':{
                \App\bangluong_ct_08::where('mabl',$mabl)->delete();
                break;
            }
            case '09':{
                \App\bangluong_ct_09::where('mabl',$mabl)->delete();
                break;
            }
            case '10':{
                \App\bangluong_ct_10::where('mabl',$mabl)->delete();
                break;
            }
            case '11':{
                \App\bangluong_ct_11::where('mabl',$mabl)->delete();
                break;
            }
            case '12':{
                \App\bangluong_ct_12::where('mabl',$mabl)->delete();
                break;
            }
        }
        */
    }

    function destroyBangluong_ct_cb($thang, $id){
        //chưa chia bảng
        \App\bangluong_ct::findorfail($id)->delete();
        /*
        switch($thang){
            case '01':{
                \App\bangluong_ct_01::findorfail($id)->delete();
                break;
            }
            case '02':{
                \App\bangluong_ct_02::findorfail($id)->delete();
                break;
            }
            case '03':{
                \App\bangluong_ct_03::findorfail($id)->delete();
                break;
            }
            case '04':{
                \App\bangluong_ct_04::findorfail($id)->delete();
                break;
            }
            case '05':{
                \App\bangluong_ct_05::findorfail($id)->delete();
                break;
            }
            case '06':{
                \App\bangluong_ct_06::findorfail($id)->delete();
                break;
            }
            case '07':{
                \App\bangluong_ct_07::findorfail($id)->delete();
                break;
            }
            case '08':{
                \App\bangluong_ct_08::findorfail($id)->delete();
                break;
            }
            case '09':{
                \App\bangluong_ct_09::findorfail($id)->delete();
                break;
            }
            case '10':{
                \App\bangluong_ct_10::findorfail($id)->delete();
                break;
            }
            case '11':{
                \App\bangluong_ct_11::findorfail($id)->delete();
                break;
            }
            case '12':{
                \App\bangluong_ct_12::findorfail($id)->delete();
                break;
            }
        }
        */
    }
    //</editor-fold>

    //<editor-fold desc="Danh sách cán bộ">
    //Hàm kiểm tra nâng lương so với ngày xét
    //biến $nangluong để tự nâng lương cán bộ - dành cho dư toán, nhu cầu kp
    function getCanBo($m_canbo, $ngayxet, $nangluong = false, $ngaynangluong = null){
        $a_nglg = \App\ngachluong::all()->keyby('msngbac')->toarray();
        foreach($m_canbo as $canbo){
            //xet lương ngạch bậc
            if(getDayVn($canbo->ngaytu) != '' && $canbo->ngaytu > $ngayxet){
                if(!isset($a_nglg[$canbo->msngbac])){
                    continue;
                }
                $nglg = $a_nglg[$canbo->msngbac];
                $canbo->ngayden = $canbo->ngaytu;//xét lại ngày nâng lương
                if($canbo->heso < $nglg['hesolonnhat']){//nâng lương ngạch bậc
                    $canbo->heso -= $nglg['hesochenhlech'];
                }else{//vượt khung
                    $canbo->vuotkhung = $canbo->vuotkhung == 5 ? 0 : $canbo->vuotkhung - 1;
                }
            }
            //xét thâm niên nghề
            if(getDayVn($canbo->tnntungay) != '' && $canbo->tnntungay > $ngayxet){
                $canbo->pctnn = $canbo->pctnn == 5 ? 0 : $canbo->pctnn - 1;
                $canbo->tnndenngay = $canbo->tnntungay;//xét lại ngày nâng lương
            }
            //tự động nâng lương cho cán bộ chưa nâng lương (trước thời điểm xét) - dành cho dư toán, nhu cầu kp
            if($nangluong){
                if(getDayVn($canbo->ngayden) != '' && $canbo->ngayden <= $ngaynangluong){
                    if(!isset($a_nglg[$canbo->msngbac])){
                        continue;
                    }
                    $canbo->ngaytu = $canbo->ngayden;
                    $date = new Carbon($canbo->ngayden);
                    $nglg = $a_nglg[$canbo->msngbac];
                    if($canbo->heso < $nglg['hesolonnhat']){//nâng lương ngạch bậc
                        $canbo->heso += $nglg['hesochenhlech'];
                        $canbo->ngayden = $date->addYear($nglg['namnb'])->toDateString();
                    }else{//vượt khung
                        $canbo->vuotkhung = $canbo->vuotkhung == 0 ? 5 : $canbo->vuotkhung + 1;
                        $canbo->ngayden = $date->addYear(1)->toDateString();
                    }
                }

                //xét thâm niên nghề
                if(getDayVn($canbo->tnndenngay) != '' && $canbo->tnndenngay <= $ngaynangluong){
                    $canbo->pctnn = $canbo->pctnn == 0 ? 5 : $canbo->pctnn + 1;
                    $canbo->tnntungay = $canbo->tnndenngay;
                    $datetn = new Carbon($canbo->tnndenngay);
                    $canbo->tnndenngay = $datetn->addYear(1)->toDateString();
                }
            }

        }
        return $m_canbo;
    }
    //</editor-fold>
}
