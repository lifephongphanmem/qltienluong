@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: right;">
                <b>Biểu số 2e</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>TỔNG HỢP KINH PHÍ TĂNG THEO NGHỊ ĐỊNH SỐ 33/2023/NĐ-CP - NGƯỜI HOẠT ĐỘNG KHÔNG CHUYÊN TRÁCH Ở CẤP XÃ, Ở THÔN, TỔ DÂN PHỐ</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
                    tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</p>
            </td>
        </tr>
    </table>
    <table id="data_body" width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <tr>
            <th style="width: 2%">STT</th>
            <th>CHỈ TIÊU</th>
            <th style="width: 8%">TỔNG ĐƠN VỊ HÀNH CHÍNH CẤP XÃ, THÔN </th>
            <th style="width: 8%">MỨC KHOÁN QUỸ PHỤ CẤP 1 THÁNG THEO NGHỊ ĐỊNH 34/2019/NĐ-CP</th>
            <th style="width: 8%">MỨC KHOÁN QUỸ PHỤ CẤP 1 THÁNG THEO NGHỊ ĐỊNH 33/2023/NĐ-CP</th>
            <th style="width: 8%">KHOÁN QUỸ PHỤ CẤP 01 THÁNG THEO NGHỊ ĐỊNH 34 (LƯƠNG 1,49)</th>
            <th style="width: 8%">KHOÁN QUỸ PHỤ CẤP 01 THÁNG THEO NGHỊ ĐỊNH 33 (LƯƠNG 1,49)</th>
            <th style="width: 8%">CHÊNH LỆCH KINH PHÍ KHOÁN QUỸ PHỤ CẤP NĂM 2023</th>
        </tr>

        <tr>
            <td>A</td>
            <td>B</td>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4= 1x2x1,49</td>
            <td>5=1x3x1,49</td>
            <td>6=(5-4)*5T</td>
        </tr>
        <tr style="font-weight: bold">
            <td></td>
            <td>TỔNG SỐ</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['tdv']) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['mk']) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['mk2']) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($a_It['quyluong34']) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($a_It['quyluong33']) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($a_It['tong']) }}</td>

        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: right; {{ isset($dulieu['style']) ? $dulieu['style'] : '' }}">
                <td style=" text-align: center">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left">{{ $dulieu['noidung'] }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['solieu']['tdv']) }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['solieu']['mk'], 3) }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['solieu']['mk2'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['quyluong34'], 0, $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['quyluong33'], 0, $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['tong'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">{{ $m_dv->cdlanhdao }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ '' }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
        </tr>
    </table>

@stop
