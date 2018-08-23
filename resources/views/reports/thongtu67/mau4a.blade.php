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

        .money {
            text-align: right;
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
            <b>Biểu số 4a</b>
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
<p style="text-align: center; font-weight: bold; font-size: 20px;">BÁO CÁO NGUỒN KINH PHÍ ĐỂ THỰC HIỆN CẢI CÁCH TIỀN LƯƠNG NĂM 2017</p>
<p style="text-align: center; font-style: italic">(Ban hành kèm theo Thông tư số 67/2017/TT-BTC)</p>
<p style="text-align: right; font-style: italic">Đơn vị: {{($inputs['donvitinh']==1?"Đồng":($inputs['donvitinh']==2?"Nghìn đồng":"Triệu đồng"))}}</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 10px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;padding-left: 2px;padding-right: 2px">STT</th>
        <th style="padding-left: 2px;padding-right: 2px">NỘI DUNG</th>
        <th style="width: 20%;padding-left: 2px;padding-right: 2px">SỐ TIỀN</th>

    </tr>


    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td>2</td>
        <td>3</td>
    </tr>
    <tr style="font-weight: bold;">
        <td>A</td>
        <td>NGUỒN THỰC HIỆN CẢI CÁCH TIỀN LƯƠNG NĂM 2017</td>
        <td class="money">{{dinhdangso($a_TC['A'],0,$inputs['donvitinh'])}}</td>
    </tr>
    @foreach($a_A as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['sotien'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold;">
        <td>B</td>
        <td>TỔNG NHU CẦU NĂM 2017</td>
        <td class="money">{{dinhdangso($a_TC['BI'] + $a_TC['BII'],0,$inputs['donvitinh'])}}</td>
    </tr>

    <tr style="font-weight: bold;">
        <td>I</td>
        <td>Tổng nhu cầu kinh phí tăng thêm để thực hiện cải cách tiền lương theo Nghị định số
            47/2017/NĐ-CP và Nghị định số 76/2017/NĐ-CP</td>
        <td class="money">{{dinhdangso($a_TC['BI'],0,$inputs['donvitinh'])}}</td>
    </tr>
    @foreach($a_BI as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['sotien'],0,$inputs['donvitinh'])}}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold;">
        <td>II</td>
        <td>Nhu cầu thực hiện một số loại phụ cấp, trợ cấp theo quy định</td>
        <td class="money">{{dinhdangso($a_TC['BII'],0,$inputs['donvitinh'])}}</td>
    </tr>
        @foreach($a_BII as $dulieu)
            <tr>
                <td>{{$dulieu['tt']}}</td>
                <td>{{$dulieu['noidung']}}</td>
                <td class="money">{{dinhdangso($dulieu['sotien'],0,$inputs['donvitinh'])}}</td>
            </tr>
        @endforeach
    <tr style="font-weight: bold;">
        <td>C</td>
        <td>CHÊNH LỆCH NHU CẦU VÀ NGUỒN NĂM 2017</td>
        <td class="money">{{dinhdangso($a_TC['A'] -$a_TC['BI'] - $a_TC['BII'],0,$inputs['donvitinh'])}}</td>
    </tr>
    <tr>
        <td>1</td>
        <td>Phần thiếu nguồn ngân sách trung ương hỗ trợ</td>
        <td></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Nguồn thực hiện cải cách tiền lương còn dư</td>
        <td></td>
    </tr>
</table>

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
    <tr>
        <td style="text-align: left;" width="50%"></td>
        <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........</td>
    </tr>
    <tr style="font-weight: bold">
        <td style="text-align: center;" width="50%"></td>
        <td style="text-align: center;" width="50%">{{strtoupper($m_dv->cdlanhdao)}}</td>
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

</body>
</html>