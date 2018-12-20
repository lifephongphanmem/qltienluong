<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi" xmlns="http://www.w3.org/1999/html">

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
            <b>UỶ BAN NHÂN DÂN TỈNH, THÀNH PHỐ</b><br>
        </td>
        <td style="text-align: right">
            <b>Biểu số 2c</b><br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>BÁO CÁO NHU CẦU CHÊNH LỆCH SAU KHI ĐIỀU CHỈNH MỨC LƯƠNG CƠ SỞ TỪ 1,15 TRIỀU ĐỒNG/THÁNG LÊN 1,21 TRIỆU ĐỒNG/THÁNG ĐỂ BẢO LƯU MỨC</br> LƯƠNG ĐỐI VỚI NGƯỜI THU NHẬP THẤP ĐÃ ĐIỀU CHỈNH THEO NGHỊ ĐỊNH 17/2015/NĐ-CP</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <i>(Ban hành kèm theo Thông tư số 67/2017/TT-BTC)</i>
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
        <th style="width: 30%">Nội dung</th>
        <th>Đối tượng có hệ số lương ngạch bậc, lương chức vụ từ 2,34 trờ xuống có mặt 01/01/2017 có chênh lệch tăng thêm (Người)</th>
        <th>Tổng HS tiền lương ngạch bậc của số đối tượng có hệ số lương từ 2,34 trở xuống có mặt 01/1/2017</th>
        <th>Tổng hệ số phụ cấp của số đối tượng có hệ số lương từ 2,34 trở xuống</th>
        <th>Bổ sung chênh lệch do điều chỉnh mức lương cơ sở 1,21 triệu đồng/ tháng để giữ bằng thu nhập thấp tháng 4/2016</th>
        <th>Nhu cầu bổ sung chênh lệch 6 tháng năm 2017</th>
    </tr>
    <tr>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7 = 6 * 6T</td>
    </tr>
    <tr style="font-weight: bold">
        <td></td>
        <td>TỔNG CỘNG</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr style="font-weight: bold;">
        <td >I</td>
        <td style="text-align: left">KHU VỰC HCSN, ĐẢNG, ĐOÀN THỂ</td>
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
            <td>{{dinhdangso($dulieu['dt'])}}</td>
            <td>{{dinhdangso($dulieu['hstl'])}}</td>
            <td>{{dinhdangso($dulieu['hspc'])}}</td>
            <td>{{dinhdangso($dulieu['cl'],0,$inputs['donvitinh'])}}</td>
            <td>{{dinhdangso($dulieu['nc'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <?php print_r($ar_II);?>
    <tr style="font-weight: bold;">
        <td >II</td>
        <td style="text-align: left">CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC XÃ</td>
        <td style=" text-align: right">{{dinhdangso($ar_II['dt'])}}</td>
        <td style=" text-align: right">{{dinhdangso($ar_II['hstl'])}}</td>
        <td style=" text-align: right">{{dinhdangso($ar_II['hspc'])}}</td>
        <td style=" text-align: right">{{dinhdangso($ar_II['cl'],0,$inputs['donvitinh'])}}</td>
        <td style=" text-align: right">{{dinhdangso($ar_II['nc'],0,$inputs['donvitinh'])}}</td>
    </tr>
</table>

<table width="96%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td style="text-align: left;">
            <i>(1) Thực hiện theo khoản 3 Điều 2 Thông tư số 103/2016/TT-BTC ngày 29/6/2016 từ tháng 01 đến hết tháng 6 năm 2017.</i>
        </td>

    </tr>
</table>
<table width="96%" border ="0" cellspacing="0" cellpadding="4" style="margin:0 auto 20px; text-align: center;">
    <tr>
        <td ></td>
        <td style="font-style: italic; width: 50%">Ngày... tháng... năm ...</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold">CHỦ TỊCH ỦY BAN NHÂN DÂN TỈNH, THÀNH PHỐ</td>
    </tr>
    <tr>
        <td></td>
        <td style="font-style: italic">(Ký tên, đóng dấu)</td>
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