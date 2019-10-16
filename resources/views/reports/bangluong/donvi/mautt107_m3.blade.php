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
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;">
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>
    <?php $vuotkhung = false; ?>
    <table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 1%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th rowspan="2">Chức vụ</th>
            <th style="width: 3%;" rowspan="2">Mã ngạch</th>
            @foreach($a_phucap as $key=>$val)
                <th rowspan="2">{!!$val!!}</th>
            @endforeach
            <th rowspan="2">Cộng hệ số</th>
            <th rowspan="2">Lương, phụ cấp 01 tháng</th>
            <th colspan="2">Các chức danh bố trí kiêm nhiệm</th>
            <th style="width: 8%" rowspan="2">Tổng tiền</br>lương, phụ cấp</br>và trợ cấp</br>kiêm nhiệm</th>
            <th rowspan="2">Trích nộp bảo hiểm</th>
            <th rowspan="2">Số thực lĩnh</th>
            <th style="width: 5%;" rowspan="2">Ký nhận</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">
            <th>Chức danh</th>
            <th>Số tiền</th>
        </tr>

        <tr>
            @for($j=1;$j<=12 + $col;$j++)
                <th>{{$j}}</th>
            @endfor
        </tr>
        </thead>

        <?php $i=1;$stt=1; ?>
        @foreach($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact',$congtac->mact)?>
            @if(count($model_luong)> 0)
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{11 + $col}}">{{$congtac->tenct}}</td>
                </tr>
                @foreach($model_luong as $ct)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$ct->tencanbo}}</td>
                        <td style="text-align: left">{{isset($a_chucvu[$ct->macvcq]) ? $a_chucvu[$ct->macvcq]:'' }}</td>
                        <td style="text-align: left">{{$ct->msngbac}}</td>

                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                        @endforeach

                        <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                        <td>{{dinhdangso($ct->ttl)}}</td>
                        <!--td style="text-align: left">{{isset($a_chucvu[$ct->macvcq_kn]) ? $a_chucvu[$ct->macvcq_kn]:'' }}</td-->
                        <td style="text-align: left">{{$ct->macvcq_kn}}</td>
                        <td>{{dinhdangso($ct->ttl_kn)}}</td>
                        <td>{{dinhdangso($ct->ttl + $ct->ttl_kn)}}</td>

                        <td>{{dinhdangso($ct->ttbh)}}</td>
                        <td>{{dinhdangso($ct->luongtn + $ct->luongtn_kn)}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="4">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endforeach

                    <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                    <td></td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttl_kn'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('ttl') + $model_luong->sum('ttl_kn'))}}</td>

                    <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                    <td class="money">{{dinhdangso($model_luong->sum('luongtn') + $model_luong->sum('luongtn_kn'))}}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="4">Tổng cộng</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach

            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>

            <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
            <td></td>
            <td class="money">{{dinhdangso($model->sum('ttl_kn'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl') + $model->sum('ttl_kn'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('luongtn') + $model->sum('luongtn_kn'))}}</td>
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