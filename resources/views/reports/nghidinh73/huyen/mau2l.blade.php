@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2l</b>
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
                <b>TỔNG HỢP KINH PHÍ GIẢM THEO NGHỊ ĐỊNH SỐ 34/2019/NĐ-CP - NGƯỜI HOẠT ĐỘNG KHÔNG CHUYÊN TRÁCH Ở CẤP XÃ</b>
            </td>
        </tr>
    </table>
    <table id="data_body" width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%">STT</th>
                <th rowspan="2">Chỉ tiêu</th>
                <th rowspan="2" style="width: 5%">TỔNG ĐƠN VỊ HÀNH CHÍNH CẤP XÃ</th>
                <th colspan="2">MỨC KHOÁN QUỸ PHỤ CẤP 1 THÁNG</th>
                <th rowspan="2" style="width: 5%">SỐ LƯỢNG CÁN BỘ KCT CÓ MẶT</th>
                <th colspan="2"> KHOÁN QUỸ PHỤ CẤP 1 THÁNG THEO NGHỊ ĐỊNH 24 (LƯƠNG 1,8)</th>
                <th rowspan="2" style="width: 5%">KHOÁN QUỸ PHỤ CẤP 1 THÁNG THEO NGHỊ ĐỊNH 34(LƯƠNG 1,8)</th>
                <th rowspan="2" style="width: 5%">QUỸ PHỤ CẤP GIẢM NĂM 2023</th>
            </tr>

            <tr>
                <th style="width: 5%">Theo Nghị định 29/2013/NĐ-CP</th>
                <th style="width: 5%">Theo Nghị định 34/2019/NĐ-CP</th>
                <th style="width: 5%">KHOÁN QUỸ PHỤ CẤP</th>
                <th style="width: 5%">BHXH (14%) CHO CÁN BỘ KCT CẤP XÃ</th>
            </tr>
            <tr>
                <td>A</td>
                <td>B</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5=1*2*1,8</td>
                <td>6=4*14%*1,8</td>
                <td>7=1*3*1,8</td>
                <td>8=(7-6-5)*6T</td>                
            </tr>
        </thead>

        @foreach ($ar_I as $dulieu)
            <tr style=" text-align: right">
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['soluongdonvi_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['qd92_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['qd34_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['tongcanbo_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['tongphucap_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['tongdonggop_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['tongquyluong_2l']) }}</td>
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['quyluonggiam_2l']) }}</td>                
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
