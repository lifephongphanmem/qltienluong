@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2a/1</b>
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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH 72/2018/NĐ-CP NĂM 2018</p>
<p id="data_body1" style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 68/2018/TT-BTC)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto;font-size: 11px; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">BIÊN CHẾ ĐƯỢC</br>CẤP CÓ THẨM QUYỀN GIAO HOẶC PHÊ DUYỆT NĂM 2018</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">TỔNG SỐ</br>ĐỐI TƯỢNG HƯỞNG LƯƠNG CÓ MẶT ĐẾN 01/07/2018</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="14">QUỸ LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓP GÓP THÁNG 07/2018 THEO NGHỊ ĐỊNH SỐ 47/2017/NĐ-CP</th>

    </tr>
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">TỔNG CỘNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">LƯƠNG THEO NGẠCH, BẬC CHỨC VỤ</th>
        <th style="width: 6%;" rowspan="2">TỔNG CÁC KHOẢN PHỤ CẤP</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="10">TRONG ĐÓ</th>
        <th rowspan="2">CÁC KHOẢN</br>ĐÓNG GÓP</br>BHXH,BHYT,</br>BHTN, KPCĐ</th>
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
        <td>4</td>
        <td>5=6+7+18</td>
        <td>6</td>
        <td>7=8+...+17</td>
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
    </tr>

    <tr style="font-weight: bold; text-align: right">
        <th></th>
        <th style="text-align: center">TỔNG CỘNG (I+II+III+IV)</th>

        <th style="text-align: center"></th>
        <th style="text-align: center"></th>
        <td>{{dinhdangso($a_It['luong'] + $a_It['tongpc'] + $a_It['ttbh_dv'] + $ar_II['luong'] + $ar_II['tongpc'] + $ar_II['ttbh_dv'] + $a_IIIt['tongso'] + $a_IVt['tongso'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['luong'] + $ar_II['luong'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'] + $ar_II['tongpc'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pckv'] + $ar_II['pckv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pccv'] + $ar_II['pccv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pctnvk'] + $ar_II['pctnvk'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'] + $ar_II['pcudn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcth'] + $ar_II['pcth'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pctn'] + $ar_II['pctn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'] + $ar_II['pccovu'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'] + $ar_II['pcdang'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'] + $ar_II['pcthni'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pck'] + $ar_II['pck'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'] + $ar_II['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
    </tr>
    <tr style="font-weight: bold; text-align: right">
        <th style="text-align: center">I</th>
        <th style="text-align: left">KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ</th>
        <td>{{dinhdangso($a_It['soluongduocgiao'])}}</td>
        <td>{{dinhdangso($a_It['soluongbienche'])}}</td>
        <td>{{dinhdangso($a_It['luong'] + $a_It['tongpc'] + $a_It['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['luong'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pckv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pccv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pctnvk'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcth'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pctn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['pck'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
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
    </tr>
    @foreach($ar_I as $dulieu)
        <tr style=" text-align: right">
            <td style=" text-align: center">{{$dulieu['tt']}}</td>
            <td style=" text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['soluongduocgiao'])}}</td>
            <td>{{dinhdangso($dulieu['soluongbienche'])}}</td>
            <td>{{dinhdangso($dulieu['luong'] + $dulieu['tongpc'] + $dulieu['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['luong'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['tongpc'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pckv'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pccv'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pctnvk'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pcudn'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pcth'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pctn'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pccovu'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pcdang'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pcthni'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['pck'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach

    <tr style="font-weight: bold;">
        <td>II</td>
        <td>CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ</td>
        <td>{{dinhdangso($ar_II['soluongduocgiao'])}}</td>
        <td>{{dinhdangso($ar_II['soluongbienche'])}}</td>
        <td>{{dinhdangso($ar_II['luong'] + $ar_II['tongpc'] + $ar_II['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['luong'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['tongpc'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pckv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pccv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pctnvk'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pcudn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pcth'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pctn'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pccovu'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pcdang'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pcthni'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['pck'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($ar_II['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
    </tr>
    <tr style="font-weight: bold;">
        <td>III</td>
        <td>HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IIIt['tongso'],0,$inputs['donvitinh'])}}</td>
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

    @foreach($ar_III as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['tongso'],0,$inputs['donvitinh'])}}</td>
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
    @endforeach

    <tr style="font-weight: bold;">
        <td>IV</td>
        <td>PHỤ CẤP TRÁCH NHIỆM CẤP ỦY</td>
        <td></td>
        <td></td>
        <td>{{dinhdangso($a_IVt['tongso'],0,$inputs['donvitinh'])}}</td>
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
    @foreach($ar_IV as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($dulieu['tongso'],0,$inputs['donvitinh'])}}</td>
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
    @endforeach
</table>

<table id='data_footer' class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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