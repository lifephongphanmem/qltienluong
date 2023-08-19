@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Biểu số 2k</b>
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
                <b>TỔNG HỢP KINH PHÍ GIẢM THEO NGHỊ ĐỊNH SỐ 34/2019/NĐ-CP - CÁN BỘ, CÔNG CHỨC CẤP XÃ</b>
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
                <th colspan="2">Số lượng cán bộ, công chức cấp xã theo Nghị định 92/2009/NĐ-CP</th>
                <th rowspan="2" style="width: 5%">Số lượng công chức cấp xã có mặt 01/07/2023</th>
                <th colspan="3">Trong đó</th>
                <th colspan="2">Số lượng cán bộ, công chức cấp xã theo quy định tại Nghị định 34/2019/NĐ-CP</th>
                <th rowspan="2" style="width: 5%">Số lượng cán bộ, công chức cấp xã giảm</th>
                <th rowspan="2" style="width: 5%">Qũy lương, phụ cấp, các khoản đóng góp giảm 1 tháng (lương 1,8)</th>
                <th rowspan="2" style="width: 5%">Quỹ lương, phụ cấp giảm năm 2023 (lương 1,8)</th>
            </tr>
            <tr>
                <th style="width: 5%">Định biên theo Nghị định 92/2009/NĐ-CP</th>
                <th style="width: 5%">Tổng số</th>

                <th style="width: 5%">Hệ số lương ngạch bậc bình quân</th>
                <th style="width: 5%">Hệ số phụ cấp bình quân</th>
                <th style="width: 5%">Tỷ lệ phụ cấp tính các khoản đóng góp</th>

                <th style="width: 5%">Định biên theo Nghị định 34/2019/NĐ-CP</th>
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
                <td>10</td>
                <td>11</td>
                <td>12=11*6T</td>
            </tr>
        </thead>

        @foreach ($ar_I as $dulieu)
            <tr style=" text-align: right">
                <td style=" text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style=" text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['soluongdonvi_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['qd92_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['qd92_2k'] * $dulieu['solieu']['soluongdonvi_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['tongbienche_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['trungbinhheso_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['trungbinhphucap_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['trungbinhdonggop_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['qd34_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['tongqd34_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['soluonggiam_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['quyluonggiam_2k']) }}</td>
                <td>{{ dinhdangsothapphan($dulieu['solieu']['tongquyluonggiam_2k']) }}</td>
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
