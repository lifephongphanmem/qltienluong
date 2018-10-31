<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>

    <style type="text/css">
        body {
            font: normal 12px/14px time, serif;
        }

        tr > td {
            border: 1px solid;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th colspan="6"><b>Đơn vị: {{$m_dv['tendv']}}</b></th>
            <th colspan="9">
                <b>Mẫu số C02a - HD</b>
            </th>
        </tr>

        <tr>
            <th colspan="6">
                <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
            </th>
            <th colspan="9">
                Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
            </th>
        </tr>

        <tr>
            <th colspan="15" style="text-align: center; font-weight: bold; font-size: 20px;">
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ CÁC KHOẢN PHỤ CẤP THEO LƯƠNG,</br>CÁC KHOẢN TRÍCH NỘP THEO LƯƠNG
            </th>
        </tr>

        <tr>
            <th colspan="15" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </th>
        </tr>

    </table>

    <table>
        <tr>
            <td rowspan="2">S</br>T</br>T</td>
            <td rowspan="2">Họ và tên</td>
            <td rowspan="2">Mã ngạch</td>
            @foreach($a_phucap as $key=>$val)
                <td rowspan="2">{!!$val!!}</td>
            @endforeach
            <td rowspan="2">Cộng hệ số</td>
            <td rowspan="2">Tiền lương tdáng</td>
            <td rowspan="2">Ngày hưởng lương thực tế</td>

            <td colspan="2">BHXH</td>
            <td colspan="2">BHYT</td>
            <td colspan="2">BHTN</td>
            <td colspan="4">KPCĐ</td>
            
            <td rowspan="2">Thuế TNCN</td>
            <td rowspan="2">Giảm trừ gia cảnh</td>
            <td rowspan="2">Số thực lĩnh</td>
            <td rowspan="2">Ghi chú</td>
        </tr>

        <tr>
            <th></th>
            <th></th>
            <th></th>
            @foreach($a_phucap as $val)
                <th></th>
            @endforeach
            <th></th>
            <th></th>
            <th></th>

            <td>Trừ vào CP</td>
            <td>Trừ vào lương</td>
            <td>Trừ vào CP</td>
            <td>Trừ vào lương</td>
            <td>Trừ vào CP</td>
            <td>Trừ vào lương</td>
            <td>Trừ vào CP</td>
            <td>Trừ vào lương</td>
            <td>Số phải nộp công đoàn cấp trên</td>
            <td>Số để lại chi đơn vị</td>

            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>


        <tr>
            @for($i=1;$i<=20 + $col;$i++)
                <td>{{$i}}</td>
            @endfor
        </tr>

        <?php $i=1; ?>
        @foreach($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact',$congtac->mact)?>
            @if(count($model_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{19+ $col}}">{{$congtac->tenct}}</td>
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
                        <td>{{$ct->ttl}}</td>
                        <td>{{$ct->ttl - $ct->giaml + $ct->bhct}}</td>

                        <td>{{$ct->stbhxh_dv}}</td>
                        <td>{{$ct->stbhxh}}</td>
                        <td>{{$ct->stbhyt_dv}}</td>
                        <td>{{$ct->stbhyt}}</td>

                        <td>{{$ct->stbhtn_dv}}</td>
                        <td>{{$ct->stbhtn}}</td>
                        <td>{{$ct->stkpcd_dv}}</td>
                        <td>{{$ct->stkpcd}}</td>
                        <td>{{$ct->stkpcd_dv}}</td>
                        <td>{{$ct->stkpcd}}</td>
                        <td>{{$ct->thuetn}}</td>
                        <td></td>
                        <td>{{$ct->luongtn}}</td>
                        <td></td>
                    </tr>
                @endforeach

                <tr style="font-weight: bold; text-align: center; font-style: italic">
                    <td colspan="3">Cộng</td>
                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                    @endforeach
                    <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>


                    <td class="money">{{$model_luong->sum('ttl')}}</td>
                    <td class="money">{{$model_luong->sum('ttl') - $model_luong->sum('giaml') + $model_luong->sum('bhct')}}</td>

                    <td class="money">{{$model_luong->sum('stbhxh_dv')}}</td>
                    <td class="money">{{$model_luong->sum('stbhxh')}}</td>
                    <td class="money">{{$model_luong->sum('stbhyt_dv')}}</td>
                    <td class="money">{{$model_luong->sum('stbhyt')}}</td>
                    <td class="money">{{$model_luong->sum('stbhtn_dv')}}</td>
                    <td class="money">{{$model_luong->sum('stbhtn')}}</td>
                    <td class="money">{{$model_luong->sum('stkpcd_dv')}}</td>
                    <td class="money">{{$model_luong->sum('stkpcd')}}</td>
                    <td class="money">{{$model_luong->sum('stkpcd_dv')}}</td>
                    <td class="money">{{$model_luong->sum('stkpcd')}}</td>
                    <td class="money">{{$model_luong->sum('thuetn')}}</td>
                    <td></td>
                    <td class="money">{{$model_luong->sum('luongtn')}}</td>
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
            <td class="money">{{$model->sum('ttl')}}</td>
            <td class="money">{{$model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct')}}</td>

            <td class="money">{{$model->sum('stbhxh_dv')}}</td>
            <td class="money">{{$model->sum('stbhxh')}}</td>
            <td class="money">{{$model->sum('stbhyt_dv')}}</td>
            <td class="money">{{$model->sum('stbhyt')}}</td>

            <td class="money">{{$model->sum('stbhtn_dv')}}</td>
            <td class="money">{{$model->sum('stbhtn')}}</td>
            <td class="money">{{$model->sum('stkpcd_dv')}}</td>
            <td class="money">{{$model->sum('stkpcd')}}</td>
            <td class="money">{{$model->sum('stkpcd_dv')}}</td>
            <td class="money">{{$model->sum('stkpcd')}}</td>
            <td class="money">{{$model->sum('thuetn')}}</td>
            <td></td>
            <td class="money">{{$model->sum('luongtn')}}</td>
            <td></td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="5"></th>
            <th colspan="5"></th>
            <th colspan="5">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</th>
        </tr>
        <tr style="font-weight: bold">
            <th colspan="5">Người lập bảng</th>
            <th colspan="5">{{$m_dv->cdketoan}}</th>
            <th colspan="5">{{$m_dv['cdlanhdao']}}</th>
        </tr>
        <tr style="font-style: italic">
            <th colspan="5">(Ghi rõ họ tên)</th>
            <th colspan="5">(Ghi rõ họ tên)</th>
            <th colspan="5">(Ký tên, đóng dấu)</th>
        </tr>
        <tr>
            <th></th>
        </tr>

        <tr>
            <th colspan="5">{{$m_dv['nguoilapbieu']}}</th>
            <th colspan="5">{{$m_dv['ketoan']}}</th>
            <th colspan="5">{{$m_dv['lanhdao']}}</th>
        </tr>
    </table>

</body>
</html>