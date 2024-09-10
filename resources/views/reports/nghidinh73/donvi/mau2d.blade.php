@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2d</b>
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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ TĂNG THÊM CHI
        TRẢ CHẾ ĐỘ CHO SỐ BIÊN CHẾ GIÁO VIÊN TĂNG THÊM NĂM HỌC 2023-2024</p>
    <p id="data_body1" style="text-align: center; font-style: italic">{{ $m_thongtu->ghichu }}</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr style="text-align: center">
                <th style="width: 2%;" rowspan="3">STT</th>
                <th rowspan="3">Chỉ tiêu</th>
                <th style="width: 6%;" rowspan="3">Tổng số biên chế giao bổ sung năm 2024</th>
                <th style="width: 6%;" rowspan="3">Tổng số biên chế có mặt đến 01/7/2024</th>
                <th style="width: 6%;" rowspan="3">Tổng hệ số của 1 biên chế</th>
                <th style="width: 6%;" colspan="7">Bao gồm</th>
                <th style="width: 10%;" rowspan="3">Nhu cầu kinh phí tăng thêm thực hiện Nghị định 24/2023/NĐ-CP và Nghị
                    định số 73/2024/NĐ-CP (1)</th>
            </tr>

            <tr style="text-align: center">
                <th style="width: 6%;" rowspan="2">Hệ số lương theo ngạch, bậc, chức vụ</th>
                <th style="width: 6%;" rowspan="2">Tổng hệ số phụ cấp</th>
                <th style="width: 6%;" colspan="4">Trong đó</th>
                <th style="width: 6%;" rowspan="2">Tỷ lệ các khoản đóng góp</th>
            </tr>

            <tr style="text-align: center">
                <th style="width: 6%;">Tỷ lệ phụ cấp khu vực</th>
                <th style="width: 6%;">Tỷ lệ phụ cấp ưu đãi nghề</th>
                <th style="width: 6%;">Tỷ lệ phụ cấp thu hút</th>
                <th style="width: 6%;">Tỷ lệ phụ cấp đặc biệt</th>
            </tr>

            <tr style="font-weight: bold; text-align: center">
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5=6+7+11</td>
                <td>6</td>
                <td>7=8+9x6+10x6+11x6</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>11</td>
                <td>12=6x23,5%</td>
                <td>13=4x5x0,31 triệu đồng x số tháng thực tế hưởng</td>
            </tr>
        </thead>
        <tr style=" text-align: right;">
            <td style=" text-align: center;"></td>
            <td style=" text-align: left;">Tổng cộng</td>
            <td>{{ dinhdangsothapphan($ar_Tong['bienchebosung']) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['bienchecomat']) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['tongcong'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['hesoluong'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['tongpc'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['pckhuvuc'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['pcudn'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['pcthuhut'],3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['pckhac'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['donggop'], 3) }}</td>
            <td>{{ dinhdangsothapphan($ar_Tong['nhucau'], 3) }}</td>
        </tr>
        @foreach ($ar_I as $dulieu)
            <tr style=" text-align: right;">
                <td style=" text-align: center;">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left;">{{ $dulieu['noidung'] }}</td>
                <td>{{ dinhdangsothapphan($dulieu['bienchebosung']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['bienchecomat']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['tongcong'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['hesoluong'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['tongpc'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['pckhuvuc'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['pcudn'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['pcthuhut'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['pckhac'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['donggop'], 3) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['nhucau'], 3) }}</td>
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
