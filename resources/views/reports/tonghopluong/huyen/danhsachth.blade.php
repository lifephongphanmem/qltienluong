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
                <b>Đơn vị: </b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: </b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                TỔNG HỢP DANH SÁCH ĐƠN VỊ TRÊN TOÀN HUYỆN
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight:bold">
                {{ $thang == 'all' ? '' : 'Tháng ' . $thang }} Năm {{ $nam }}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th rowspan="{{$thang=='all'?2:''}}" style="width: 2%;">STT</th>
            <th rowspan="{{$thang=='all'?2:''}}" style="width: 20%;">Tên đơn vị</th>
            @if ($thang == 'all')
                <th colspan="12">Tháng</th>
            @else
                <th style="width: 5%">Trạng thái</th>
            @endif

            <th rowspan="{{$thang=='all'?2:''}}" style="width: 5%">Ghi chú</th>
        </tr>
        @if ($thang == 'all')
            <tr style="padding-left: 2px;padding-right: 2px">
                <th>01</th>
                <th>02</th>
                <th>03</th>
                <th>04</th>
                <th>05</th>
                <th>06</th>
                <th>07</th>
                <th>08</th>
                <th>09</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
            </tr>
        @endif
        <?php $i = 1; ?>
        @if ($thang == 'all')
            @foreach ($model as $dv)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td style="text-align: left">{{ $dv->tendv }}</td>
                    <td class="{{ $dv->thang1 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang1 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang2 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang2 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang3 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang3 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang4 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang4 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang5 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang5 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang6 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang6 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang7 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang7 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang8 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang8 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang9 == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->thang9 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}
                    </td>
                    <td class="{{ $dv->thang10 == 'DAGUI' ? '' : 'text-danger' }}">
                        {{ $dv->thang10 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}</td>
                    <td class="{{ $dv->thang11 == 'DAGUI' ? '' : 'text-danger' }}">
                        {{ $dv->thang11 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}</td>
                    <td class="{{ $dv->thang12 == 'DAGUI' ? '' : 'text-danger' }}">
                        {{ $dv->thang12 == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}</td>
                    <td></td>
                </tr>
            @endforeach
        @else
            @foreach ($model as $dv)
            <?php $trangthai='thang'.$thang ?>
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $dv->tendv}}</td>
                    <td class="{{ $dv->$trangthai == 'DAGUI' ? '' : 'text-danger' }}">{{ $dv->$trangthai == 'DAGUI' ? 'Đã gửi' : 'Chưa gửi' }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif

    </table>
@stop
