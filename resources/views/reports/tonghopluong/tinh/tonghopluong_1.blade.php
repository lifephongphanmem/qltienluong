@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ $m_dv['tendv'] }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ $m_dv->maqhns }}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                TỔNG HỢP KINH PHÍ TIỀN LƯƠNG THÁNG {{ $thang }} NĂM {{ $nam }}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Đơn vị</th>
            <th style="width: 3%;" colspan="2">Biên chế</th>
            <th colspan="{{ $col + 5 }}">Hệ số lương, p/cấp và các khoản đ/góp</th>
            <th rowspan="3">Tổng lương tháng {{ $thang }}/{{ $nam }}</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 3%;" rowspan="2">Được cấp có thẩm quyền giao</th>
            <th style="width: 3%;" rowspan="2">Có mặt</th>
            <th rowspan="2">Tổng cộng </th>
            <th rowspan="2">Lương ngạch, bậc, CV</th>
            <th colspan="{{ $col + 1 }}">Trong đó</th>
            <th colspan="2">Các khoản đóng góp</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th>Tổng các khoản P/cấp</th>
            @foreach ($a_phucap as $key => $val)
                <th>{!! $val !!}</th>
            @endforeach
            <th>BHXH, YT, CĐ</th>
            <th>Thất nghiệp</th>
        </tr>
        <tr style="font-weight: bold;" class="money">
            <td></td>
            <td style="font-weight: bold; text-align: center">TỔNG SỐ</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('soluong')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('soluongcomat')) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model->sum('tongtl'), 5) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model->sum('heso'), 5) }}</td>
            <td style="text-align: right">{{ dinhdangsothapphan($model->sum('tongpc'), 5) }}</td>
            @foreach ($a_phucap as $key => $val)
                <td>{{ dinhdangsothapphan($model->sum($key), 5) }}</td>
            @endforeach
            <td style="text-align: right">
                {{ dinhdangso($model->sum('stbhxh_dv') + $model->sum('stbhyt_dv') + $model->sum('stkpcd_dv')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td style="text-align: right">{{ dinhdangso($model->sum('tongtl') + $model->sum('tongbh')) }}</td>
        </tr>
        @foreach ($model_donvi_bc as $k => $ct)
            <?php $m_tonghop = $model->where('madvbc', $ct->madvbc);?>
            <tr class="money" style="font-weight: bold;">
                <td style="text-align: center">{{ strtoupper(toAlpha(++$k)) }}</td>
                <td style="text-align: left">{{ $ct->tendvbc }}</td>
                <td style="text-align: right">{{ dinhdangso($m_tonghop->sum('soluong')) }}</td>
                <td style="text-align: right">{{ dinhdangso($m_tonghop->sum('soluongcomat')) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($m_tonghop->sum('tongtl'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($m_tonghop->sum('heso'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($m_tonghop->sum('tongpc'), 5) }}</td>

                @foreach ($a_phucap as $key => $val)
                    <td>{{ dinhdangsothapphan($m_tonghop->sum($key), 5) }}</td>
                @endforeach

                <td>{{ dinhdangso($m_tonghop->sum('stbhxh_dv') + $m_tonghop->sum('stbhyt_dv') + $m_tonghop->sum('stkpcd_dv')) }}
                </td>
                <td>{{ dinhdangso($m_tonghop->sum('stbhtn_dv')) }}</td>
                <td>{{ dinhdangso($m_tonghop->sum('tongtl') + $m_tonghop->sum('tongbh')) }}</td>
            </tr>          
            <?php $i = 1; ?>
            @foreach ($model_phanloai as $pl)

            <tr style="font-weight: bold;">
                <td style="text-align: center;">{{ convert2Roman($i++) }}</td>
                <td style="text-align: left;" colspan="{{ 11 + $col }}">{{ $pl->tenphanloai }}</td>
            </tr>
                <?php
                $j=1;
                $a_pl = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai));
                $phanloai = $m_tonghop->where('maphanloai',$pl->maphanloai);
                $donvi = $model_donvi->where('maphanloai',$pl->maphanloai);
                $stt=1;
                $a_dv = a_getelement($a_soluong,array('maphanloai'=>$pl->maphanloai));
                $a_khoipb = array_column($model_khoipb->where('maphanloai',$pl->maphanloai)->toarray(),'linhvuchoatdong','tenlinhvuc');

                $j=1;
                foreach($a_khoipb as $key=>$val){
                        $a_donvi = array_column($m_donvi->where('maphanloai',$pl->maphanloai)->where('linhvuchoatdong',$a_khoipb[$key])->where('madvbc',$ct->madvbc)->toarray(),'madv','tendv');
                    ?>
                <tr style="font-weight: bold;">
                    <td style="text-align: center;">{{$j++}}</td>
                    <td style="text-align: left;" colspan="{{ 11 + $col }}">{{ $key }}</td>
                </tr>
                <?php
                    $sttdv = 0;
                    foreach($a_donvi as $keydv=>$val) {
                        $sttdv ++;
                        $phanloaict = $m_pl->where('maphanloai',$pl->maphanloai)->where('linhvuchoatdong',$a_khoipb[$key])->where('madv',$a_donvi[$keydv]);
                        $solieu_dv=$m_tonghop->where('madv',$a_donvi[$keydv]);
                        // dd($a_phucap);
                        // dd($solieu_dv);
                        // dd($m_tonghop);
            ?>
                @if(count($solieu_dv) > 0 ) 
                <tr style="font-style: italic">
                    <td style="text-align: center;">{{ ($j-1).'.'.$sttdv }}</td>
                    <td style="text-align: left;">{{ $keydv }}</td>
                    <td style="text-align: right">{{ dinhdangso($solieu_dv->sum('soluong')) }}</td>
                <td style="text-align: right">{{ dinhdangso($solieu_dv->sum('soluongcomat')) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($solieu_dv->sum('tongtl'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($solieu_dv->sum('heso'), 5) }}</td>
                <td style="text-align: right">{{ dinhdangsothapphan($solieu_dv->sum('tongpc'), 5) }}</td>

                @foreach ($a_phucap as $key_pc => $val)
                    <td>{{ dinhdangsothapphan($solieu_dv->sum($key_pc), 5) }}</td>
                @endforeach

                <td>{{ dinhdangso($solieu_dv->sum('stbhxh_dv') + $solieu_dv->sum('stbhyt_dv') + $solieu_dv->sum('stkpcd_dv')) }}
                </td>
                <td>{{ dinhdangso($solieu_dv->sum('stbhtn_dv')) }}</td>
                <td>{{ dinhdangso($solieu_dv->sum('tongtl') + $solieu_dv->sum('tongbh')) }}</td>
                </tr>
                @endif
                <?php }}?>
            @endforeach
        @endforeach

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">
                {{ $m_dv->diadanh . ', ngày ... tháng ... năm ...' }}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{ $m_dv->cdketoan }}</th>
            <td style="text-align: center;" width="35%">{{ $m_dv['cdlanhdao'] }}</td>
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
            <td style="text-align: center;" width="35%">{{ $m_dv['nguoilapbieu'] }}</td>
            <td style="text-align: center;" width="30%">{{ $m_dv['ketoan'] }}</td>
            <td style="text-align: center;" width="35%">{{ $m_dv['lanhdao'] }}</td>
        </tr>
    </table>
@stop
