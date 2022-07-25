@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">

        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                @if($thongtin['innoidung'])
                    {{$thongtin['noidung']}}
                @else
                    BẢNG TỔNG HỢP PHỤ CẤP, TRỢ CẤP
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 11px Times, serif;">
        <thead>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" >S</br>T</br>T</th>
            <th style="width: 12%;">Phân loại cán bộ, công chức</th>
            <th style="width: 3%;">Số người</th>
            <th style="width: 8%;">Tổng tiền lương hoặc phụ cấp</th>
            <th style="width: 8%;">Trợ cấp, phụ cấp kiêm nhiệm</th>
            <th style="width: 8%;">Tổng tiền lương, phụ cấp và trợ cấp kiêm nhiệm</th>
            <th style="width: 5%;">Ghi chú</th>
        </tr>

        <tr>
            @for($i=1;$i<=7;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        </thead>

        <?php $i=1; ?>

        @foreach($model as $key=>$val)
            <tr>
                <td>{{$i++}}</td>
                <td style="text-align: left">{{$val['tenct']}}</td>
                <td style="text-align: center">{{dinhdangso($val['soluong'])}}</td>
                <td>{{dinhdangso($val['ttl'])}}</td>
                <td>{{dinhdangso($val['ttl_kn'])}}</td>
                <td>{{dinhdangso($val['ttl_kn'] + $val['ttl'])}}</td>
                <td></td>
            </tr>
        @endforeach

        <tr style="font-weight: bold; text-align: center;">
            <td colspan="2">Tổng cộng</td>
            <td style="text-align: center">{{dinhdangso(array_column($model,'soluong'))}}</td>
            <td class="money">{{dinhdangso(array_column($model,'ttl'))}}</td>
            <td class="money">{{dinhdangso(array_column($model,'ttl_kn'))}}</td>
            <td class="money">{{dinhdangso(array_column($model,'ttl_kn') + array_column($model,'ttl'))}}</td>

            <td></td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="25%">Người lập bảng</td>
            <th style="text-align: center;" width="30%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="45%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="25%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="45%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="25%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="45%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>
@stop