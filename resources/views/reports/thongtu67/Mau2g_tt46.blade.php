@extends('main_baocao')

@section('content')
<table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b><br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2đ</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>BÁO CÁO QUỸ LƯƠNG, PHỤ CẤP ĐỐI VỚI LAO ĐỘNG THEO HỢP ĐỒNG KHU VỰC HÀNH CHÍNH VÀ ĐƠN VỊ SỰ NGHIỆP</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <i>(Ban hành kèm theo Thông tư số 46/2019/TT-BTC ngày 23 tháng 7 năm 2019 của Bộ Tài chính)</i>
        </td>
    </tr>
    <tr>
        <td>
        </td>
        <td style="text-align: right">
            <i>Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</i>
        </td>
    </tr>
</table>
<table id="data_body" width="96%" border ="1" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr >
        <th rowspan="2" style="width: 5%">STT</th>
        <th rowspan="2" style="width: 20%">NỘI DUNG</th>
        <th rowspan="2">TỔNG SỐ ĐỐI TƯỢNG HƯỞNG LƯƠNG CÓ MẶT ĐẾN 01/7/2019 (1)</th>
        <th colspan="4">QUỸ TIỀN LƯƠNG, PHỤ CẤP VÀ CÁC KHOẢN ĐÓNG GÓP THÁNG 7/2019 THEO NGHỊ ĐỊNH SỐ 38/2019/NĐ-CP (2)</th>
    </tr>
    <tr >
        <th >TỔNG CỘNG</th>
        <th >LƯƠNG THEO NGẠCH, BẬC CHỨC VỤ</th>
        <th >TỔNG CÁC KHOẢN PHỤ CẤP</th>
        <th >CÁC KHOẢN ĐÓNG GÓP BHXH, BHYT, BHTN, KPCĐ</th>
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
    <tr style="font-weight: bold;text-align: right">
        <td></td>
        <td style=" text-align: center">TỔNG CỘNG</td>
        <td>{{dinhdangso($a_It['soluongbienche'])}}</td>
        <td>{{dinhdangso($a_It['heso'] + $a_It['tongpc'] + $a_It['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['heso'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['tongpc'],0,$inputs['donvitinh'])}}</td>
        <td>{{dinhdangso($a_It['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
    </tr>
    @foreach($ar_I as $dulieu)
        <tr style=" text-align: right">
            <td style=" text-align: center">{{$dulieu['tt']}}</td>
            <td style=" text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['soluongbienche'])}}</td>
            <td>{{dinhdangso($dulieu['heso'] + $dulieu['tongpc'] + $dulieu['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['heso'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['tongpc'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['ttbh_dv'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
</table>

<table id="data_footer" width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> </i>
        </td>
    </tr>
    <tr>
        <td style="text-align: left;">
            <i> (1) Bao gồm đối tượng theo hợp đồng nghị định 68/2000/NĐ-CP và hợp đồng lao động khác</i>
        </td>

    </tr>
    <tr>

        <td style="text-align: left;">
            <i>(2) Không tổng hợp vào biểu 2a và biểu 4a</i>
        </td>
    </tr>
    <tr>

        <td style="text-align: left;">
            <i>(3) Bao gồm các đơn vị tự đảm bảo chi thường xuyê và chi đầu tư, đơn vị tự đảm bảo chi thường xuyên</i>
        </td>
    </tr>
</table>
@stop