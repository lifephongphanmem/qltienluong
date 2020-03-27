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
            <td  style="text-align: left;width: 60%">

            </td>
            <td  style="text-align: center;">
                <b>Mẫu số C02a - HD</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}}</b>
            </td>
            <td style="text-align: center; font-style: italic">
                Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
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
            @if($m_bl->noidung != '')
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase;">
                    {{$m_bl->noidung}}
                </td>
            @else
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                    BẢNG THANH TOÁN TIỀN PHỤ CẤP
                </td>
            @endif
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 2%;">S</br>T</br>T</th>
                <th rowspan="2" style="width: 20%;">Họ và tên</th>
                <th rowspan="2" style="width: 10%;">Chức vụ</th>

                <th colspan="{{$col}}">Hệ số</th>
                <th colspan="{{$col}}">Số tiền</th>
                <th rowspan="2">Thành tiền</th>
                <th rowspan="2">Phụ cấp</br>01 ngày</br>làm việc</th>
                <th rowspan="2" style="width: 5%;">Số ngày</br>làm việc</th>
                <th rowspan="2">Tổng tiền</th>
                <th rowspan="2" style="width: 10%">Ghi chú</th>
            </tr>

            <tr>
                @foreach($a_phucap as $key=>$val)
                    <th>{!!$val!!}</th>
                @endforeach

                @foreach($a_phucap as $key=>$val)
                    <th>{!!$val!!}</th>
                @endforeach
            </tr>

            <tr>
                @for($i=1;$i<= 9 + $col;$i++)
                    <th>{{$i}}</th>
                @endfor
            </tr>
        </thead>

        <?php $i=1; ?>

            @foreach($model as $ct)
                <tr>
                    <td>{{$i++}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{isset($a_cv[$ct->macvcq]) ? $a_cv[$ct->macvcq] : ''}}</td>

                    @foreach($a_phucap as $k=>$v)
                        <?php $hs = 'hs_'.$k; ?>
                        <td>{{dinhdangsothapphan($ct->$hs,5)}}</td>
                    @endforeach

                    @foreach($a_phucap as $key=>$val)
                        <?php $hs = 'st_'.$key; ?>
                        <td>{{dinhdangsothapphan($ct->$hs,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($ct->tiencong,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->tiencongngay,5)}}</td>
                    <td>{{$ct->songaytruc}}</td>
                    <td>{{dinhdangso($ct->ttl)}}</td>
                    <td></td>
                </tr>
            @endforeach

            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="3">Cộng</td>
                @foreach($a_phucap as $key=>$val)
                    <?php $hs = 'hs_'.$key; ?>
                    <td>{{dinhdangsothapphan($model->sum($hs) ,5)}}</td>
                @endforeach

                @foreach($a_phucap as $k=>$v)
                    <?php $hs = 'st_'.$k; ?>
                    <td>{{dinhdangsothapphan($model->sum($hs) ,5)}}</td>
                @endforeach
                <td>{{dinhdangsothapphan($model->sum('tiencong') ,5)}}</td>
                <td>{{dinhdangsothapphan($model->sum('tiencongngay') ,5)}}</td>
                <td>{{dinhdangsothapphan($model->sum('songaytruc') ,5)}}</td>
                <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
                <td></td>
            </tr>
    </table>

    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="25%">Người lập bảng</td>
            <th style="text-align: center;" width="30%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="45%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="25%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="45%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;" width="25%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="45%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>