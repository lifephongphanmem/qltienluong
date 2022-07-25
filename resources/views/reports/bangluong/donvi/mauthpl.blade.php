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
                    BẢNG TỔNG HỢP TIỀN LƯƠNG, PHỤ CẤP, TRỢ CẤP
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
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Phân loại cán bộ</th>
            <th style="width: 3%;" rowspan="2">Số người</th>
            <th style="width: 8%;" rowspan="2">Tổng tiền lương hoặc phụ cấp</th>
            <th colspan="{{$thongtin['col']}}">Các loại phụ cấp</th>
            <th style="width: 8%;" rowspan="2">Trợ cấp, phụ cấp kiêm nhiệm</th>
            <th style="width: 8%;" rowspan="2">Tổng tiền lương, phụ cấp và trợ cấp kiêm nhiệm</th>
            <th style="width: 8%;" rowspan="2">Các khoản khấu trừ</th>
            <th style="width: 8%;" rowspan="2">Tiền lương, phụ cấp còn lại</th>
            <th style="width: 5%;" rowspan="2">Ghi chú</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            @foreach($a_phucap as $key=>$val)
                <th>{!!$val!!}</th>
            @endforeach
        </tr>

        <tr>
            @for($i=1;$i<=9 + $thongtin['col'];$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        </thead>

        <?php $i=1; ?>

        @foreach($model as $ct)
            <tr>
                <td>{{$i++}}</td>
                <td style="text-align: left">{{$ct->tenct}}</td>
                <td style="text-align: center">{{$ct->soluong}}</td>
                <td>{{dinhdangso($ct->ttl)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangso($ct->$key)}}</td>
                @endforeach
                <td>{{dinhdangso($ct->ttl_kn)}}</td>
                <td>{{dinhdangso($ct->ttl_kn + $ct->ttl)}}</td>
                <td>{{dinhdangso($ct->ttbh)}}</td>
                <td>{{dinhdangso($ct->luongtn)}}</td>
                <td></td>
            </tr>
        @endforeach

        <tr style="font-weight: bold; text-align: center;">
            <td colspan="2">Tổng cộng</td>
            <td style="text-align: center">{{dinhdangso($model->sum('soluong'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach

            <td class="money">{{dinhdangso($model->sum('ttl_kn'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl_kn') + $model->sum('ttl'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
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