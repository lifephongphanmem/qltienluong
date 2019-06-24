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
            <b>Biểu số 4b</b>
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
<p style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">TỔNG HỢP NHU CẦU, NGUỒN THỰC HIỆN {{$model_thongtu->cancu}}</p>
<p style="text-align: center; font-style: italic">(Ban hành kèm theo {{$model_thongtu->tenttqd}})</p>
<p style="text-align: right; font-style: italic">Đơn vị: đồng</p>
<table cellspacing="0" cellpadding="0" border="1" style="margin: 10px auto; border-collapse: collapse;">
    <tr style="padding-left: 2px;padding-right: 2px">
        <th style="width: 5%;padding-left: 2px;padding-right: 2px" rowspan="2">TT</th>
        <th style="padding-left: 2px;padding-right: 2px" rowspan="2">CHỈ TIÊU</th>
        <th style="width: 10%;padding-left: 2px;padding-right: 2px" rowspan="2">NHU CẦU KINH PHÍ THỰC HIỆN CCTL NĂM 2017</th>
        <th style="width: 40%;padding-left: 2px;padding-right: 2px" colspan="5">NGUỒN TỪ TIẾT KIỆM 10% CHI THƯỜNG XUYÊN VÀ NGUỒN THU ĐỂ LẠI ĐƠN VỊ</th>

    </tr>

    <tr style="padding-left: 2px;padding-right: 2px">
        <th>TỔNG SỐ</th>
        <th>TIẾT KIỆM 10% CHI THƯỜNG XUYÊN</th>
        <th>HỌC PHÍ</th>
        <th>VIỆN PHÍ</th>
        <th>NGUỒN THU TỪ ĐƠN VỊ HÀNH CHÍNH, SỰ NGHIỆP KHÁC</th>
    </tr>

    <tr style="font-weight: bold; text-align: center">
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        <td>8</td>
    </tr>
    @foreach($data as $dulieu)
        <tr>
            <td>{{$dulieu['tt']}}</td>
            <td>{{$dulieu['noidung']}}</td>
            <td class="money">{{dinhdangso($dulieu['nhucau'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonkp'])}}</td>
            <td class="money">{{dinhdangso($dulieu['tietkiem'])}}</td>
            <td class="money">{{dinhdangso($dulieu['hocphi'])}}</td>
            <td class="money">{{dinhdangso($dulieu['vienphi'])}}</td>
            <td class="money">{{dinhdangso($dulieu['nguonthu'])}}</td>
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