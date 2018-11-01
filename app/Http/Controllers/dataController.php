<?php

namespace App\Http\Controllers;
use App\Http\Requests;

class dataController extends Controller
{
    function getBangluong_ct($thang,$nam,$madv, $mabl){//thừa đk
        //sau này chia bảng
    }

    function getBangluong_ct_th($thang,$nam,$madv){
        //sau này chia bảng
        //for từng bảng rồi cộng vào trả lại mảng array
        $model = \App\bangluong::join('bangluong_ct','bangluong.mabl','=','bangluong_ct.mabl')
            ->where('bangluong.thang',$thang)
            ->where('bangluong.nam', $nam)
            ->where('bangluong.madv',$madv)
            ->where('bangluong.phanloai', 'BANGLUONG')
            ->select('bangluong_ct.*')->get()->toarray();
        /*
        $model = bangluong_ct::where('mabl', $inputs['mabl'])->get();
        $m_hoso = hosocanbo::where('madv', $inputs['madv'])->get();

        $a_ht = array_column($m_hoso->toarray(),'tencanbo','macanbo');
        $dmchucvucq = array_column(dmchucvucq::all('tenvt', 'macvcq')->toArray(), 'tenvt', 'macvcq');
        $sunghiep = array_column($m_hoso->toarray(), 'sunghiep', 'macanbo');
        $nhomct = array_column(dmphanloaict::all('macongtac', 'mact')->toArray(), 'macongtac', 'mact');

        foreach ($model as $hs) {
            $hs->tencv = isset($dmchucvucq[$hs->macvcq]) ? $dmchucvucq[$hs->macvcq] : '';
            $hs->sunghiep = isset($sunghiep[$hs->macanbo]) ? $sunghiep[$hs->macanbo] : '';
            $hs->macongtac = isset($nhomct[$hs->mact]) ? $nhomct[$hs->mact] : '';
            if($hs->tencanbo == ''){
                $hs->tencanbo = isset($a_ht[$hs->macanbo]) ? $a_ht[$hs->macanbo] : ''; //kiêm nhiệm chưa có tên cán bộ
            }
        }
        */
        return $model;
    }
}
