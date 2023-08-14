@extends('main_baocao')

@section('content')

    <table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px;text-align: center">
        <tr>
            <td style="text-align: left">
                <b>{{ 'Đơn vị: ' . $m_dv->tendv }}</b>
            </td>
            <td style="text-align: right">
                <b>Biểu số 2g</b><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{-- <b>BÁO CÁO QUỸ LƯƠNG, PHỤ CẤP ĐỐI VỚI LAO ĐỘNG THEO HỢP ĐỒNG KHU VỰC HÀNH CHÍNH VÀ ĐƠN VỊ SỰ NGHIỆP</b> --}}
                <b>TỔNG HỢP PHỤ CẤP ƯU ĐÃI NGHỀ THEO NGHỊ ĐỊNH SỐ 05/2023/NĐ-CP NGÀY 15/02/2023 CỦA CHÍNH PHỦ</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{-- <i>(Ban hành kèm theo Thông tư số 68/2018/TT-BTC)</i> --}}
                <i>(Ban hành kèm theo Thông tư số 50/2023/TT-BTC ngày 17
                    tháng 7 năm 2023 của Bộ trưởng Bộ Tài chính)</i>
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td style="text-align: right">
                <i>Đơn vị:
                    {{ $inputs['donvitinh'] == 1 ? 'Đồng' : ($inputs['donvitinh'] == 2 ? 'Nghìn đồng' : 'Triệu đồng') }}</i>
            </td>
        </tr>
    </table>
    <table id="data_body" width="96%" border="1" cellspacing="0" cellpadding="4"
        style="margin:0 auto 20px; text-align: center;">
        <tr>
            <th rowspan="2" style="width: 5%">STT</th>
            <th rowspan="2">NỘI DUNG</th>
            <th style="width: 8%" rowspan="2">TỔNG SỐ ĐỐI TƯỢNG HƯỞNG LƯƠNG CÓ MẶT ĐẾN 01/07/2023</th>
            <th colspan="4">QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP THÁNG 07/2023 THEO NGHỊ ĐỊNH SỐ 24/2023/NĐ-CP
            </th>
        </tr>
        <tr>
            <th style="width: 8%">TỔNG CỘNG</th>
            <th style="width: 8%">LƯƠNG THEO NGẠCH, BẬC CHỨC VỤ</th>
            <th style="width: 8%">TỔNG CÁC KHOẢN PHỤ CẤP</th>
            <th style="width: 8%">CÁC KHOẢN ĐÓNG GÓP BHXH, BHYT, BHTN, KPCĐ</th>
        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4=5+6+7</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
        </tr>
        
        @foreach ($ar_I as $dulieu)
            <tr style="text-align: right">
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ $dulieu['tt'] }}</td>
                <td style="text-align: left;{{ $dulieu['style'] }}">{{ $dulieu['noidung'] }}</td>
                <td style="text-align: center;{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['canbo_congtac']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['tongcong']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['st_heso']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['st_tongpc']) }}</td>
                <td style="{{ $dulieu['style'] }}">{{ dinhdangsothapphan($dulieu['solieu']['ttbh_dv']) }}</td>
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
