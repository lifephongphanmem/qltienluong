@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2b</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
    </table>
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BẢNG TỔNG HỢP QUỸ TRỢ CẤP TĂNG THÊM
        NĂM 2024 CỦA CÁN BỘ XÃ, PHƯỜNG, THỊ TRẤN ĐÃ NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG TỪ NGÂN SÁCH NHÀ NƯỚC</p>
        <p style="text-align: center; font-style: italic">{{$m_thongtu->ghichu}}</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;padding-left: 2px;padding-right: 2px">STT</th>
            <th style="padding-left: 2px;padding-right: 2px">NỘI DUNG</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG SỐ NGƯỜI NGHỈ VIỆC HƯỞNG TRỢ CẤP HÀNG THÁNG ĐẾN
                01/07/2024</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ
                42/2023/NĐ-CP</th>            
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP 1 THÁNG THEO QUY ĐỊNH TẠI NGHỊ ĐỊNH SỐ
                75/2024/NĐ-CP</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">QUỸ TRỢ CẤP TĂNG THÊM THÁNG 7</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">BẢO HIỂM Y TẾ TĂNG THÊM THÁNG 7</th>
            <th style="width: 6%;padding-left: 2px;padding-right: 2px">TỔNG QUỸ TRỢ CẤP TĂNG THÊM NĂM 2024</th>
        </tr>

        <tr style="font-weight: bold; text-align: center">
            <td>A</td>
            <td>B</td>
            <td>1</td>
            <td>2</td>
            <td>3</td>           
            <td>4=3-2</td>
            <td>5=1x0.54x4,5%</td>
            <td>6=(4+5)x6T</td>
        </tr>

        <tr style="font-weight: bold;">
            <th></th>
            <th>TỔNG CỘNG</th>
            <td class="text-center">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'songuoi'))) }}</td>
            <td class="text-right">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'quy1'))) }}</td>
            <td class="text-right">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'quy2'))) }}</td>
            <td class="text-right">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'trocap'))) }}</td>
            <td class="text-right">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'baohiem'))) }}</td>
            <td class="text-right">{{ dinhdangsothapphan(array_sum(array_column($ar_I, 'tongquy'))) }}</td>            
        </tr>

        @foreach ($ar_I as $dulieu)
            <tr>
                <td>{{ $dulieu['tt'] }}</td>
                <td>{{ $dulieu['noidung'] }}</td>
                <td class="text-center">{{ dinhdangsothapphan($dulieu['songuoi']) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($dulieu['quy1']) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($dulieu['quy2']) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($dulieu['trocap']) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($dulieu['baohiem']) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($dulieu['tongquy']) }}</td>
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
            <td style="text-align: center;" width="50%">{{  mb_strtoupper($m_dv->cdlanhdao) }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%"></td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ '' }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
        </tr>
    </table>

@stop
