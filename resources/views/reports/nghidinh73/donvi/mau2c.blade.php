@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2c</b>
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
                <b>TỔNG HỢP KINH PHÍ TĂNG THÊM THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP XÃ, THÔN VÀ TỔ
                    DÂN PHỐ NĂM 2024</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: center; font-style: italic">{{ $m_thongtu->ghichu }}</p>
            </td>
        </tr>
    </table>
    <table id="data_body" width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <tr>
            <th style="width: 2%">STT</th>
            <th>CHỈ TIÊU</th>
            <th style="width: 8%">TỔNG ĐƠN VỊ HÀNH CHÍNH CẤP XÃ, THÔN </th>
            <th style="width: 8%">MỨC KHOÁN QUỸ PHỤ CẤP 1 THÁNG THEO NGHỊ ĐỊNH 33/2023/NĐ-CP</th>
            <th style="width: 8%">CHÊNH LỆCH KINH PHÍ KHOÁN QUỸ PHỤ CẤP THÁNG 07 NĂM 2024</th>
            <th style="width: 8%">CHÊNH LỆCH KINH PHÍ KHOÁN QUỸ PHỤ CẤP 05 THÁNG CUỐI NĂM 2024</th>
            <th style="width: 8%">CHÊNH LỆCH KINH PHÍ KHOÁN QUỸ PHỤ CẤP NĂM 2024</th>
        </tr>

        <tr>
            <td>A</td>
            <td>B</td>
            <td>1</td>
            <td>2</td>
            <td>3=1x2x1,8</td>
            <td>4=1x3x2,34</td>
            <td>5=(4-3)*6</td>
        </tr>
        <tr style="font-weight: bold">
            <td></td>
            <td>TỔNG SỐ</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['tdv']) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['mk']) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($a_It['muccu']) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($a_It['mucapdung']) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($a_It['chenhlech']) }}</td>
        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: right; {{ isset($dulieu['style']) ? $dulieu['style'] : '' }}">
                <td style=" text-align: center">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left">{{ $dulieu['noidung'] }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['solieu']['tdv']) }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['solieu']['mk'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['muccu'], 0, $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['mucapdung'], 0, $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['chenhlech'], 0, $inputs['donvitinh']) }}</td>
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
            <td style="text-align: center;" width="50%">{{ mb_strtoupper($m_dv->cdlanhdao) }}</td>
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
