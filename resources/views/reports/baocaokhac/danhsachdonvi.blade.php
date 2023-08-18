@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_donvi['tendv'] }}</b>
            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_donvi->maqhns }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                DANH SÁCH ĐƠN VỊ CẤP DƯỚI
            </td>
        </tr>

    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th rowspan="2" style="width: 2%;">S</br>T</br>T</th>
                <th rowspan="2">TÊN ĐƠN VỊ</th>
                <th colspan="2">ĐƠN VỊ CHỦ QUẢN</th>
                <th rowspan="2" style="width: 5%;">TRẠNG THÁI</th>
            </tr>
            <tr class="text-center">               
                <th style="width: 15%;">THÔNG TIN ĐƠN VỊ</th>
                <th style="width: 15%;">NĂM NGÂN SÁCH {{$inputs['namns']}}</th>
            </tr>
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td colspan="4">TỔNG CỘNG ({{ dinhdangso(count($model)) }} ĐƠN VỊ)</td>
           
            
        </tr>
        <?php $i = 1; ?>
        @foreach ($model_phanloai as $phanloai)
            <?php
            $j = 1;
            $model_donvi = $model->where('maphanloai', $phanloai->maphanloai);
            ?>
            @if (count($model_donvi) > 0)
                <tr class="font-weight-bold">
                    <td>{{ convert2Roman($i++) }}</td>
                    <td colspan="4">{{ $phanloai->tenphanloai . ' (' . dinhdangso(count($model_donvi)) . ' ĐƠN VỊ)' }}</td>                    
                   
                </tr>
                @foreach ($model_donvi as $donvi)
                    <tr>
                        <td class="text-center">{{ $j++ }}</td>
                        <td>{{ $donvi->tendv }}</td>                       
                        <td>{{$donvi->tencqcq}}</td>
                        <td>{{$donvi->tencqcq_ql}}</td>
                        <td>{{$donvi->trangthai == 'TD'? 'Tạm dừng theo dõi':''}}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="60%"></td>
            <td style="text-align: center; font-style: italic" width="40%">
                {{ $m_donvi->diadanh . ', ' . Date2Str(null) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['lanhdao'] }}</td>
        </tr>
    </table>
@stop
