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
            TỔNG HỢP HỆ SỐ LƯƠNG VÀ PHỤ CẤP NĂM {{$nam}}
        </td>
    </tr>

</table>

<table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
        <th style="width: 12%;" rowspan="3">Đơn vị</th>
        <th style="width: 3%;" colspan="2">Biên chế</th>
        <th colspan="{{$col + 5}}">Hệ số  lương, p/cấp và các khỏan đ/góp</th>
        <th rowspan="3">Quỹ lương  {{$nam}}</th>
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
        <td style="text-align: right">{{dinhdangso($model_th->sum('soluonggiao'))}}</td>
        <td style="text-align: right">{{dinhdangso($model_th->sum('soluongcomat'))}}</td>
        <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tongcong'),5)}}</td>
        <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('heso'),5)}}</td>
        <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('tongpc'),5)}}</td>
        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangsothapphan($model_th->sum($key),5)}}</td>
        @endforeach
        <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('stbhxh_dv')+$model_th->sum('stbhyt_dv')+$model_th->sum('stkpcd_dv'),5)}}</td>
        <td style="text-align: right">{{dinhdangsothapphan($model_th->sum('stbhtn_dv'),5)}}</td>
        {{-- <td style="text-align: right">{{dinhdangso($model_th->sum('ttl') + $model_th->sum('ttbh_dv'))}}</td> --}}
        <td style="text-align: right">{{dinhdangso($model_th->sum('tongtienluong'))}}</td>
    </tr>
    @foreach($model_donvi as $key=>$donvi)
        <?php
            $m_ct = $model_th->where('madvbc',$donvi->madvbc);
        ?>
        <tr style="font-weight: bold" class="money">
            <td style="text-align: center">{{++$key}}</td>
            <td style="text-align: left">{{$donvi->tendvbc}}</td>
            <td style="text-align: right">{{dinhdangso($m_ct->sum('soluonggiao'))}}</td>
            <td style="text-align: right">{{dinhdangso($m_ct->sum('soluongcomat'))}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($m_ct->sum('tongcong'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($m_ct->sum('heso'),5)}}</td>
            <td style="text-align: right">{{dinhdangsothapphan($m_ct->sum('tongpc'),5)}}</td>

            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($m_ct->sum($key),5)}}</td>
            @endforeach

            <td>{{dinhdangsothapphan($m_ct->sum('stbhxh_dv')+$m_ct->sum('stbhyt_dv')+$m_ct->sum('stkpcd_dv'),5)}}</td>
            <td>{{dinhdangsothapphan($m_ct->sum('stbhtn_dv'),5)}}</td>
            {{-- <td>{{dinhdangso($m_ct->sum('ttl') + $m_ct->sum('ttbh_dv'))}}</td> --}}
            <td>{{dinhdangso($m_ct->sum('tongtienluong'))}}</td>
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