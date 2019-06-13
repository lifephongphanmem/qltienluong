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

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$thongtin->tendv}}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$thongtin->maqhns}}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                BẢNG TỔNG HỢP DỰ TOÁN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG
            </td>
        </tr>

    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Phân loại công tác</th>
            <th style="width: 3%;" rowspan="2">Số lượng cán bộ</th>
            @foreach($a_phucap as $key=>$val)
                <th rowspan="2">{!!$val!!}</th>
            @endforeach
            <th rowspan="2">Cộng hệ số</th>
            <th rowspan="2">Tiền lương tháng</th>
            <th colspan="5">Các khoản trích nộp theo lương</th>

            <th style="width: 5%" rowspan="2">Ghi chú</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            <th>BHXH</th>
            <th>BHYT</th>
            <th>KPCĐ</th>
            <th>BHTN</th>
            <th>Cộng</th>
        </tr>

        <tr>
            @for($i=1;$i<=11 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        <?php $j = 0;?>
    @foreach($m_phanloai as $phanloai)
        <?php
                $j++;
                $model_phanloai = $model->where('phanloai',$phanloai->maphanloai);
                $donvi = $m_dv->where('maphanloai', $phanloai->maphanloai);
            ?>
            <tr style="font-weight: bold;">
                <td>{{convert2Roman($j)}}</td>
                <td style="text-align: left;" colspan="{{10 + $col}}">{{$phanloai->tenphanloai}}</td>
            </tr>
            @foreach($donvi as $dv)
                <tr style="font-weight: bold;">
                    <td></td>
                    <td style="text-align: left" colspan="{{10 + $col}}">{{$dv->tendv}}</td>
                </tr>
                <?php $model_ct =  $model_phanloai->where('madv',$dv->madv);
                $i=1;
                $ar_dv = $m_donvi->where('madv',$dv->madv)->first()->toarray() ;
                $a_congtac = a_getelement($model_congtac,$ar_dv);
            ?>
                <?php $stt=1; ?>
            @foreach($a_congtac as $congtac)
                <?php
                    $model_luong = $model_ct->where('mact',$congtac['mact']);
                ?>
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$a_ct[$congtac['mact']]}}</td>
                        <td style="text-align: right">{{$model_luong->count('id')/12}}</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($model_luong->sum($key),5)}}</td>
                        @endforeach

                        <td>{{dinhdangsothapphan($model_luong->sum('tonghs'),5)}}</td>
                        <td>{{dinhdangso($model_luong->sum('luongtn'))}}</td>
                        <td>{{dinhdangso($model_luong->sum('stbhxh_dv'))}}</td>
                        <td>{{dinhdangso($model_luong->sum('stbhyt_dv'))}}</td>
                        <td>{{dinhdangso($model_luong->sum('stkpcd_dv'))}}</td>
                        <td>{{dinhdangso($model_luong->sum('stbhtn_dv'))}}</td>
                        <td>{{dinhdangso($model_luong->sum('ttbh_dv'))}}</td>
                        <td></td>
                    </tr>
            @endforeach
                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_ct->sum($key) ,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($model_ct->sum('tonghs') ,5)}}</td>
                    <td class="money">{{dinhdangso($model_ct->sum('luongtn'))}}</td>

                    <td class="money">{{dinhdangso($model_ct->sum('stbhxh_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_ct->sum('stbhyt_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_ct->sum('stkpcd_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_ct->sum('stbhtn_dv'))}}</td>
                    <td class="money">{{dinhdangso($model_ct->sum('ttbh_dv'))}}</td>
                    <td></td>
                </tr>
         @endforeach
    @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
            <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>

            <td class="money">{{dinhdangso($model->sum('stbhxh_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhyt_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhtn_dv'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh_dv'))}}</td>
            <td></td>
        </tr>

    </table>

    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">{{$thongtin->diadanh .', ngày ... tháng ... năm ...'}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{$thongtin->cdketoan}}</th>
            <td style="text-align: center;" width="35%">{{$thongtin->cdlanhdao}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="35%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="35%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="35%">{{$thongtin['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$thongtin['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$thongtin['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>