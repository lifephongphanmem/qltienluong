@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2a</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;width: 60%">
            <b>{{'Đơn vị: '.$m_dv->tendv}}</b>
        </td>
        <td style="text-align: center; font-style: italic">

        </td>
    </tr>
</table>
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP NĂM 2019</p>
<p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: Đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">NỘI DUNG</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">BIÊN</br>CHẾ</br>ĐƯỢC</br>CẤP</br>CÓ</br>THẨM</br>QUYỀN</br>GIAO</br>HOẶC</br>PHÊ</br>DUYỆT</br>NĂM</th>
        <th colspan="2">TRONG ĐÓ</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">TỔNG</br>SỐ</br>ĐỐI</br>TƯỢNG</br>HƯỞNG</br>LƯƠNG</br>CÓ</br>MẶT</br>ĐẾN</br>01/7/2018</th>
        <th colspan="2">TRONG ĐÓ</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="14">HỆ SỐ LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓP GÓP THÁNG 07/2019</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">CHÊNH</br>LỆCH</br>QUỸ</br>LƯƠNG</br>PHỤ</br>CẤP</br>TĂNG</br>THÊM</br>01</br>THÁNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">NHU CẦU</br>KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP NĂM 2019</th>

    </tr>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">BIÊN</br>CHẾ</br>CÔNG</br>CHỨC</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">BIÊN</br>CHẾ</br>VIÊN</br>CHỨC</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">BIÊN</br>CHẾ</br>CÔNG</br>CHỨC</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">BIÊN</br>CHẾ</br>VIÊN</br>CHỨC</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">TỔNG</br>CỘNG</th>
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">HỆ</br>SỐ</br>LƯƠNG</br>THEO</br>NGẠCH,</br>BẬC</br>CHỨC</br>VỤ</th>
        <th style="width: 2%;" rowspan="2">TỔNG</br>CÁC</br>KHOẢN</br>PHỤ</br>CẤP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="10">TRONG ĐÓ</th>
        <th style="width: 2%;"rowspan="2">CÁC</br>KHOẢN</br>ĐÓNG</br>GÓP</br>BHXH,</br>BHYT,</br>BHTN,</br>KPCĐ</th>
    </tr>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th>PHỤ CẤP KHU VỰC</th>
        <th>PHỤ CẤP CHỨC VỤ</th>
        <th>PHỤ CẤP THÂM NIÊN VƯỢT KHUNG</th>
        <th>PHỤ CẤP ƯU ĐÃI NGÀNH</th>
        <th>PHỤ CẤP THU HÚT</th>
        <th>PHỤ CẤP CÔNG TÁC LÂU NĂM</th>
        <th>PHỤ CẤP CÔNG VỤ</th>
        <th>PHỤ CẤP CÔNG TÁC ĐẢNG</th>
        <th>PHỤ CẤP THÂM NIÊN NGHỀ</th>
        <th>PHỤ CẤP KHÁC</th>
    </tr>

    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td></td>
        <td></td>
        <td>4</td>
        <td></td>
        <td></td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
        <td>9</td>
        <td>10</td>
        <td>11</td>
        <td>12</td>
        <td>13</td>
        <td>14</td>
        <td>15</td>
        <td>16</td>
        <td>17</td>
        <td>18</td>
        <td>19=5*0,1</td>
        <td>33x6T</td>
    </tr>


    {{-- <tr style="font-weight: bold;text-align: right">
        <th style="text-align: center">I</th>
        <th style="text-align: left">KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ</th>
        <td>{{dinhdangso($a_It['soluongduocgiao'])}}</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_It['soluongbienche'])}}</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_It['st_heso'] + $a_It['tongpc'] +$a_It['ttbh_dv'])}}</td>
        <td>{{dinhdangso($a_It['heso'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'])}}</td>
        <td>{{dinhdangso($a_It['pckv'])}}</td>
        <td>{{dinhdangso($a_It['pccv'])}}</td>
        <td>{{dinhdangso($a_It['vuotkhung'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'])}}</td>
        <td>{{dinhdangso($a_It['pcth'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'])}}</td>
        <td>{{dinhdangso($a_It['pctnn'])}}</td>
        <td>{{dinhdangso($a_It['pck'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech']*6)}}</td>
    </tr>

    <tr style="font-style: italic;">
        <td></td>
        <td>Trong đó</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @foreach($ar_I as $dulieu)
        <tr style="text-align: right">
            <td style="text-align: center">{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['soluongduocgiao'])}}</td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['soluongbienche'])}}</td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['heso'] + $dulieu['tongpc'] +$dulieu['ttbh_dv'])}}</td>
            <td>{{dinhdangso($dulieu['heso'])}}</td>
            <td>{{dinhdangso($dulieu['tongpc'])}}</td>
            <td>{{dinhdangso($dulieu['pckv'])}}</td>
            <td>{{dinhdangso($dulieu['pccv'])}}</td>
            <td>{{dinhdangso($dulieu['vuotkhung'])}}</td>
            <td>{{dinhdangso($dulieu['pcudn'])}}</td>
            <td>{{dinhdangso($dulieu['pcth'])}}</td>
            <td>{{dinhdangso($dulieu['pcthni'])}}</td>
            <td>{{dinhdangso($dulieu['pccovu'])}}</td>
            <td>{{dinhdangso($dulieu['pcdang'])}}</td>
            <td>{{dinhdangso($dulieu['pctnn'])}}</td>
            <td>{{dinhdangso($dulieu['pck'])}}</td>
            <td>{{dinhdangso($dulieu['ttbh_dv'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold;text-align: right">
        <td style="text-align: center">II</td>
        <td style="text-align: left;">CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($ar_II['heso'] + $ar_II['tongpc'] +$ar_II['ttbh_dv'])}}</td>
        <td>{{dinhdangso($ar_II['heso'])}}</td>
        <td>{{dinhdangso($ar_II['tongpc'])}}</td>
        <td>{{dinhdangso($ar_II['pckv'])}}</td>
        <td>{{dinhdangso($ar_II['pccv'])}}</td>
        <td>{{dinhdangso($ar_II['vuotkhung'])}}</td>
        <td>{{dinhdangso($ar_II['pcudn'])}}</td>
        <td>{{dinhdangso($ar_II['pcth'])}}</td>
        <td>{{dinhdangso($ar_II['pcthni'])}}</td>
        <td>{{dinhdangso($ar_II['pccovu'])}}</td>
        <td>{{dinhdangso($ar_II['pcdang'])}}</td>
        <td>{{dinhdangso($ar_II['pctnn'])}}</td>
        <td>{{dinhdangso($ar_II['pck'])}}</td>
        <td>{{dinhdangso($ar_II['ttbh_dv'])}}</td>
        <td>{{dinhdangso($ar_II['chenhlech'])}}</td>
        <td>{{dinhdangso($ar_II['chenhlech']*6)}}</td>
    </tr>
    <tr style="font-weight: bold;text-align: right">
        <td style="text-align: center">III</td>
        <td style="text-align: left">HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IIIt['tongso'])}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IIIt['chenhlech'])}}</td>
        <td>{{dinhdangso($a_IIIt['chenhlech']*6)}}</td>
    </tr>

    @foreach($ar_III as $dulieu)
        <tr style="text-align: right">
            <td style="text-align: center">{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['tongso'])}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold;text-align: right">
        <td style="text-align: center">IV</td>
        <td style="text-align: left">PHỤ CẤP TRÁCH NHIỆM CẤP ỦY</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IVt['tongso'])}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IVt['chenhlech'])}}</td>
        <td>{{dinhdangso($a_IVt['chenhlech']*6)}}</td>
    </tr>
    @foreach($ar_IV as $dulieu)
        <tr style="text-align: right;">
            <td style="text-align: center">{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['tongso'])}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td>{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach --}}
</table>

<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">CHỦ TỊCH ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">((Ký tên, đóng dấu))</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>
@stop