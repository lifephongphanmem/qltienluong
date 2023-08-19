@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 4b</b>
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
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">TỔNG HỢP NHU CẦU, NGUỒN THỰC HIỆN NGHỊ
        ĐỊNH SỐ 24/2023/NĐ-CP NĂM 2023</p>
    {{-- <p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p> --}}
    <p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
        tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</p>
    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị:
        {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 10px auto; border-collapse: collapse;">
        <tr>
            <th style="width: 3%;" rowspan="3">TT</th>
            <th rowspan="3">CHỈ TIÊU</th>
            <th style="width: 8%;" rowspan="3">NHU CẦU KINH PHÍ THỰC HIỆN CCTL NĂM 2023</th>
            <th colspan="5">NGUỒN TỪ TIẾT KIỆM 10% CHI THƯỜNG
                XUYÊN, NGUỒN THU ĐỂ LẠI ĐƠN VỊ</th>

        </tr>

        <tr>
            <th style="width: 8%;" rowspan="2">TỔNG SỐ</th>
            <th style="width: 8%;" rowspan="2">TIẾT KIỆM 10% CHI THƯỜNG XUYÊN</th>
            <th colspan="3">NGUỒN THU TỪ ĐƠN VỊ HÀNH CHÍNH, SỰ NGHIỆP</th>
        </tr>

        <tr>
            <th style="width: 8%;">HỌC PHÍ</th>
            <th style="width: 8%;">VIỆN PHÍ</th>
            <th style="width: 8%;">KHÁC</th>
        </tr>
        <tr style="font-weight: bold;">
            <td></td>
            <td>TỔNG SỐ</td>
            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['nhucau'] +
                        $data[3]['solieu']['nhucau'] +
                        $data[4]['solieu']['nhucau'] +
                        $data[5]['solieu']['nhucau'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>

            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['tongso'] +
                        $data[3]['solieu']['tongso'] +
                        $data[4]['solieu']['tongso'] +
                        $data[5]['solieu']['tongso'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>

            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['tietkiem'] +
                        $data[3]['solieu']['tietkiem'] +
                        $data[4]['solieu']['tietkiem'] +
                        $data[5]['solieu']['tietkiem'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>

            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['hocphi'] +
                        $data[3]['solieu']['hocphi'] +
                        $data[4]['solieu']['hocphi'] +
                        $data[5]['solieu']['hocphi'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>

            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['vienphi'] +
                        $data[3]['solieu']['vienphi'] +
                        $data[4]['solieu']['vienphi'] +
                        $data[5]['solieu']['vienphi'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>

            <td class="text-right">
                {{ dinhdangso(
                    $data[0]['solieu']['nguonthu'] +
                        $data[3]['solieu']['nguonthu'] +
                        $data[4]['solieu']['nguonthu'] +
                        $data[5]['solieu']['nguonthu'],
                    0,
                    $inputs['donvitinh'],
                ) }}
            </td>
        </tr>
        @foreach ($data as $dulieu)
            <tr>
                <td>{{ $dulieu['tt'] }}</td>
                <td>{{ $dulieu['noidung'] }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['nhucau'], 0, $inputs['donvitinh']) }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['tongso'], 0, $inputs['donvitinh']) }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['tietkiem'], 0, $inputs['donvitinh']) }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['hocphi'], 0, $inputs['donvitinh']) }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['vienphi'], 0, $inputs['donvitinh']) }}</td>
                <td class="text-right">{{ dinhdangso($dulieu['solieu']['nguonthu'], 0, $inputs['donvitinh']) }}</td>
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
