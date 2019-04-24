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
                BẢNG TỔNG HỢP LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG CỦA ĐƠN VỊ
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>
    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th style="width: 3%;" rowspan="2">Mã ngạch</th>
            @foreach($a_phucap as $key=>$val)
                <th rowspan="2">{!!$val!!}</th>
            @endforeach
            <th rowspan="2">Cộng hệ số</th>
            <th rowspan="2">Tiền lương tháng</th>
            <th rowspan="2">Giảm trừ<br>lương</th>
            <th rowspan="2">Tiền lương thực tế</th>
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
            @for($i=1;$i<=13 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>


        @for($j=0;$j<count($a_nguon);$j++)
            <?php $i=1; ?>
            <?php
                $model_congtac = a_getelement($a_congtac,$a_nguon[$j]);
            ?>
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: left;" colspan="{{12 + $col}}">{{$a_nguon[$j]['tennguonkp']}}</td>
            </tr>
            @foreach($model_congtac as $congtac)
                <?php
                    $model_luong = $model->where('mact',$congtac['mact'])->where('manguonkp',$congtac['manguonkp']);
                ?>
                @if(count($model_luong)> 0)
                    <?php $stt=1; ?>
                    <tr style="font-weight: bold; font-style:italic ">
                        <td>{{convert2Roman($i++)}}</td>
                        <td style="text-align: left;" colspan="{{12+ $col}}">{{$congtac['tenct']}}</td>
                    </tr>
                    @foreach($model_luong as $ct)
                        <tr>
                            <td>{{$stt++}}</td>
                            <td style="text-align: left">{{$ct->tencanbo}}</td>
                            <td style="text-align: left">{{$ct->msngbac}}</td>

                            @foreach($a_phucap as $key=>$val)
                                <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                            @endforeach

                            <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                            <td>{{dinhdangso($ct->tongtl)}}</td>
                            <td>{{dinhdangso($ct->giaml)}}</td>
                            <td>{{dinhdangso($ct->tongtl - $ct->giaml)}}</td>

                            <td>{{dinhdangso($ct->stbhxh_dv)}}</td>
                            <td>{{dinhdangso($ct->stbhyt_dv)}}</td>
                            <td>{{dinhdangso($ct->stkpcd_dv)}}</td>
                            <td>{{dinhdangso($ct->stbhtn_dv)}}</td>

                            <td>{{dinhdangso($ct->ttbh_dv)}}</td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr style="font-weight: bold; text-align: center; font-style: italic">
                        <td colspan="3">Cộng</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                        @endforeach
                        <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>

                        <td class="money">{{dinhdangso($model_luong->sum('tongtl'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('tongtl') - $model_luong->sum('giaml'))}}</td>

                        <td class="money">{{dinhdangso($model_luong->sum('stbhxh_dv'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('stbhyt_dv'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('stkpcd_dv'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('stbhtn_dv'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('ttbh_dv'))}}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
            <td class="money">{{dinhdangso($model->sum('tongtl'))}}</td>
            <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
            <td class="money">{{dinhdangso($model->sum('tongtl') - $model->sum('giaml'))}}</td>

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
            <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', ngày ... tháng ... năm ...'}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="35%">Người lập bảng</td>
            <th style="text-align: center;" width="50%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="35%">{{$m_dv['cdlanhdao']}}</td>
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
            <td style="text-align: center;" width="35%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="35%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>