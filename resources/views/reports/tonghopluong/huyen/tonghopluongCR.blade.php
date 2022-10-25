@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td  style="text-align: left;width: 60%">

            </td>
            <td  style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{$m_dv->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                TỔNG HỢP KINH PHÍ TIỀN LƯƠNG THÁNG {{$thang}} NĂM {{$nam}}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Đơn vị</th>
            <th style="width: 3%;" colspan="2">Biên chế</th>
            <th colspan="{{$col + 5}}">Hệ số lương, p/cấp và các khoản đ/góp</th>
            <th rowspan="3">Tổng lương tháng {{$thang}}/{{$nam}}</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 3%;" rowspan="2">Được cấp có thẩm quyền giao</th>
            <th style="width: 3%;" rowspan="2">Có mặt</th>
            <th rowspan="2">Tổng cộng </th>
            <th rowspan="2">Lương ngạch, bậc, CV</th>
            <th colspan="{{$col+1}}">Trong đó</th>
            <th colspan="2">Các khoản đóng góp</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th >Tổng các khoản P/cấp</th>
            @foreach($a_phucap as $key=>$val)
                <th >{!!$val!!}</th>
            @endforeach
            <th>BHXH, YT, CĐ</th>
            <th>Thất nghiệp</th>
        </tr>
        <tr style="font-weight: bold;" class="money">
            <td></td>
            <td style="font-weight: bold; text-align: center">TỔNG SỐ</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_th->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_th->sum('stbhxh_dv')+$model_th->sum('stbhyt_dv')+$model_th->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('ttl') + $model_th->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($model_th as $ct)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$ct->tencongtac}}</td>
                <td style="text-align: right">{{dinhdangso($ct->soluong)}}</td>
                <td style="text-align: right">{{dinhdangso($ct->soluongcomat)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->tongcong,5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->heso,5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->tongpc,5)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                @endforeach

                <td>{{dinhdangso($ct->stbhxh_dv+$ct->stbhyt_dv+$ct->stkpcd_dv)}}</td>
                <td>{{dinhdangso($ct->stbhtn_dv)}}</td>
                <td>{{dinhdangso($ct->tongtienluong)}}</td>
            </tr>
        @endforeach

        <?php
            $model_gd = $model->where('linhvuchoatdong','GD');
            $model_gdT = $model_gd->groupby('tencongtac');
            $a_plcongtac = array_column($model_gd->toarray(),'mact' , 'tencongtac');
            //dd($model_gdT->toarray());
            //dd($a_plcongtac);
        ?>
        <tr style="font-weight: bold;">
            <td>I</td>
            <td style="font-weight: bold;text-align: left">Sự nghiệp giáo dục</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_gd->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stbhxh_dv')+$model_gd->sum('stbhyt_dv')+$model_gd->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('ttl') + $model_gd->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdT[$key]->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_gdT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_gdT[$key]->sum('stbhxh_dv')+$model_gdT[$key]->sum('stbhyt_dv')+$model_gdT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('ttl') + $model_gdT[$key]->sum('ttbh_dv'))}}</td>
            </tr>
        @endforeach
        <?php
        $model_gd = $model->where('linhvuchoatdong','GD');
        $model_gdpl = $model_gd->groupby('tenphanloai');
            //dd($model_gdpl->toarray());
        $a_pldv = array_column($model_gd->toarray(),'maphanloai' , 'tenphanloai');
            $stt = 0;
        ?>
        @foreach($a_pldv as $key=>$val)
            <?php $stt ++;
            $model_gdpl = $model_gd->where('maphanloai',$a_pldv[$key]);
            $model_gdplCT = $model_gdpl->groupby('tencongtac');
            $a_plcongtac = array_column($model_gdpl->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money">
                <td>{{$stt}}</td>
                <td style="font-weight: bold;text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_gdpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stbhxh_dv')+$model_gdpl->sum('stbhyt_dv')+$model_gdpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('ttl') + $model_gdpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_gdplCT[$key]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_gdplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_gdplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stbhxh_dv')+$model_gdplCT[$key]->sum('stbhyt_dv')+$model_gdplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('ttl') + $model_gdplCT[$key]->sum('ttbh_dv'))}}</td>
                </tr>

            @endforeach
                <?php
                    $ttdv = 0;
                    $model_gddv = $model_gdpl->groupby('tendv');

                    $a_donvi = array_column($model_gdpl->toarray(),'madv' , 'tendv');
                    $a_plcongtac = array_column($model_gdpl->toarray(),'mact' , 'tencongtac');
                //dd($model_gddv->toarray());
                ?>
                @foreach($a_donvi as $keydv=>$val)
                    <?php $ttdv ++;
                    $model_gddvCT= $model_gdpl->where('tendv',$keydv)->groupby('tencongtac');
                    $a_plctdv = array_column($model_gdpl->where('tendv',$keydv)->toarray(),'mact' , 'tencongtac');
                    ?>
                    <tr class="money" style="font-weight: bold">
                        <td style="text-align: center">{{$stt.'.'.$ttdv}}</td>
                        <td style="text-align: left">{{$keydv}}</td>
                        <td style="text-align: right">{{dinhdangso($model_gddv[$keydv]->sum('soluong'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_gddv[$keydv]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('tongcong'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_gddv[$keydv]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stbhxh_dv')+$model_gddv[$keydv]->sum('stbhyt_dv')+$model_gddv[$keydv]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('ttl') + $model_gddv[$keydv]->sum('ttbh_dv'))}}</td>
                    </tr>
                        @foreach($a_plctdv as $key=>$val)
                            <tr class="money">
                                <td style="text-align: center"></td>
                                <td style="text-align: left">{{$key}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('soluong'))}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('soluongcomat'))}}</td>
                                <td style="text-align: right">{{dinhdangsothapphan($model_gddvCT[$key]->sum('tongcong'),5)}}</td>
                                <td style="text-align: right">{{dinhdangsothapphan($model_gddvCT[$key]->sum('heso'),5)}}</td>
                                <td style="text-align: right">{{dinhdangsothapphan($model_gddvCT[$key]->sum('tongpc'),5)}}</td>

                                @foreach($a_phucap as $key1=>$val)
                                    <td>{{dinhdangsothapphan($model_gddvCT[$key]->sum($key1),5)}}</td>
                                @endforeach

                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stbhxh_dv')+$model_gddvCT[$key]->sum('stbhyt_dv')+$model_gddvCT[$key]->sum('stkpcd_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stbhtn_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('ttl') + $model_gddvCT[$key]->sum('ttbh_dv'))}}</td>
                            </tr>
                        @endforeach
                @endforeach
        @endforeach



        <?php

        $model_hcsn = $model->where('maphanloai','<>','KVXP');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','GD');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','QLNN');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DDT');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DOANTHE');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DANG');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','LVXH');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','LVCT');
        $model_hcsnT = $model_hcsn->groupby('tencongtac');

        //$model_hcsn = $modelhcsn;
        //$model_hcsnT = $modelhcsn->groupby('tencongtac');
        $a_plcongtac = array_column($model_hcsn->toarray(),'mact' , 'tencongtac');

        ?>
        <tr style="font-weight: bold;">
            <td>II</td>
            <td style="font-weight: bold;text-align: left">Tổng HCSN chưa có GD</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hcsn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhxh_dv')+$model_hcsn->sum('stbhyt_dv')+$model_hcsn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('ttl') + $model_hcsn->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhxh_dv')+$model_hcsnT[$key]->sum('stbhyt_dv')+$model_hcsnT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttl') + $model_hcsnT[$key]->sum('ttbh_dv'))}}</td>
            </tr>
        @endforeach
        <?php

        $model_hcsn = $model->where('maphanloai','<>','KVXP');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','GD');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','QLNN');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DDT');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DOANTHE');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DANG');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','LVXH');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','LVCT');
        $model_hcsnpl = $model_hcsn->groupby('tenlinhvuchoatdong');

        //$model_hcsn = $modelhcsn;
        //$model_hcsnpl = $modelhcsn->groupby('tenlinhvuchoatdong');
        //dd($model_hcsnpl->toarray());
        //$a_pldv = array_column($model_hcsn->toarray(),'maphanloai' , 'tenphanloai');
        $a_pldv = array_column($model_hcsn->toarray(),'linhvuchoatdong' , 'tenlinhvuchoatdong');
        $stt = 0;
        ?>
        @foreach($a_pldv as $key=>$val)
            <?php $stt ++;
            $model_hcsnpl = $model_hcsn->where('linhvuchoatdong',$a_pldv[$key]);
            $model_hcsnplCT = $model_hcsnpl->groupby('tencongtac');
            $a_plcongtac = array_column($model_hcsnpl->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money">
                <td>{{$stt}}</td>
                <td style="font-weight: bold;text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhxh_dv')+$model_hcsnpl->sum('stbhyt_dv')+$model_hcsnpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('ttl') + $model_hcsnpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv')+$model_hcsnplCT[$key]->sum('stbhyt_dv')+$model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttl') + $model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                </tr>

            @endforeach
            <?php
            $ttdv = 0;
            $model_hcsndv = $model_hcsnpl->groupby('tendv');
            //dd($model_hcsndv->toarray());
            $a_donvi = array_column($model_hcsnpl->toarray(),'madv' , 'tendv');

            ?>
            @foreach($a_donvi as $keydv=>$val)
                <?php $ttdv ++;

                    $model_hcsndvCT= $model_hcsnpl->where('tendv',$keydv)->groupby('tencongtac');
                    $a_plcongtac = array_column($model_hcsnpl->where('tendv',$keydv)->toarray(),'mact' , 'tencongtac');
                ?>
                <tr class="money" style="font-weight: bold">
                    <td style="text-align: center">{{$stt.'.'.$ttdv}}</td>
                    <td style="text-align: left">{{$keydv}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsndv[$keydv]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhxh_dv')+$model_hcsndv[$keydv]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttl') + $model_hcsndv[$keydv]->sum('ttbh_dv'))}}</td>
                </tr>
                @foreach($a_plcongtac as $key=>$val)
                    <tr class="money">
                        <td style="text-align: center"></td>
                        <td style="text-align: left">{{$key}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluong'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongcong'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_hcsndvCT[$key]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhxh_dv')+$model_hcsndvCT[$key]->sum('stbhyt_dv')+$model_hcsndvCT[$key]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('ttl') + $model_hcsndvCT[$key]->sum('ttbh_dv'))}}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <?php
        $model_hcsn = $modelqlnn;
        //$model_hcsn = $model_hcsn->where('linhvuchoatdong','DDT');
        $model_hcsnT = $model_hcsn->groupby('tencongtac');
        $a_plcongtac = array_column($model_hcsn->toarray(),'mact' , 'tencongtac');
        ?>
        <tr style="font-weight: bold;">
            <td>III</td>
            <td style="font-weight: bold;text-align: left">QLNN, Đảng, ĐT</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hcsn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhxh_dv')+$model_hcsn->sum('stbhyt_dv')+$model_hcsn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('ttl') + $model_hcsn->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhxh_dv')+$model_hcsnT[$key]->sum('stbhyt_dv')+$model_hcsnT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttl') + $model_hcsnT[$key]->sum('ttbh_dv'))}}</td>
            </tr>
        @endforeach
        <?php
        $model_hcsn = $modelqlnn;
        //$model_hcsn = $model_hcsn->where('linhvuchoatdong','DDT');
        $model_hcsnpl = $model_hcsn->groupby('tenlinhvuchoatdong');
        //dd($model_hcsnpl->toarray());
        //$a_pldv = array_column($model_hcsn->toarray(),'maphanloai' , 'tenphanloai');
        $a_pldv = array_column($model_hcsn->toarray(),'linhvuchoatdong' , 'tenlinhvuchoatdong');
        $stt = 0;
        ?>
        @foreach($a_pldv as $key=>$val)
            <?php $stt ++;
            $model_hcsnpl = $model_hcsn->where('linhvuchoatdong',$a_pldv[$key]);
            $model_hcsnplCT = $model_hcsnpl->groupby('tencongtac');
            $a_plcongtac = array_column($model_hcsnpl->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money">
                <td>{{$stt}}</td>
                <td style="font-weight: bold;text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhxh_dv')+$model_hcsnpl->sum('stbhyt_dv')+$model_hcsnpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('ttl') + $model_hcsnpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv')+$model_hcsnplCT[$key]->sum('stbhyt_dv')+$model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttl') + $model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                </tr>

            @endforeach
            <?php
            $ttdv = 0;
            $model_hcsndv = $model_hcsnpl->groupby('tendv');
            //dd($model_hcsndv->toarray());
            $a_donvi = array_column($model_hcsnpl->toarray(),'madv' , 'tendv');
            $a_plcongtac = array_column($model_hcsnpl->toarray(),'mact' , 'tencongtac');
            ?>
            @foreach($a_donvi as $keydv=>$val)
                <?php $ttdv ++;
                    $model_hcsndvCT= $model_hcsnpl->where('tendv',$keydv)->groupby('tencongtac');
                    $a_plcongtac = array_column($model_hcsnpl->where('tendv',$keydv)->toarray(),'mact' , 'tencongtac');
                ?>
                <tr class="money" style="font-weight: bold">
                    <td style="text-align: center">{{$stt.'.'.$ttdv}}</td>
                    <td style="text-align: left">{{$keydv}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsndv[$keydv]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhxh_dv')+$model_hcsndv[$keydv]->sum('stbhyt_dv')+$model_hcsndv[$keydv]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttl') + $model_hcsndv[$keydv]->sum('ttbh_dv'))}}</td>
                </tr>
                @foreach($a_plcongtac as $key=>$val)
                    <tr class="money">
                        <td style="text-align: center"></td>
                        <td style="text-align: left">{{$key}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluong'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongcong'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_hcsndvCT[$key]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhxh_dv')+$model_hcsndvCT[$key]->sum('stbhyt_dv')+$model_hcsndvCT[$key]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsndvCT[$key]->sum('ttl') + $model_hcsndvCT[$key]->sum('ttbh_dv'))}}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr style="font-weight: bold;">
            <td>IV</td>
            <td style="font-weight: bold;text-align: left">Khối Xã, phường, thị trấn</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_xp->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_xp->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_xp->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stbhxh_dv')+$model_xp->sum('stbhyt_dv')+$model_xp->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('ttl') + $model_xp->sum('ttbh_dv'))}}</td>
        </tr>        
        <?php
        $ttdv = 0;
        $model_hcsndv = $model_xp->groupby('tendv');
        //dd($model_hcsndv->toarray());
        $a_donvi = array_column($model_xp->toarray(),'madv' , 'tendv');
        $a_plcongtac = array_column($model_xp->toarray(),'mact' , 'tencongtac');
        ?>
        @foreach($a_donvi as $keydv=>$val)
            <?php $ttdv ++;
                $model_hcsndvCT= $model_xp->where('tendv',$keydv)->groupby('tencongtac');
                $a_plcongtac = array_column($model_xp->where('tendv',$keydv)->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money" style="font-weight: bold">
                <td style="text-align: center">{{$ttdv}}</td>
                <td style="text-align: left">{{$keydv}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_hcsndv[$keydv]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhxh_dv')+$model_hcsndv[$keydv]->sum('stbhyt_dv')+$model_hcsndv[$keydv]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttl') + $model_hcsndv[$keydv]->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluong'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndvCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongcong'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndvCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsndvCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhxh_dv')+$model_hcsndvCT[$key]->sum('stbhyt_dv')+$model_hcsndvCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndvCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndvCT[$key]->sum('ttl') + $model_hcsndvCT[$key]->sum('ttbh_dv'))}}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="font-weight: bold;">
            <td>V</td>
            <td style="font-weight: bold;text-align: left">Hoạt động phí HĐND</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('tongcong')+$model_kn->sum('tongcong')),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('heso')+$model_kn->sum('heso')),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('tongpc')+$model_kn->sum('tongpc')),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan(($model_hdnd->sum($key)+$model_kn->sum($key)),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhxh_dv')+$model_kn->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhtn_dv')+$model_kn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('ttl')+$model_kn->sum('ttl') + $model_hdnd->sum('ttbh_dv')+$model_kn->sum('ttbh_dv'))}}</td>
        </tr>
        <tr >
            <td>1</td>
            <td style="text-align: left">SH phí ĐB HĐND</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hdnd->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhxh_dv')+$model_hdnd->sum('stbhyt_dv')+$model_hdnd->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('ttl') + $model_hdnd->sum('ttbh_dv'))}}</td>
        </tr>
        <tr >
            <td>2</td>
            <td style="text-align: left">Kiêm nhiệm</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_kn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stbhxh_dv')+$model_kn->sum('stbhyt_dv')+$model_kn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('ttl') + $model_kn->sum('ttbh_dv'))}}</td>
        </tr>
        <tr >
            <td>3</td>
            <td style="text-align: left">Chuyên trách</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangso(0)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>VI</td>
            <td style="font-weight: bold;text-align: left">PC trách nhiệm cấp ủy</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('soluong'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_uv->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stbhxh_dv')+$model_uv->sum('stbhyt_dv')+$model_uv->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('ttl') + $model_uv->sum('ttbh_dv'))}}</td>
        </tr>

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', ngày ... tháng ... năm ...'}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="35%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="35%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="35%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop