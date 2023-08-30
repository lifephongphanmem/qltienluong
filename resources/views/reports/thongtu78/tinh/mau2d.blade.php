@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: right;">
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
        <tr>
            <td colspan="2">
                <b>TỔNG HỢP KINH PHÍ TĂNG/GIẢM THEO NGHỊ ĐỊNH SỐ 33/2023/NĐ-CP - CÁN BỘ, CÔNG CHỨC CẤP XÃ</b>
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
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%">STT</th>
                <th rowspan="2">Chỉ tiêu</th>
                <th rowspan="2" style="width: 5%">Tổng số đơn vị hành chính cấp xã</th>
                <th colspan="2">Số lượng cán bộ, công chức cấp xã theo Nghị định 34/2019/NĐ-CP</th>
                <th rowspan="2" style="width: 5%">Số lượng công chức cấp xã đã tính định mức năm 2022</th>
                <th colspan="3">Trong đó hệ số tính định mức năm 2022</th>
                <th colspan="2">Số lượng cán bộ, công chức cấp xã theo quy định tại Nghị định 33/2023/NĐ-CP</th>
                <th rowspan="2" style="width: 5%">Số lượng cán bộ, công chức cấp xã tăng/giảm so với định mức</th>
                <th rowspan="2" style="width: 5%">Qũy lương, phụ cấp, các khoản đóng góp tăng/giảm 1 tháng (lương 1,49)
                </th>
                <th rowspan="2" style="width: 5%">Quỹ lương, phụ cấp tăng/giảm năm 2023 (lương 1,49)</th>
            </tr>
            <tr>
                <th style="width: 5%">Định biên theo Nghị định 34/2019/NĐ-CP</th>
                <th style="width: 5%">Tổng số</th>

                <th style="width: 5%">Hệ số lương ngạch bậc bình quân</th>
                <th style="width: 5%">Hệ số phụ cấp bình quân</th>
                <th style="width: 5%">Tỷ lệ phụ cấp tính các khoản đóng góp</th>

                <th style="width: 5%">Định biên theo Nghị định 33/2023/NĐ-CP</th>
                <th style="width: 5%">Tổng số</th>

            </tr>
            <tr>
                <td>A</td>
                <td>B</td>
                <td>1</td>
                <td>2</td>
                <td>3=1*2</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9=1*8</td>
                <td>10=3-9</td>
                <td>11</td>
                <td>12=11x5T</td>
            </tr>
        </thead>
        @foreach ($model_donvi_bc as $diaban)
            @foreach ($ar_I[$diaban->madvbc] as $dulieu)
                <tr style=" text-align: right;{{ $dulieu['style'] }}">
                    <td style=" text-align: center;">{{ $dulieu['tt'] }}</td>
                    <td style=" text-align: left;">{{ $dulieu['noidung'] }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['soluongdonvi_2k'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['qd34_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['tongqd34_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['soluongcanbo_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['hesoluongbq_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['hesophucapbq_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['tyledonggop_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['soluongdinhbien_2d'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['tongsodinhbien_2d'], $inputs['lamtron']) }}</td>
                    {{-- <td>{{ dinhdangsothapphan($dulieu['solieu']['tongqd34_2d'] - $dulieu['solieu']['soluongdinhbien_2d'], $inputs['lamtron']) }} --}}
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['tongqd34_2d'] - $dulieu['solieu']['tongsodinhbien_2d'], $inputs['lamtron']) }}
                    </td>

                    <td>{{ dinhdangsothapphan($dulieu['solieu']['quyluonggiam_2k'], $inputs['lamtron']) }}</td>
                    <td>{{ dinhdangsothapphan($dulieu['solieu']['tongquyluonggiam_2k'], $inputs['lamtron']) }}</td>
                </tr>
            @endforeach
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
