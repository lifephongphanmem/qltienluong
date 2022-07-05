@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 4b</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'Đơn vị: '.$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">TỔNG HỢP NHU CẦU, NGUỒN THỰC HIỆN NGHỊ ĐỊNH 38/2019/NĐ-CP NĂM 2019</p>
<p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 10px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;padding-left: 2px;padding-right: 2px" rowspan="3">TT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">CHỈ TIÊU</th>
        <th style="width: 10%;padding-left: 2px;padding-right: 2px" rowspan="3">NHU CẦU KINH PHÍ THỰC HIỆN CCTL NĂM 2019</th>
        <th style="width: 40%;padding-left: 2px;padding-right: 2px" colspan="6">NGUỒN TỪ TIẾT KIỆM 10% CHI THƯỜNG XUYÊN VÀ NGUỒN THU ĐỂ LẠI ĐƠN VỊ VÀ NGUỒN TIẾT KIỆM THEO NGHỊ QUYẾT 18, 19</th>

    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th rowspan="2">TỔNG SỐ</th>
        <th rowspan="2">TIẾT KIỆM 10% CHI THƯỜNG XUYÊN</th>
        <th colspan="3">NGUỒN THU TỪ ĐƠN VỊ HÀNH CHÍNH, SỰ NGHIỆP</th>
        <th rowspan="2">TIẾT KIỆM CHI THEO NGHỊ QUYẾT 18,19</th>
    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>HỌC PHÍ</th>
        <th>VIỆN PHÍ</th>
        <th>KHÁC</th>
    </tr>
    <tr style="font-weight: bold;">
        <td></td>
        <td>TỔNG SỐ</td>
        <td class="money">{{dinhdangso($a_TC['nhucau'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['nguonkp'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['tietkiem'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['hocphi'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['vienphi'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['nguonthu'],0,$inputs['donvitinh'])}}</td>
        <td class="money">{{dinhdangso($a_TC['khac'],0,$inputs['donvitinh'])}}</td>
    </tr>
    @foreach($tongcong as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['nhucau'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonkp'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['tietkiem'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['hocphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['vienphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonthu'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['khac'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold;">
        <td></td>
        <td>Trong đó:</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <tr style="font-weight: bold;">
        <td>I</td>
        <td>Cấp tỉnh</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <?php
    if (isset($inputs['inchitiet'])) {
        $group[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $group[6] = array('val' => 'KVXP', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
    } else {
        $data[0] = array('val' => 'GDDT', 'tt' => 'a', 'noidung' => 'Sự nghiệp giáo dục - đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[1] = array('val' => 'GD', 'tt' => '-', 'noidung' => 'Giáo dục', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[2] = array('val' => 'DT', 'tt' => '-', 'noidung' => 'Đào tạo', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[3] = array('val' => 'YTE', 'tt' => 'b', 'noidung' => 'Sự nghiệp y tế', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[4] = array('val' => 'KHAC', 'tt' => 'c', 'noidung' => 'Sự nghiệp khác', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[5] = array('val' => 'QLNN', 'tt' => 'd', 'noidung' => ' Quản lý nhà nước, Đảng, đoàn thể', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
        $data[6] = array('val' => 'KVXP', 'tt' => '-', 'noidung' => 'Trong đó: Cán bộ, công chức cấp xã', 'nhucau' => 0, 'nguonkp' => 0, 'tietkiem' => 0, 'hocphi' => 0, 'vienphi' => 0, 'nguonthu' => 0);
    }
    $model_ct = $model->where('caphanhchinh','T');
    if (isset($inputs['inchitiet'])) {
        $gddt = 0;
        $daotao = 0;
        $giaoduc = 0;
        $i = 0;
        for ($j = 0; $j < count($group); $j++) {
            $i++;
            if ($group[$j]['val'] == 'GDDT')
                $gddt = $i;
            if ($group[$j]['val'] == 'GD')
                $giaoduc = $i;
            if ($group[$j]['val'] == 'DT')
                $daotao = $i;
            $data[$i]['val'] = $group[$j]['val'];
            $data[$i]['tt'] = $group[$j]['tt'];
            $data[$i]['noidung'] = $group[$j]['noidung'];
            $data[$i]['nhucau'] = 0;
            $data[$i]['nguonkp'] = 0;
            $data[$i]['tietkiem'] = 0;
            $data[$i]['hocphi'] = 0;
            $data[$i]['vienphi'] = 0;
            $data[$i]['khac'] = 0;
            $data[$i]['nguonthu'] = 0;
            $luugr = $i;
            if($group[$j]['val'] == 'KHAC'){
                foreach($a_sn_cl as $sn){
                    $dulieu = $model_ct->where('linhvuchoatdong', $sn);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        $a_dv = a_unique(array_column($dulieu->toarray(),'madv'));
                        foreach ($a_dv as $chitietdv) {
                            $solieu = $dulieu->where('madv', $chitietdv);
                            $d =1;
                            if (isset($solieu) && count($solieu) > 0) {
                                $d++;
                                $i += $d;
                                $data[$i]['val'] = $group[$j]['val'];
                                $data[$i]['tt'] = '+';
                                $data[$i]['noidung'] = $model_dv->where('madv',$chitietdv)->first()->tendv;
                                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['khac'] += 0;
                                $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                            }
                        }
                    }
                }
            }
            else {
                $dulieu = $model_ct->where('linhvuchoatdong', $group[$j]['val']);
                if (isset($dulieu) && count($dulieu) > 0) {
                    //$luugr = 0;
                    $a_dv = a_unique(array_column($dulieu->toarray(), 'madv'));
                    $luugr = $i;
                    foreach ($a_dv as $chitietdv) {
                        $solieu = $model->where('madv', $chitietdv);
                        $d = 1;
                        if (isset($solieu) && count($solieu) > 0) {
                            //dd($solieu);
                            $d++;
                            $i += $d;
                            $data[$i]['val'] = $group[$j]['val'];
                            $data[$i]['tt'] = '+';
                            $data[$i]['noidung'] = $model_dv->where('madv', $chitietdv)->first()->tendv;
                            $data[$i]['nhucau'] = $solieu->sum('nhucau');
                            $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                            $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                            $data[$i]['hocphi'] = $solieu->sum('hocphi');
                            $data[$i]['vienphi'] = $solieu->sum('vienphi');
                            $data[$i]['khac'] = 0;
                            $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                            $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                            $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                            $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                            $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                            $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                            $data[$luugr]['khac'] += 0;
                            $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                        }
                    }
                }
            }
        }
        $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
        $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
        $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
        $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
        $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
        $data[$gddt]['khac'] = 0;
        $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
    } else {
        for ($i = 0; $i < count($data); $i++) {
            $solieu = $model_ct->where('linhvuchoatdong', $data[$i]['val']);
            $data[$i]['nhucau'] = $solieu->sum('nhucau');
            $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
            $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
            $data[$i]['hocphi'] = $solieu->sum('hocphi');
            $data[$i]['vienphi'] = $solieu->sum('vienphi');
            $data[$i]['khac'] = 0;
            $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
        }
        $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
        $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
        $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
        $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
        $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
        $data[0]['khac'] = 0;
        $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];

        $data[4]['nhucau'] = $model_ct->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
        $data[4]['nguonkp'] = $model_ct->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
        $data[4]['tietkiem'] = $model_ct->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
        $data[4]['hocphi'] = $model_ct->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
        $data[4]['vienphi'] = $model_ct->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
        $data[4]['khac'] = 0;
        $data[4]['nguonthu'] = $model_ct->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
    }
    ?>
    @foreach($data as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['nhucau'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonkp'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['tietkiem'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['hocphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['vienphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonthu'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['khac'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold;">
        <td>II</td>
        <td>Cấp huyện</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <?php

        $stt = 0;
        foreach($a_h as $huyen=>$key){
            $model_ct = $model->where('caphanhchinh','<>','T')->where('madvbc',$huyen);
            $stt++;
        ?>
    <tr style="font-weight: bold;">
        <td>{{$stt}}</td>
        <td>{{$a_h[$huyen]}}</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <?php
    if (isset($inputs['inchitiet'])) {
        $gddt = 0;
        $daotao = 0;
        $giaoduc = 0;
        $i = 0;
        for ($j = 0; $j < count($group); $j++) {
            $i++;
            if ($group[$j]['val'] == 'GDDT')
                $gddt = $i;
            if ($group[$j]['val'] == 'GD')
                $giaoduc = $i;
            if ($group[$j]['val'] == 'DT')
                $daotao = $i;
            $data[$i]['val'] = $group[$j]['val'];
            $data[$i]['tt'] = $group[$j]['tt'];
            $data[$i]['noidung'] = $group[$j]['noidung'];
            $data[$i]['nhucau'] = 0;
            $data[$i]['nguonkp'] = 0;
            $data[$i]['tietkiem'] = 0;
            $data[$i]['hocphi'] = 0;
            $data[$i]['vienphi'] = 0;
            $data[$i]['khac'] = 0;
            $data[$i]['nguonthu'] = 0;
            $luugr = $i;
            if($group[$j]['val'] == 'KHAC'){
                foreach($a_sn_cl as $sn){
                    $dulieu = $model_ct->where('linhvuchoatdong', $sn);
                    if (isset($dulieu) && count($dulieu) > 0) {
                        $a_dv = a_unique(array_column($dulieu->toarray(),'madv'));
                        foreach ($a_dv as $chitietdv) {
                            $solieu = $dulieu->where('madv', $chitietdv);
                            $d =1;
                            if (isset($solieu) && count($solieu) > 0) {
                                $d++;
                                $i += $d;
                                $data[$i]['val'] = $group[$j]['val'];
                                $data[$i]['tt'] = '+';
                                $data[$i]['noidung'] = $model_dv->where('madv',$chitietdv)->first()->tendv;
                                $data[$i]['nhucau'] = $solieu->sum('nhucau');
                                $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                                $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                                $data[$i]['hocphi'] = $solieu->sum('hocphi');
                                $data[$i]['vienphi'] = $solieu->sum('vienphi');
                                $data[$i]['khac'] = 0;
                                $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                                $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                                $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                                $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                                $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                                $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                                $data[$luugr]['khac'] += 0;
                                $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                            }
                        }
                    }
                }
            }
            else {
                $dulieu = $model_ct->where('linhvuchoatdong', $group[$j]['val']);
                if (isset($dulieu) && count($dulieu) > 0) {
                    //$luugr = 0;
                    $a_dv = a_unique(array_column($dulieu->toarray(), 'madv'));
                    $luugr = $i;
                    foreach ($a_dv as $chitietdv) {
                        $solieu = $model->where('madv', $chitietdv);
                        $d = 1;
                        if (isset($solieu) && count($solieu) > 0) {
                            //dd($solieu);
                            $d++;
                            $i += $d;
                            $data[$i]['val'] = $group[$j]['val'];
                            $data[$i]['tt'] = '+';
                            $data[$i]['noidung'] = $model_dv->where('madv', $chitietdv)->first()->tendv;
                            $data[$i]['nhucau'] = $solieu->sum('nhucau');
                            $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
                            $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
                            $data[$i]['hocphi'] = $solieu->sum('hocphi');
                            $data[$i]['vienphi'] = $solieu->sum('vienphi');
                            $data[$i]['khac'] = 0;
                            $data[$i]['nguonthu'] = $solieu->sum('nguonthu');

                            $data[$luugr]['nhucau'] += $solieu->sum('nhucau');
                            $data[$luugr]['nguonkp'] += $solieu->sum('nguonkp');
                            $data[$luugr]['tietkiem'] += $solieu->sum('tietkiem');
                            $data[$luugr]['hocphi'] += $solieu->sum('hocphi');
                            $data[$luugr]['vienphi'] += $solieu->sum('vienphi');
                            $data[$luugr]['khac'] += 0;
                            $data[$luugr]['nguonthu'] += $solieu->sum('nguonthu');
                        }
                    }
                }
            }
        }
        $data[$gddt]['nhucau'] = $data[$giaoduc]['nhucau'] + $data[$daotao]['nhucau'];
        $data[$gddt]['nguonkp'] = $data[$giaoduc]['nguonkp'] + $data[$daotao]['nguonkp'];
        $data[$gddt]['tietkiem'] = $data[$giaoduc]['tietkiem'] + $data[$daotao]['tietkiem'];
        $data[$gddt]['hocphi'] = $data[$giaoduc]['hocphi'] + $data[$daotao]['hocphi'];
        $data[$gddt]['vienphi'] = $data[$giaoduc]['vienphi'] + $data[$daotao]['vienphi'];
        $data[$gddt]['khac'] = 0;
        $data[$gddt]['nguonthu'] = $data[$giaoduc]['nguonthu'] + $data[$daotao]['nguonthu'];
    } else {
        for ($i = 0; $i < count($data); $i++) {
            $solieu = $model_ct->where('linhvuchoatdong', $data[$i]['val']);
            $data[$i]['nhucau'] = $solieu->sum('nhucau');
            $data[$i]['nguonkp'] = $solieu->sum('nguonkp');
            $data[$i]['tietkiem'] = $solieu->sum('tietkiem');
            $data[$i]['hocphi'] = $solieu->sum('hocphi');
            $data[$i]['vienphi'] = $solieu->sum('vienphi');
            $data[$i]['khac'] = 0;
            $data[$i]['nguonthu'] = $solieu->sum('nguonthu');
        }
        $data[0]['nhucau'] = $data[1]['nhucau'] + $data[2]['nhucau'];
        $data[0]['nguonkp'] = $data[1]['nguonkp'] + $data[2]['nguonkp'];
        $data[0]['tietkiem'] = $data[1]['tietkiem'] + $data[2]['tietkiem'];
        $data[0]['hocphi'] = $data[1]['hocphi'] + $data[2]['hocphi'];
        $data[0]['vienphi'] = $data[1]['vienphi'] + $data[2]['vienphi'];
        $data[0]['khac'] = 0;
        $data[0]['nguonthu'] = $data[1]['nguonthu'] + $data[2]['nguonthu'];

        $data[4]['nhucau'] = $model_ct->sum('nhucau') - $data[0]['nhucau'] - $data[5]['nhucau'] - $data[3]['nhucau'];
        $data[4]['nguonkp'] = $model_ct->sum('nguonkp') - $data[0]['nguonkp'] - $data[5]['nguonkp'] - $data[3]['nguonkp'];
        $data[4]['tietkiem'] = $model_ct->sum('tietkiem') - $data[0]['tietkiem'] - $data[5]['tietkiem'] - $data[3]['tietkiem'];
        $data[4]['hocphi'] = $model_ct->sum('hocphi') - $data[0]['hocphi'] - $data[5]['hocphi'] - $data[3]['hocphi'];
        $data[4]['vienphi'] = $model_ct->sum('vienphi') - $data[0]['vienphi'] - $data[5]['vienphi'] - $data[3]['vienphi'];
        $data[4]['khac'] = 0;
        $data[4]['nguonthu'] = $model_ct->sum('nguonthu') - $data[0]['nguonthu'] - $data[5]['nguonthu'] - $data[3]['nguonthu'];
    }
    ?>
    @foreach($data as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['nhucau'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonkp'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['tietkiem'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['hocphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['vienphi'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonthu'],0,$inputs['donvitinh'])}}</td>
            <td class="money">{{dinhdangso($dulieu['khac'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <?php }?>
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">((Ký tên, đóng dấu))</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

@stop