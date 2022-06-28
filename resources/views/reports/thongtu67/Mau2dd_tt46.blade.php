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
            <b>BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THỰC HIỆN TINH GIẢN BIÊN CHẾ, SÁP NHẬP ĐƠN VỊ <br> THEO NGHỊ QUYẾT SỐ 18-NQ/TW VÀ NGHỊ QUYẾT SỐ 19/NQ-TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG</b>
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
        <th style="width: 5%">STT</th>
        <th style="width: 23%">Phân loại đơn vị</th>
        <th>Tổng số đối tượng có mặt đến 31/12/2015</th>
        <th>Tổng số đói tượng có mặt đến 01/7/2017</th>
        <th>Quỹ lương, phụ cấp tháng 7 năm 2017 (lương 1,39)</th>
        <th>Tổng số đối tượng có mặt đến 01/7/2019</th>
        <th>Quỹ lương, phụ cấp tháng 7 năm 2018 (lương 1,39)</th>
        <th>Quỹ lương, phụ cấp tiết kiệm trong 1 tháng</th>
        <th>Kinh phí tiết kiệm được từ định mức chi hoạt động trong 1 tháng</th>
        <th>Quỹ lương, phụ cấp và định mức chi hoạt động tiết kiệm trong năm 2019</th>
    </tr>
    <tr>
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6=5-3</td>
        <td>7</td>
        <td>8</td>
    </tr>
    <tr style="font-weight: bold">
        <td></td>
        <td>TỔNG CỘNG</td>
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
            <td>{{dinhdangso($dulieu['dt15'])}}</td>
            <td>{{dinhdangso($dulieu['dt17'])}}</td>
            <td>{{dinhdangso($dulieu['luong17'])}}</td>
            <td>{{dinhdangso($dulieu['dt19'])}}</td>
            <td>{{dinhdangso($dulieu['luong19'])}}</td>
            <td>{{dinhdangso($dulieu['pcthang'])}}</td>
            <td>{{dinhdangso($dulieu['tkthang'])}}</td>
            <td>{{dinhdangso($dulieu['luong'])}}</td>
        </tr>
    @endforeach
</table>

<table id="data_footer" width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> (1) Đối với các đơn vị tự đảm bảo chi thường xuyên và chi đầu tư; tự đảm bảo chi thường xuyên, chỉ báo cáo số lượng biên chế tinh giản, không tổng hợp nhu cầu lương, định mức chi hoạt động tiết kiệm.</i>
        </td>

    </tr>
</table>
@stop