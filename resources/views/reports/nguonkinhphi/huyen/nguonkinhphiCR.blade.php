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
                NGUỒN KINH PHÍ THỰC HIỆN NĂM {{$nam}}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Đơn vị</th>
            <th style="width: 3%;" rowspan="3">BC được giao</th>
            <th style="width: 3%;" rowspan="3">BC có mặt</th>
            <th colspan="{{$col + 8}}">Hệ số lương, p/cấp và các khoản đ/góp</th>
            <th rowspan="3">Tổng quỹ lương {{$nam}}</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th rowspan="2">Hệ số lương theo NĐ 72/2018/NĐ CP (chưa tính nâng lương {{$nam}})</th>
            <th rowspan="2">Lương theo ngạch bậc chức vụ</th>
            <th rowspan="2">Tổng các khoản P/cấp</th>
            {{-- <th colspan="{{$col}}">Trong đó</th> --}}
            <th rowspan="2">Các khoản đ/góp BHXH,YT KPCĐ, BHTN</th>
            <th colspan="4">Trong đó</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            @foreach($a_phucap as $key=>$val)
                <th >{!!$val!!}</th>
            @endforeach
            <th>BHXH</th>
            <th>BHYT</th>
            <th>KPCĐ</th>
            <th>Bảo hiểm thất nghiệp</th>
        </tr>
        <tr style="font-weight: bold;" class="money">
            <td></td>
            <td style="font-weight: bold; text-align: center">TỔNG SỐ</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_th->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_th->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_th->sum('luongtn') + $model_th->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($model_th as $ct)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$ct->tencongtac}}</td>
                <td style="text-align: right">{{dinhdangso($ct->soluonggiao)}}</td>
                <td style="text-align: right">{{dinhdangso($ct->soluongcomat)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->heso,5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($ct->tongpc,5)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                @endforeach

                <td>{{dinhdangso($ct->ttbh_dv)}}</td>
                <td>{{dinhdangso($ct->stbhxh_dv)}}</td>
                <td>{{dinhdangso($ct->stbhyt_dv)}}</td>
                <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                <td>{{dinhdangso($ct->stbhtn_dv)}}</td>
                <td>{{dinhdangso($ct->ttbh_dv + $ct->luongtn)}}</td>
            </tr>
        @endforeach

        <?php
            $model_gd = $model->where('linhvuchoatdong','GD');
            $model_gdT = $model_gd->groupby('tencongtac');
            $a_plcongtac = array_column($model_gd->toarray(),'mact' , 'tencongtac');
        ?>
        <tr style="font-weight: bold;">
            <td>I</td>
            <td style="font-weight: bold;text-align: left">Sự nghiệp giáo dục</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_gd->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_gd->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_gd->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_gd->sum('luongtn') + $model_gd->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdT[$key]->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_gdT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_gdT[$key]->sum('ttbh_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_gdT[$key]->sum('ttbh_dv') + $model_gdT[$key]->sum('luongtn'))}}</td>
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
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_gdpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_gdpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('ttbh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stbhxh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stbhyt_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_gdpl->sum('luongtn') + $model_gdpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_gdplCT[$key]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_gdplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_gdplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_gdplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_gdplCT[$key]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_gdplCT[$key]->sum('ttbh_dv') + $model_gdplCT[$key]->sum('luongtn'))}}</td>
                </tr>

            @endforeach
                <?php
                    $ttdv = 0;
                    $model_gddv = $model_gdpl->groupby('tendv');
                    //dd($model_gddv->toarray());
                    $a_donvi = array_column($model_gdpl->toarray(),'madv' , 'tendv');
                    $a_plcongtac = array_column($model_gdpl->toarray(),'mact' , 'tencongtac');
                ?>
                @foreach($a_donvi as $keydv=>$val)
                    <?php $ttdv ++;
                    $model_gddvCT= $model_gdpl->where('tendv',$keydv)->groupby('tencongtac');
                    $a_plcongtac = array_column($model_gdpl->where('tendv',$keydv)->toarray(),'mact' , 'tencongtac');
                    ?>
                    <tr class="money" style="font-weight: bold">
                        <td style="text-align: center">{{$stt.'.'.$ttdv}}</td>
                        <td style="text-align: left">{{$keydv}}</td>
                        <td style="text-align: right">{{dinhdangso($model_gddv[$keydv]->sum('soluonggiao'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_gddv[$keydv]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('tonghs'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_gddv[$keydv]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_gddv[$keydv]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_gddv[$keydv]->sum('ttbh_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stbhxh_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stbhyt_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_gddv[$keydv]->sum('ttbh_dv') + $model_gddv[$keydv]->sum('luongtn'))}}</td>
                    </tr>
                        @foreach($a_plcongtac as $key=>$val)
                            <tr class="money">
                                <td style="text-align: center"></td>
                                <td style="text-align: left">{{$key}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('soluonggiao'))}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('soluongcomat'))}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('tonghs'))}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('heso'))}}</td>
                                <td style="text-align: right">{{dinhdangso($model_gddvCT[$key]->sum('tongpc'))}}</td>

                                @foreach($a_phucap as $key1=>$val)
                                    <td>{{dinhdangso($model_gddvCT[$key]->sum($key1))}}</td>
                                @endforeach

                                <td>{{dinhdangso($model_gddvCT[$key]->sum('ttbh_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stbhxh_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stbhyt_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stkpcd_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('stbhtn_dv'))}}</td>
                                <td>{{dinhdangso($model_gddvCT[$key]->sum('ttbh_dv') + $model_gddvCT[$key]->sum('luongtn'))}}</td>
                            </tr>
                        @endforeach
                @endforeach
        @endforeach



        <?php
        $model_hcsn = $model->where('linhvuchoatdong','<>','GD');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','HCSN');
        $model_hcsnT = $model_hcsn->groupby('tencongtac');
        $a_plcongtac = array_column($model_hcsn->toarray(),'mact' , 'tencongtac');

        ?>
        <tr style="font-weight: bold;">
            <td>II</td>
            <td style="font-weight: bold;text-align: left">Tổng HCSN chưa có GD</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hcsn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('luongtn') + $model_hcsn->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttbh_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttbh_dv') + $model_hcsnT[$key]->sum('luongtn'))}}</td>
            </tr>
        @endforeach
        <?php
        $model_hcsn = $model->where('linhvuchoatdong','<>','GD');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','HCSN');
        $model_hcsnpl = $model_hcsn->groupby('tenphanloai');
        //dd($model_hcsnpl->toarray());
        $a_pldv = array_column($model_hcsn->toarray(),'maphanloai' , 'tenphanloai');
        $stt = 0;
        ?>
        @foreach($a_pldv as $key=>$val)
            <?php $stt ++;
            $model_hcsnpl = $model_hcsn->where('maphanloai',$a_pldv[$key]);
            $model_hcsnplCT = $model_hcsnpl->groupby('tencongtac');
            $a_plcongtac = array_column($model_hcsnpl->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money">
                <td>{{$stt}}</td>
                <td style="font-weight: bold;text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('ttbh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhxh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhyt_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('luongtn') + $model_hcsnpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv') + $model_hcsnplCT[$key]->sum('luongtn'))}}</td>
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
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsndv[$keydv]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttbh_dv') + $model_hcsndv[$keydv]->sum('luongtn'))}}</td>
                </tr>
                @foreach($a_plcongtac as $key=>$val)
                    <tr class="money">
                        <td style="text-align: center"></td>
                        <td style="text-align: left">{{$key}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluonggiao'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tonghs'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhyt_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv') + $model_hcsnplCT[$key]->sum('luongtn'))}}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <?php
        $model_hcsn = $model->where('linhvuchoatdong','QLNN')
                ->where('linhvuchoatdong','DDT');
        $model_hcsnT = $model_hcsn->groupby('tencongtac');
        $a_plcongtac = array_column($model_hcsn->toarray(),'mact' , 'tencongtac');
        ?>
        <tr style="font-weight: bold;">
            <td>III</td>
            <td style="font-weight: bold;text-align: left">QLNN, Đảng, ĐT</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hcsn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hcsn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hcsn->sum('luongtn') + $model_hcsn->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <tr class="money">
                <td style="text-align: center"></td>
                <td style="text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttbh_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_hcsnT[$key]->sum('ttbh_dv') + $model_hcsnT[$key]->sum('luongtn'))}}</td>
            </tr>
        @endforeach
        <?php
        $model_hcsn = $model->where('linhvuchoatdong','<>','GD');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','KVXP');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','QLNN');
        $model_hcsn = $model_hcsn->where('linhvuchoatdong','<>','DDT');
        $model_hcsnpl = $model_hcsn->groupby('tenphanloai');
        //dd($model_hcsnpl->toarray());
        $a_pldv = array_column($model_hcsn->toarray(),'maphanloai' , 'tenphanloai');
        $stt = 0;
        ?>
        @foreach($a_pldv as $key=>$val)
            <?php $stt ++;
            $model_hcsnpl = $model_hcsn->where('maphanloai',$a_pldv[$key]);
            $model_hcsnplCT = $model_hcsnpl->groupby('tencongtac');
            $a_plcongtac = array_column($model_hcsnpl->toarray(),'mact' , 'tencongtac');
            ?>
            <tr class="money">
                <td>{{$stt}}</td>
                <td style="font-weight: bold;text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_hcsnpl->sum('tongpc'),5)}}</td>
                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_hcsnpl->sum($key),5)}}</td>
                @endforeach
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('ttbh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhxh_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhyt_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stkpcd_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('stbhtn_dv'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_hcsnpl->sum('luongtn') + $model_hcsnpl->sum('ttbh_dv'))}}</td>
            </tr>
            @foreach($a_plcongtac as $key=>$val)
                <tr class="money">
                    <td style="text-align: center"></td>
                    <td style="text-align: left">{{$key}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv') + $model_hcsnplCT[$key]->sum('luongtn'))}}</td>
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
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_hcsndv[$keydv]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_hcsndv[$keydv]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_hcsndv[$keydv]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_hcsndv[$keydv]->sum('ttbh_dv') + $model_hcsndv[$keydv]->sum('luongtn'))}}</td>
                </tr>
                @foreach($a_plcongtac as $key=>$val)
                    <tr class="money">
                        <td style="text-align: center"></td>
                        <td style="text-align: left">{{$key}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluonggiao'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_hcsnplCT[$key]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tonghs'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_hcsnplCT[$key]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_hcsnplCT[$key]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhxh_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhyt_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_hcsnplCT[$key]->sum('ttbh_dv') + $model_hcsnplCT[$key]->sum('luongtn'))}}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr style="font-weight: bold;">
            <td>IV</td>
            <td style="font-weight: bold;text-align: left">Hoạt động phí HĐND</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('tonghs')+$model_kn->sum('tonghs')),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('heso')+$model_kn->sum('heso')),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan(($model_hdnd->sum('tongpc')+$model_kn->sum('tongpc')),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan(($model_hdnd->sum($key)+$model_kn->sum($key)),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('ttbh_dv')+$model_kn->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhxh_dv')+$model_kn->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhyt_dv')+$model_kn->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stkpcd_dv')+$model_kn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhtn_dv')+$model_kn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('luongtn') + $model_hdnd->sum('ttbh_dv')+$model_kn->sum('luongtn')+$model_kn->sum('ttbh_dv'))}}</td>
        </tr>
        <tr >
            <td>1</td>
            <td style="text-align: left">SH phí ĐB HĐND</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_hdnd->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_hdnd->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_hdnd->sum('luongtn') + $model_hdnd->sum('ttbh_dv'))}}</td>
        </tr>
        <tr >
            <td>2</td>
            <td style="text-align: left">Kiêm nhiệm</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_kn->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_kn->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_kn->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_kn->sum('luongtn')+$model_kn->sum('ttbh_dv'))}}</td>
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
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
            <td style="text-align: right">{{dinhdangso(0)}}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td>IV</td>
            <td style="font-weight: bold;text-align: left">PC trách nhiệm cấp ủy</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_uv->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_uv->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_uv->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_uv->sum('luongtn')+$model_uv->sum('ttbh_dv'))}}</td>
        </tr>



        <?php
        $model_xpT = $model_xp->groupby('tencongtac');
        $a_plcongtac = array_column($model_xp->toarray(),'mact' , 'tencongtac');
            $ttxp = 0;
        ?>
        <tr style="font-weight: bold;">
            <td>V</td>
            <td style="font-weight: bold;text-align: left">Xã, phường</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_xp->sum('tonghs'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_xp->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($model_xp->sum('tongpc'),5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model_xp->sum($key),5)}}</td>
            @endforeach
            <td style="text-align: right">{{dinhdangso($model_xp->sum('ttbh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stbhxh_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stbhyt_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stkpcd_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('stbhtn_dv'))}}</td>
            <td style="text-align: right">{{dinhdangso($model_xp->sum('luongtn') + $model_xp->sum('ttbh_dv'))}}</td>
        </tr>
        @foreach($a_plcongtac as $key=>$val)
            <?php $ttxp ++;
            $ttdv = 0;
            $model_xpdv = $model_xpT[$key]->groupby('tendv');
            //dd($model_xpT[$key]->toarray());
            $a_donvi = array_column($model_xpT[$key]->toarray(),'madv' , 'tendv');
            $a_plcongtac = array_column($model_xpT[$key]->toarray(),'mact' , 'tencongtac');
            //dd($a_donvi);
            ?>
            <tr class="money" style="font-weight: bold">
                <td style="text-align: center">{{$ttxp}}</td>
                <td style="font-weight: bold; text-align: left">{{$key}}</td>
                <td style="text-align: right">{{dinhdangso($model_xpT[$key]->sum('soluonggiao'))}}</td>
                <td style="text-align: right">{{dinhdangso($model_xpT[$key]->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_xpT[$key]->sum('tonghs'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_xpT[$key]->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($model_xpT[$key]->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key1=>$val)
                    <td>{{dinhdangsothapphan($model_xpT[$key]->sum($key1),5)}}</td>
                @endforeach

                <td>{{dinhdangso($model_xpT[$key]->sum('ttbh_dv'))}}</td>
                <td>{{dinhdangso($model_xpT[$key]->sum('stbhxh_dv'))}}</td>
                <td>{{dinhdangso($model_xpT[$key]->sum('stbhyt_dv'))}}</td>
                <td>{{dinhdangso($model_xpT[$key]->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($model_xpT[$key]->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($model_xpT[$key]->sum('ttbh_dv') + $model_xpT[$key]->sum('luongtn'))}}</td>
            </tr>


            @foreach($a_donvi as $keydv=>$val)
                <?php $ttdv ++;
                $model_xpdvCT= $model_xpT[$key]->where('tendv',$keydv)->groupby('tencongtac');
                ?>
                <tr class="money" >
                    <td style="text-align: center">{{$ttdv}}</td>
                    <td style="text-align: left">{{$keydv}}</td>
                    <td style="text-align: right">{{dinhdangso($model_xpdv[$keydv]->sum('soluonggiao'))}}</td>
                    <td style="text-align: right">{{dinhdangso($model_xpdv[$keydv]->sum('soluongcomat'))}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_xpdv[$keydv]->sum('tonghs'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_xpdv[$keydv]->sum('heso'),5)}}</td>
                    <td style="text-align: right">{{dinhdangsothapphan($model_xpdv[$keydv]->sum('tongpc'),5)}}</td>

                    @foreach($a_phucap as $key1=>$val)
                        <td>{{dinhdangsothapphan($model_xpdv[$keydv]->sum($key1),5)}}</td>
                    @endforeach

                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('ttbh_dv'))}}</td>
                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('stbhxh_dv'))}}</td>
                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('stbhyt_dv'))}}</td>
                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('stkpcd_dv'))}}</td>
                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('stbhtn_dv'))}}</td>
                    <td>{{dinhdangso($model_xpdv[$keydv]->sum('ttbh_dv') + $model_xpdv[$keydv]->sum('luongtn'))}}</td>
                </tr>
                @foreach($a_plcongtac as $key=>$val)
                    <tr class="money" style="font-style: italic;">
                        <td style="text-align: center"></td>
                        <td style="text-align: left">{{$key}}</td>
                        <td style="text-align: right">{{dinhdangso($model_xpdvCT[$key]->sum('soluonggiao'))}}</td>
                        <td style="text-align: right">{{dinhdangso($model_xpdvCT[$key]->sum('soluongcomat'))}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_xpdvCT[$key]->sum('tonghs'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_xpdvCT[$key]->sum('heso'),5)}}</td>
                        <td style="text-align: right">{{dinhdangsothapphan($model_xpdvCT[$key]->sum('tongpc'),5)}}</td>

                        @foreach($a_phucap as $key1=>$val)
                            <td>{{dinhdangsothapphan($model_xpdvCT[$key]->sum($key1),5)}}</td>
                        @endforeach

                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('ttbh_dv'))}}</td>
                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('stbhxh_dv'))}}</td>
                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('stbhyt_dv'))}}</td>
                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_xpdvCT[$key]->sum('ttbh_dv') + $model_xpdvCT[$key]->sum('luongtn'))}}</td>
                    </tr>
                @endforeach
            @endforeach

        @endforeach



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