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
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th rowspan="2">Cấp</br>bậc</br>chức</br>vụ</th>
            <th colspan="{{$col}}">Hệ số phụ cấp</th>
            <th colspan="{{$col}}">Số tiền</th>
            <th rowspan="2">Tổng cộng</br>tiền lương</th>
            <th colspan="4">Các khoản phải khấu trừ</th>
            <th rowspan="2">Còn lại</th>
            <th style="width: 3%;"rowspan="2">Ký nhận</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">

            @foreach($a_phucap as $key=>$val)
                <th>{!!$val!!}</th>
            @endforeach

            @foreach($a_phucap as $key=>$val)
                <th>{!!$val!!}</th>
            @endforeach
            <th>BHXH</th>
            <th>BHYT</th>
            <th>KPCĐ</th>
            <th>Cộng</th>
        </tr>

        <tr>
            @for($i=1;$i<=10 + $col*2;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>

        <?php $i=1; ?>
        @for($i_ct=0;$i_ct<count($a_congtac);$i_ct++)
            <?php $a_luong = a_getelement_equal($a_canbo, array('mact'=>$a_congtac[$i_ct]['mact'])) ?>
            @if(count($a_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{9 + $col * 2}}">{{$a_ct[$a_congtac[$i_ct]['mact']]}}</td>
                </tr>
                @foreach($a_luong as $luong)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$luong['tencanbo']}}</td>
                        <td style="text-align: left">{{isset($a_cv[$luong['macvcq']])? $a_cv[$luong['macvcq']] : ''}}</td>

                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($luong[$key],5)}}</td>
                        @endforeach

                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangso($luong['st_'.$key])}}</td>
                        @endforeach

                        <td>{{dinhdangso($luong['ttl'] - $luong['giaml'] + $luong['bhct'])}}</td>
                        <td>{{dinhdangso($luong['stbhxh'])}}</td>
                        <td>{{dinhdangso($luong['stbhyt'])}}</td>
                        <td>{{dinhdangso($luong['stkpcd'])}}</td>
                        <td>{{dinhdangso($luong['ttbh'])}}</td>
                        <td>{{dinhdangso($luong['luongtn'])}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan(array_sum(array_column($a_luong,$key)) ,5)}}</td>
                    @endforeach

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangso(array_sum(array_column($a_luong,'st_'.$key)))}}</td>
                    @endforeach
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttl')) - array_sum(array_column($a_luong,'giaml')) + array_sum(array_column($a_luong,'bhct')))}}</td>

                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhxh')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stbhyt')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'stkpcd')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'ttbh')))}}</td>
                    <td class="money">{{dinhdangso(array_sum(array_column($a_luong,'luongtn')))}}</td>
                    <td></td>
                </tr>
            @endif
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan(array_sum(array_column($a_canbo,$key)) ,5)}}</td>
            @endforeach

            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangso(array_sum(array_column($a_canbo,'st_'.$key)))}}</td>
            @endforeach
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttl')) - array_sum(array_column($a_canbo,'giaml')) + array_sum(array_column($a_canbo,'bhct')))}}</td>

            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhxh')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stbhyt')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'stkpcd')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'ttbh')))}}</td>
            <td class="money">{{dinhdangso(array_sum(array_column($a_canbo,'luongtn')))}}</td>
            <td></td>
        </tr>
    </table>

    <table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center; font-style: italic" width="50%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%">Người lập bảng</td>
            <td style="text-align: center;" width="50%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{$m_dv['nguoilapbieu']}}</td>
            <td style="text-align: center;" width="50%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>

</body>
</html>