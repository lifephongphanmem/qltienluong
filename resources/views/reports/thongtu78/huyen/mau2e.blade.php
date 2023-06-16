@extends('main_baocao')

@section('content')

    <table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px;text-align: center">
        <tr>
            <td style="text-align: left">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>


            <td style="text-align: right">
                <b>Biểu số 2e</b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THAY ĐỔI CƠ CHÉ TỰ CHỦ TRONG NĂM 2023 <br> THEO NGHỊ QUYẾT
                    CỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{-- <i>(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</i> --}}
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td style="text-align: right">
                <i>Đơn vị: {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</i>
            </td>
        </tr>
    </table>
    <table id="data_body" width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <tr>
            <th rowspan="2" style="width: 5%">STT</th>
            <th rowspan="2" style="width: 23%">Phân loại đơn vị sự nghiệp</th>
            <th rowspan="2">Số lượng đơn vị đến 31/12/2017</th>
            <th rowspan="2">Số lượng đơn vị đến 31/12/2023</th>
            <th colspan="2">Số lượng đơn vị thay đổi loại hình cơ chế tự chủ</th>
            <th rowspan="2">Kinh phí tiết kiệm được từ việc thay đổi cơ chế tự chủ trong 1 tháng (1)</th>
            <th rowspan="2">Kinh phí tiết kiệm trong năm 2023</th>
        </tr>
        <tr>
            <th>Tăng</th>
            <th>Giảm</th>
        </tr>
        <tr>
            <td>A</td>
            <td>B</td>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
        </tr>
        <tr style="font-weight: bold; text-align: center">
            <td></td>
            <td>TỔNG CỘNG</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right"></td>
            <td style="text-align: right"></td>

        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: center">
                <td>{{ $dulieu['tt'] }}</td>
                <td style="text-align: left">{{ $dulieu['noidung'] }}</td>
                <td>{{ dinhdangso($dulieu['solieu']['tongsodonvi1']) }}</td>
                <td>{{ dinhdangso($dulieu['solieu']['tongsodonvi2']) }}</td>
                <td>{{ dinhdangso($dulieu['solieu']['tang']) }}</td>
                <td>{{ dinhdangso($dulieu['solieu']['giam']) }}</td>
                <td style="text-align: right">{{ dinhdangso($dulieu['solieu']['quy_tuchu']) }}</td>
                <td style="text-align: right">{{ dinhdangso($dulieu['solieu']['kp_tk']) }}</td>
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
