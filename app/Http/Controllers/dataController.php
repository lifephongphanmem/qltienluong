<?php

namespace App\Http\Controllers;
use App\Http\Requests;

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
            ->select('bangluong_ct.*')
            //->orderby('bangluong_ct.stt')
            ->get()->sortby('stt')->toarray();
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
