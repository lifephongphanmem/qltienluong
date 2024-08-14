@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 4a</b>
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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NGUỒN KINH PHÍ ĐỂ THỰC HIỆN
        CẢI CÁCH TIỀN LƯƠNG NĂM 2024</p>

    <p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Nghị định số 73/2024/NĐ-CP ngày 30
        tháng 6 năm 2024)</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị:
        {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 10px auto; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th>NỘI DUNG</th>
                <th style="width: 20%">SỐ TIỀN</th>
            </tr>
            <tr style="font-weight: bold; text-align: center">
                <td>1</td>
                <td>2</td>
                <td>3</td>
            </tr>
        </thead>

        <tr style="font-weight: bold;">
            <td>A</td>
            <td>NGUỒN THỰC HIỆN CẢI CÁCH TIỀN LƯƠNG NĂM 2024</td>
            <td class="money">{{ dinhdangso($a_TC['A'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        @foreach ($a_A as $dulieu)
            <tr>
                <td>{{ $dulieu['tt'] }}</td>
                <td>{{ $dulieu['noidung'] }}</td>
                <td class="money">{{ dinhdangso($dulieu['sotien'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td>B</td>
            <td>TỔNG NHU CẦU NĂM 2024</td>
            <td class="money">
                {{ dinhdangso($a_TC['B1'] + $a_TC['B2'] + $a_TC['B3'], 0, $inputs['donvitinh']) }}
            </td>
        </tr>
        <!-- B.I -->
        <tr style="font-weight: bold;">
            <td>I</td>
            <td>Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số 24/2023/NĐ-CP và Nghị
                định số 42/2023/NĐ-CP (tính đủ 12 tháng)</td>
            <td class="money">{{ dinhdangso(array_sum(array_column($a_B1, 'sotien')), 0, $inputs['donvitinh']) }}</td>
        </tr>

        <!-- B.II -->
        <tr style="font-weight: bold;">
            <td>II</td>
            <td>Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số 73/2024/NĐ-CP</td>
            <td class="money">{{ dinhdangso($a_TC['B2'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        @foreach ($a_B2 as $dulieu)
            <tr>
                <td>{{ $dulieu['tt'] }}</td>
                <td>{{ $dulieu['noidung'] }}</td>
                <td class="money">{{ dinhdangso($dulieu['sotien'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach

        <!-- B.III -->
        <tr style="font-weight: bold;">
            <td>III</td>
            <td>Nhu cầu thực hiện một số loại phụ cấp, trợ cấp theo quy định</td>
            <td class="money">{{ dinhdangso($a_TC['B3'], 0, $inputs['donvitinh']) }}</td>
        </tr>
        @foreach ($a_B3 as $dulieu)
            <tr>
                <td>{{ $dulieu['tt'] }}</td>
                <td>{{ $dulieu['noidung'] }}</td>
                <td class="money">{{ dinhdangso($dulieu['sotien'], 0, $inputs['donvitinh']) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td>C</td>
            <td>CHÊNH LỆCH NHU CẦU VÀ NGUỒN NĂM 2024</td>
            <td class="money">
                {{ dinhdangso(abs($a_TC['A'] - $a_TC['B1'] - $a_TC['B2'] - $a_TC['B3']), 0, $inputs['donvitinh']) }}
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Phần thiếu nguồn ngân sách trung ương hỗ trợ</td>
            <td>
                {{ dinhdangso($a_TC['A'] > $a_TC['B1'] + $a_TC['B2'] + $a_TC['B3'] ? 0 : abs($a_TC['A'] - $a_TC['B1'] - $a_TC['B2'] - $a_TC['B3']), 0, $inputs['donvitinh']) }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Nguồn thực hiện cải cách tiền lương còn dư</td>
            <td class="money">
                {{ dinhdangso($a_TC['A'] > $a_TC['B1'] + $a_TC['B2'] + $a_TC['B3'] ? $a_TC['A'] - $a_TC['B1'] - $a_TC['B2'] - $a_TC['B3'] : 0, 0, $inputs['donvitinh']) }}
            </td>
        </tr>
    </table>
    <!-- 2024.07.26 bỏ chữ ký theo y.c
        <table id="data_footer1" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
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
    -->
@stop
