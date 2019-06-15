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
            padding-bottom: 5px;
        }

        .money tr td{
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
            padding: 5px;
        }

        p{
            padding: 5px;
        }

        span{
            text-transform: uppercase;
            font-weight: bold;
        }

        @media print {
            .in{
                display: none !important;
            }
        }
    </style>
</head>



<body style="font:normal 11px Times, serif;">
    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">

        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                DANH SÁCH CÁN BỘ NÂNG LƯƠNG NGẠCH BẬC
            </td>
        </tr>
        @if(isset($inputs['ngaytu']))
            <tr>
                <td colspan="2" style="text-align: center; font-style: italic">
                    Từ ngày: {{getDayVn($inputs['ngaytu'])}} đến ngày: {{getDayVn($inputs['ngayden'])}}
                </td>
            </tr>
        @endif
    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 11px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th rowspan="2" style="width: 3%;" >S</br>T</br>T</th>
            <th rowspan="2">Họ và tên</th>
            <th rowspan="2" style="width: 15%;">Chức vụ</th>
            <th rowspan="2" style="width: 10%;">Ngày nâng lương</th>
            <th rowspan="2" style="width: 10%;">Mã ngạch lương</th>
            <th colspan="3">Lương hiện tại</th>
            <th colspan="3">Lương mới</th>
        </tr>

        <tr>
            <th style="width: 5%;">Bậc</th>
            <th style="width: 5%;">Hệ số</th>
            <th style="width: 5%;">Vượt khung</th>

            <th style="width: 5%;">Bậc</th>
            <th style="width: 5%;">Hệ số</th>
            <th style="width: 5%;">Vượt khung</th>
        </tr>
        <tr>
            @for($i=1;$i<=11;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>

        @foreach($a_pl as $pl)
            <?php $i=1; $model_ct = $model->where('trangthai',$pl['trangthai'])->sortby('ngayden'); ?>
            <tr style="font-weight: bold; font-style:italic ">
                <td></td>
                <td style="text-align: left;" colspan="10">{{$pl['trangthai'] == 'DANANGLUONG'?'Danh sách cán bộ đã nâng lương':'Danh sách cán bộ chưa nâng lương'}}</td>
            </tr>
            @foreach($model_ct as $ct)
                <tr>
                    <td>{{$i++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td style="text-align: center">{{getDayVn($ct->ngayden)}}</td>
                    <td style="text-align: center">{{$ct->msngbac}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->bac)}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->heso,3)}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->vuotkhung)}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->bac_m)}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->heso_m,3)}}</td>
                    <td style="text-align: center">{{dinhdangsothapphan($ct->vuotkhung_m)}}</td>
                </tr>
            @endforeach
        @endforeach
    </table>

    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">

        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%">Người lập bảng</td>
            <td style="text-align: center;" width="50%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="50%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>