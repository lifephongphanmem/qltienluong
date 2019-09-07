<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        .header tr td {
            padding-top: 0px;
            padding-bottom: 10px;
        }

        table, p {
            width: 98%;
            margin: auto;
        }

        table tr td:first-child {
            text-align: center;
        }

        td, th {
            padding: 10px;
        }
        p{
            padding: 5px;
        }
        span{
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>
<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
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
<p style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NHU CẦU KINH PHÍ THỰC HIỆN NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP NĂM 2019</p>
<p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</p>
<p style="text-align: right; font-style: italic">Đơn vị: Đồng</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto;font-size: 11px; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="3">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="3">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">BIÊN CHẾ ĐƯỢC</br>CẤP CÓ THẨM QUYỀN GIAO HOẶC PHÊ DUYỆT NĂM 2018</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="3">TỔNG SỐ</br>ĐỐI TƯỢNG HƯỞNG LƯƠNG CÓ MẶT ĐẾN 01/07/2019</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" colspan="14">QUỸ LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓP GÓP THÁNG 07/2019 THEO NGHỊ ĐỊNH SỐ 72/2018/NĐ-CP</th>

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
        <td>{{dinhdangso($a_It['heso'] + $a_It['tongpc'] + $a_It['ttbh_dv'] + $ar_II['heso']
                + $ar_II['tongpc'] + $ar_II['ttbh_dv'] + $a_IIIt['tongso'] + $a_IVt['tongso'])}}</td>
        <td>{{dinhdangso($a_It['heso'] + $ar_II['heso'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'] + $ar_II['tongpc'])}}</td>
        <td>{{dinhdangso($a_It['pckv'] + $ar_II['pckv'])}}</td>
        <td>{{dinhdangso($a_It['pccv'] + $ar_II['pccv'])}}</td>
        <td>{{dinhdangso($a_It['vuotkhung'] + $ar_II['vuotkhung'])}}</td>
        <td>{{dinhdangso($a_It['pcudn'] + $ar_II['pcudn'])}}</td>
        <td>{{dinhdangso($a_It['pcth'] + $ar_II['pcth'])}}</td>
        <td>{{dinhdangso($a_It['pcthni'] + $ar_II['pcthni'])}}</td>
        <td>{{dinhdangso($a_It['pccovu'] + $ar_II['pccovu'])}}</td>
        <td>{{dinhdangso($a_It['pcdang'] + $ar_II['pcdang'])}}</td>
        <td>{{dinhdangso($a_It['pctnn'] + $ar_II['pctnn'])}}</td>
        <td>{{dinhdangso($a_It['pck'] + $ar_II['pck'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'] + $ar_II['ttbh_dv'],0)}}</td>
    </tr>
    <tr style="font-weight: bold; text-align: right">
        <th style="text-align: center">I</th>
        <th style="text-align: left">KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ</th>
        <td>{{dinhdangso($a_It['soluongduocgiao'])}}</td>
        <td>{{dinhdangso($a_It['soluongbienche'])}}</td>
        <td>{{dinhdangso($a_It['heso'] + $a_It['tongpc'] + $a_It['ttbh_dv'])}}</td>
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
            <td>{{dinhdangso($dulieu['heso'] + $dulieu['tongpc'] + $dulieu['ttbh_dv'])}}</td>
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
        </tr>
    @endforeach

    <tr style="font-weight: bold;">
        <td>II</td>
        <td>CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ</td>
        <td>{{dinhdangso($ar_II['soluongduocgiao'])}}</td>
        <td>{{dinhdangso($ar_II['soluongbienche'])}}</td>
        <td>{{dinhdangso($ar_II['heso'] + $ar_II['tongpc'] + $ar_II['ttbh_dv'])}}</td>
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
    </tr>
    <tr style="font-weight: bold;">
        <td>III</td>
        <td>HOẠT ĐỘNG PHÍ ĐẠI BIỂU HĐND CÁC CẤP</td>
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
    </tr>

    @foreach($ar_III as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
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
        </tr>
    @endforeach

    <tr style="font-weight: bold;">
        <td>IV</td>
        <td>PHỤ CẤP TRÁCH NHIỆM CẤP ỦY</td>
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
    </tr>
    @foreach($ar_IV as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
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
        </tr>
    @endforeach
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">{{$m_dv->cdlanhdao}}</td>
    </tr>
    <tr style="font-style: italic">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
    </tr>
    <tr>
        <td><br><br><br></td>
    </tr>

    <tr>
        <td style="text-align: center;" width="50%">{{''}}</td>
        <td style="text-align: center;" width="50%">{{$m_dv->lanhdao}}</td>
    </tr>
</table>

</body>
</html>