<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <link rel="shortcut icon" href="{{ url('images/LIFESOFT.png')}}" type="image/x-icon">
    <style type="text/css">
        body {
            font: normal 14px/16px time, serif;
        }
        table, p {
            width: 98%;
            margin: auto;
        }

        td, th {
            padding: 5px;
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

<body style="font:normal 12px Arial, serif;">

<table width="96%" border="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px;text-align: center">
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
            <b>BÁO CÁO NGUỒN THỰC HIỆN CCTL TIẾT KIỆM TỪ VIỆC THỰC HIỆN TINH GIẢN BIÊN CHẾ, SÁP NHẬP ĐƠN VỊ <br> THEO NGHỊ QUYẾT SỐ 10-NQ/TW VÀ NGHỊ QUYẾT SỐ 19/NQ-TW NGÀY 25/10/2017 CỦA BAN CHẤP HÀNH TRUNG ƯƠNG</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <i>(Ban hành kèm theo Thông tư số 68/2018/TT-BTC)</i>
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
<table width="96%" border ="1" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr >
        <th style="width: 5%">STT</th>
        <th style="width: 23%">Phân loại đơn vị</th>
        <th>Tổng số đối tượng có mặt đến 31/12/2015</th>
        <th>Tổng số đói tượng có mặt đến 01/7/2017</th>
        <th>Quỹ lương, phụ cấp tháng 7 năm 2017 (lương 1,30)</th>
        <th>Tổng số đối tượng có mặt đến 01/7/2018</th>
        <th>Quỹ lương, phụ cấp tháng 7 năm 2018 (lương 1,30)</th>
        <th>Quỹ lương, phụ cấp tiết kiệm trong 1 tháng</th>
        <th>Kinh phí tiết kiệm được từ định mức chi hoạt động trong 1 tháng</th>
        <th>Quỹ lương, phụ cấp và định mức chi hoạt động tiết kiệm trong năm 2018 so với năm 2017(1)</th>
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

<table width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i><b>Ghi chú:</b> (1) Tính toán dựa trên thời gian tinh giản biên chế thực tế trong năm (có thẻ nhỏ hơn 12 tháng). Tổng hợp vào biểu 4a.</i>
        </td>

    </tr>
    <tr>

        <td style="text-align: left;">
            <i>(2) Đối với các đơn vị tự đảm bảo chi thường xuyên và chi đầu tư; tự đảm bảo chi thường xuyên, chi báo cáo số lượng biên chế tinh giản, không tợp hợp nhu cầu lương, định mức chi hoạt động tiết kiệm.</i>
        </td>
    </tr>
</table>
</body>
</html>