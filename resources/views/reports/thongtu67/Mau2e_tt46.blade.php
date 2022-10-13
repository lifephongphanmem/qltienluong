@extends('main_baocao')

@section('content')

<table id="data_header" width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
    <tr>
        <td style="text-align: left">
            @if ($inputs != '')
            <b>{{'Đơn vị: '.$m_dv->tendv}}</b> 
            @else
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ {{$m_dv->diadanh}}</b><br>
            @endif
        </td>
          
            
        <td style="text-align: right">
            <b>Biểu số 2e</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THAY ĐỔI CƠ CHÉ TỰ CHỦ TRONG NĂM 2019 <br> THEO NGHỊ QUYẾT CỐ 19-NQ/TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG</b>
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
        <th rowspan="2" style="width: 23%">Phân loại đơn vị sự nghiệp</th>
        <th rowspan="2">Số lượng đơn vị đến 31/12/2017</th>
        <th rowspan="2">Số lượng đơn vị đến 31/12/2019</th>
        <th colspan="2">Số lượng đơn vị thay đổi loại hình cơ chế tự chủ</th>
        <th rowspan="2">Kinh phí tiết kiệm được từ việc thay đổi cơ chế tự chủ trong 1 tháng (1)</th>
        <th rowspan="2">Kinh phí tiết kiệm trong năm 2019</th>
    </tr>
    <tr >
        <th >Tăng</th>
        <th >Giảm</th>
    </tr>
    <tr>
        <td>A</td>
        <td>B</td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
    </tr>
    <tr style="font-weight: bold; text-align: center">
        <td></td>
        <td>TỔNG CỘNG</td>
        <td>{{dinhdangso($a_It['tongsodonvi1'])}}</td>
        <td>{{dinhdangso($a_It['tongsodonvi2'])}}</td>
        <td>{{dinhdangso($a_It['tang'])}}</td>
        <td>{{dinhdangso($a_It['giam'])}}</td>
        <td style="text-align: right">{{dinhdangso($a_It['quy_tuchu'])}}</td>
        <td style="text-align: right">{{dinhdangso($a_It['kp_tk'])}}</td>

    </tr>
    @foreach ($ar_I as $dulieu )
        <tr style="text-align: center">
            <td>{{$dulieu['tt']}}</td>
            <td style="text-align: left">{{$dulieu['noidung']}}</td>
            <td>{{dinhdangso($dulieu['tongsodonvi1'])}}</td>
            <td>{{dinhdangso($dulieu['tongsodonvi2'])}}</td>
            <td>{{dinhdangso($dulieu['tang'])}}</td>
            <td>{{dinhdangso($dulieu['giam'])}}</td>
            <td style="text-align: right">{{dinhdangso($dulieu['quy_tuchu'])}}</td>
            <td style="text-align: right">{{dinhdangso($dulieu['kp_tk'])}}</td>
        </tr>
    @endforeach
</table>

<table id="data_footer" width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> (1) Phần ngân sách nhà nước giảm hỗ trợ cho đơn vị</i>
        </td>

    </tr>
    <tr>
        <td style="text-align: left;">
            <i>(2) Kinh phí tiết kiệm được không bao gồm phần kinh phí tiết kiệm từ việc tinh giản biên chế, sát nhập đơn vị đã được tổng hợp tại biểu 2đ.</i>
        </td>
    </tr>
</table>
@stop