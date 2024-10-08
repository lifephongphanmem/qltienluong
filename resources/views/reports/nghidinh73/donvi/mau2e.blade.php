@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2đ</b>
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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM
        DO THAY ĐỔI MỨC TỰ CHỦ TÀI CHÍNH TRONG NĂM 2024 THEO NGHỊ QUYẾT SỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG
        ƯƠNG</p>
    <p id="data_body1" style="text-align: center; font-style: italic">{{ $m_thongtu->ghichu }}</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr style="text-align: center">
                <th rowspan="2" style="width: 2%;">STT</th>
                <th rowspan="2">Phân loại đơn vị sự nghiệp</th>
                <th rowspan="2" style="width: 6%;">Số lượng đơn vị đến 31/12/2023</th>
                <th rowspan="2" style="width: 6%;">Số lượng đơn vị đến 31/12/2024</th>
                <th colspan="2">Số lượng đơn vị thay đổi loại hình cơ chế tự chủ</th>
                <th rowspan="2" style="width: 6%;">Kinh phí tiết kiệm được trong 1 tháng từ việc thay đổi mức tự chủ tài chính </th>
                <th rowspan="2" style="width: 6%;">Kinh phí tiết kiệm năm 2024</th>
                <th rowspan="2" style="width: 6%;">50% kinh phí giảm chi NSNN năm 2024 dành để CCTL</th>
            </tr>
            <tr style="text-align: center">
                <th style="width: 6%;">Tăng</th>
                <th style="width: 6%;">Giảm</th>
            </tr>

            <tr style="font-weight: bold; text-align: center">
                <td>A</td>
                <td>B</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>                
                <td>7=6*50%</td>
            </tr>
        </thead>
        <tr style=" text-align: right; font-weight: bold">
            <td style=" text-align: center;"></td>
            <td style=" text-align: center;">Tổng cộng</td>
            <td>{{ dinhdangsothapphan($ar_Tong['bienche2023']) }}</td>           
            <td>{{ dinhdangsothapphan($ar_Tong['bienche2024']) }}</td>
            <td></td>
            <td></td>           
            <td>{{ dinhdangsothapphan($ar_Tong['tietkiem01thang'], $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['tietkiem2024'], $inputs['donvitinh']) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['kinhphigiam'], $inputs['donvitinh']) }}</td>
        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style=" text-align: right;">
                <td style=" text-align: center;">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left;">{{ $dulieu['noidung'] }}</td>
                <td>{{ dinhdangsothapphan($dulieu['bienche2023']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['bienche2024']) }}</td>
                <td></td>
                <td></td>
                <td>{{ dinhdangsothapphan($dulieu['tietkiem01thang'], $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['tietkiem2024'], $inputs['donvitinh']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['kinhphigiam'], $inputs['donvitinh']) }}</td>
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
