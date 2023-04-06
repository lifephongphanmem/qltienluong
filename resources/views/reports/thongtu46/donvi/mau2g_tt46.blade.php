@extends('main_baocao')

@section('content')

<table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <td  style="text-align: left;width: 60%">

        </td>
        <td  style="text-align: center;">
            <b>Biểu số 2g</b>
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
<p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO QUỸ TIỀN LƯƠNG, PHỤ CẤP ĐỐI VỚI LAO ĐỘNG THEO HỢP ĐỒNG </p>
<p id="data_body1" style="text-align: center; font-style: italic">(Kèm theo Công văn số ....../STC-HCNS ngày  tháng 9 năm 2018 của Sở Tài chính)</p>
<p id="data_body2" style="text-align: right; font-style: italic">Đơn vị: Đồng</p>
<table id="data_body3" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; font-size: 10px; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 2%;padding-left: 2px;padding-right: 2px" rowspan="2">STT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="2">NỘI DUNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">TỔNG SỐ ĐỐI</br>TƯỢNG HƯỞNG</br>LƯƠNG CÓ MẶT</br>ĐẾN 01/7/2019 (1)</th>
        <th style="width: 24%;padding-left: 2px;padding-right: 2px" colspan="4">HỆ SỐ LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP THÁNG</br>7/2019</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">CHÊNH LỆCH</br>QUỸ LƯƠNG,</br>PHỤ CẤP</br>TĂNG THÊM</br> 01 THÁNG</th>
        <th style="width: 6%;padding-left: 2px;padding-right: 2px" rowspan="2">NHU CẦU</br>KINH PHÍ</br>THỰC HIỆN</br>NGHỊ ĐỊNH SỐ</br>38/2019/NĐ-CP</th>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td>TỔNG CỘNG</td>
        <td>LƯƠNG THEO</br>NGẠCH, BẬC</br>CHỨC VỤ</td>
        <td>TỔNG CÁC HỆ</br>SỐ PHỤ CẤP</td>
        <td>CÁC KHOẢN</br>ĐÓNG GÓP</br>BHXH, BHYT,</br>BHTN, KPCĐ</td>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4=5+6+7</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8=4*0,1</td>
        <td>9=8*6</td>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td></td>
        <td style="text-align: center">TỔNG CỘNG</td>
        <td>{{dinhdangso($a_It['soluongbienche'])}}</td>
        <td>{{dinhdangsothapphan($a_It['heso'] + $a_It['tongpc'] +$a_It['ttbh_dv'],2)}}</td>
        <td>{{dinhdangsothapphan($a_It['heso'],2)}}</td>
        <td>{{dinhdangsothapphan($a_It['tongpc'],2)}}</td>
        <td>{{dinhdangsothapphan($a_It['ttbh_dv'],3)}}</td>
        <td>{{dinhdangso($a_It['chenhlech'])}}</td>
        <td>{{dinhdangso($a_It['chenhlech']*6)}}</td>
    </tr>
    @foreach ($ar_I as $dulieu )
        <tr  style="text-align: center">
            <td >{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['soluongbienche'])}}</td>
            <td>{{dinhdangsothapphan($dulieu['heso'] + $dulieu['tongpc'] +$dulieu['ttbh_dv'],3)}}</td>
            <td>{{dinhdangsothapphan($dulieu['heso'],3)}}</td>
            <td>{{dinhdangsothapphan($dulieu['tongpc'],3)}}</td>
            <td>{{dinhdangsothapphan($dulieu['ttbh_dv'],3)}}</td>
            <td style="text-align: rifht">{{dinhdangso($dulieu['chenhlech'])}}</td>
            <td style="text-align: rifht">{{dinhdangso($dulieu['chenhlech']*6)}}</td>
        </tr>
    @endforeach
</table>
<table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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