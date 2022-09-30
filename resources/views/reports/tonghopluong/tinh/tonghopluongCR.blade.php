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
        {{-- @foreach($model_th as $ct)
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
    @endforeach --}}
        @foreach($model_donvi as $k=>$ct)
        <?php $m_tonghop = $model_th->where('madvbc',$ct->madvbc) ?>
            <tr class="money">
                <td style="text-align: center">{{++$k}}</td>
                <td style="text-align: left">{{$ct->tendvbc}}</td>
                <td style="text-align: right">{{dinhdangso($m_tonghop->sum('soluong'))}}</td>
                <td style="text-align: right">{{dinhdangso($m_tonghop->sum('soluongcomat'))}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($m_tonghop->sum('tongcong'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($m_tonghop->sum('heso'),5)}}</td>
                <td style="text-align: right">{{dinhdangsothapphan($m_tonghop->sum('tongpc'),5)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($m_tonghop->sum($key),5)}}</td>
                @endforeach

                <td>{{dinhdangso($m_tonghop->sum('stbhxh_dv')+$m_tonghop->sum('stbhyt_dv')+$m_tonghop->sum('stkpcd_dv'))}}</td>
                <td>{{dinhdangso($m_tonghop->sum('stbhtn_dv'))}}</td>
                <td>{{dinhdangso($m_tonghop->sum('ttl') + $m_tonghop->sum('ttbh_dv'))}}</td>
            </tr>
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