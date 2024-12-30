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
                {{-- <b>Đơn vị: {{$m_dv['tendv']}}</b> --}}
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                {{-- <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b> --}}
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                NHẬT KÝ DỪNG/MỞ HOẠT ĐỘNG ĐƠN VỊ
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Từ ngày {{$inputs['tungay'] != ''?getDayVn($inputs['tungay']):'...'}} đến ngày {{getDayVn($inputs['denngay'])}}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 3%;">S</br>T</br>T</th>
            <th style="width: 18%;">Tên đơn vị</th>
            <th style="width: 8%;">Trạng thái</th>
            <th style="width: 8%;">Ngày tháng dừng/mở HĐ</th>
            <th style="width: 20%;">Ghi chú</th>
        </tr>


        <?php $i = 1; ?>
        @foreach ($a_dv as $key => $dv)
            <?php
            $stt = 1;
            $m_donvi = $model->where('macqcq', $key);
            ?>
            @if (count($m_donvi) > 0)
                <tr style="font-weight: bold;">
                    <td style="text-align: left">{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;" colspan="4">{{ $dv }}</td>
                </tr>

                @foreach ($m_donvi as $item)
                    <tr style=" text-align: left">
                        <td style="text-align: center">{{ $stt++ }}</td>

                        <td>{{ $a_madv[$item->madv] }}</td>

                        <td class="money text-center">{{ $a_trangthai[$item->action] }}</td>
                        <td class="money text-center">{{ getDayVn($item->ngaythang) }}</td>
                        <td class="money text-left">{{ $item->ghichu }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </table>

@stop
