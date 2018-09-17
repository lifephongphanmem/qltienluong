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
            @if($thongtin['phanloai'] == 'TRUYLINH')
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                    BẢNG THANH TOÁN TRUY LĨNH LƯƠNG VÀ PHỤ CẤP
                </td>
            @else
                <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                    BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
                </td>
            @endif

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
            <th colspan="{{$col+1}}">Hệ số phụ cấp</th>
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
                <th>Cộng hệ</br>số</th>
            @foreach($a_phucap as $key=>$val)
                <th>{!!$val!!}</th>
            @endforeach
            <th>BHXH</th>
            <th>BHYT</th>
            <th>KPCĐ</th>
            <th>Cộng</th>
        </tr>

        <tr>
            @for($i=1;$i<=11 + $col*2;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>

        <?php $i=1; ?>
        @foreach($model_congtac as $congtac)
            <?php
                $model_luong = $model->where('mact',$congtac->mact);
                $model_luong_st = $model_st->where('mact',$congtac->mact);
            ?>
            @if(count($model_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{10+ $col*2}}">{{$congtac->tenct}}</td>
                </tr>
                @foreach($model_luong as $ct)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$ct->tencanbo}}</td>
                        <td style="text-align: left">{{$ct->tencv}}</td>


                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @endforeach
                        <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                        @foreach($a_phucap as $key=>$val)
                            @if($ct->$key > 1000)
                                <td>{{dinhdangso($ct->$key)}}</td>
                            @else
                                <td>{{dinhdangso($ct->$key * $thongtin['luongcb'])}}</td>
                            @endif
                        @endforeach


                        <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                        <td>{{dinhdangso($ct->stbhxh)}}</td>
                        <td>{{dinhdangso($ct->stbhyt)}}</td>
                        <td>{{dinhdangso($ct->stkpcd)}}</td>
                        <td>{{dinhdangso($ct->ttbh)}}</td>
                        <td>{{dinhdangso($ct->luongtn)}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangso($model_luong_st->sum($key))}}</td>
                    @endforeach
                    <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') + $model_luong->sum('bhct'))}}</td>

                    <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('stkpcd'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>

                    <td></td>
                </tr>
            @endif
        @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="3">Tổng cộng</td>

            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangso($model_st->sum($key))}}</td>
            @endforeach
            <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct'))}}</td>

            <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
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