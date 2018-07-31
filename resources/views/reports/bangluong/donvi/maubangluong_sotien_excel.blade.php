<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{$pageTitle}}</title>
    <style type="text/css">
        tr > td {
            border: 1px solid;
        }
    </style>
</head>


<body>

<table>
    <tr>
        <th colspan="6"><b>Đơn vị: {{$m_dv['tendv']}}</b></th>
        <th colspan="6">
            <b>Mẫu số C02a - HD</b>
        </th>
    </tr>

    <tr>
        <th colspan="6">
            <b>Mã đơn vị SDNS: {{$m_dv->maqhns}}</b>
        </th>
        <th colspan="6">
            (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính)
        </th>
    </tr>

    <tr>
        <th colspan="12" style="text-align: center; font-weight: bold; font-size: 20px;">
            BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
        </th>
    </tr>

    <tr>
        <th colspan="12" style="text-align: center; font-style: italic">
            Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
        </th>
    </tr>

</table>

<table>

    <tr>
        <td>STT</td>
        <td>Mã số</br>công chức</td>
        <td>Họ và tên</td>
        <td>Cấp bậc</br>chức vụ</td>
        <td>Mã số</br>ngạch</br>bậc</td>
        <td colspan="{{$col + 3}}">Lương hệ số</td>
        <td>Nghỉ việc không được hưởng lương</td>
        <td>BHXH trả</br>thay lương</td>
        <td>Tổng cộng</br>tiền lương</td>
        <td colspan="5">Các khoản phải khấu trừ</td>
        <td>Còn lại</td>
        <td>Ký nhận</td>
    </tr>

    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>Hệ số</br>lương</td>
        <td>Hệ số</br>phụ</br>cấp</td>

        @foreach($a_phucap as $key=>$val)
            <td>{!!$val!!}</td>
        @endforeach
        <th>Cộng</br>hệ số</th>
        <th></th>
        <th></th>
        <th></th>

        <td>BHXH</td>
        <td>BHYT</td>
        <td>KPCĐ</td>
        <td>BHTN</td>
        <td>Cộng</td>
        <th></th>
        <th></th>
    </tr>

    <tr>
        @for($i=1;$i<=18 + $col;$i++)
        <td>{{$i}}</td>
        @endfor
    </tr>

    <?php $i=1; ?>
    @foreach($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact',$congtac->mact)?>
        @if(count($model_luong) > 0)
            <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{17 + $col}}">{{$congtac->tenct}}</td>
                </tr>

            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td style="text-align: left">{{$ct->macongchuc}}</td>
                    <td style="text-align: left">{{$ct->tencanbo}}</td>
                    <td style="text-align: left">{{$ct->tencv}}</td>
                    <td style="text-align: left">{{$ct->msngbac}}</td>
                    <td>{{dinhdangso(($ct->heso + $ct->hesott))}}</td>
                    <td>{{dinhdangso($ct->hesopc)}}</td>

                    @foreach($a_phucap as $key=>$val)
                        <td>{{dinhdangso($ct->$key)}}</td>
                    @endforeach

                    <td>{{dinhdangso($ct->tonghs)}}</td>

                    <td> {{dinhdangso($ct->giaml)}}</td>
                    <td>{{dinhdangso($ct->bhct)}}</td>
                    <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stkpcd)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                    <td></td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; text-align: center; font-style: italic">
                <td colspan="5">Cộng</td>
                <td>{{dinhdangso($model_luong->sum('heso') + $model_luong->sum('hesott'))}}</td>
                <td>{{dinhdangso($model_luong->sum('hesopc'))}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangso($model_luong->sum($key))}}</td>
                @endforeach

                <td>{{dinhdangso($model_luong->sum('tonghs'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') + $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stkpcd'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('luongtn'))}}</td>

                <td></td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td colspan="5">Tổng cộng</td>
        <td>{{dinhdangso($model->sum('heso') + $model->sum('hesott'))}}</td>
        <td>{{dinhdangso($model->sum('hesopc'))}}</td>

        @foreach($a_phucap as $key=>$val)
            <td>{{dinhdangso($model->sum($key))}}</td>
        @endforeach

        <td>{{dinhdangso($model->sum('tonghs'))}}</td>

        <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
        <td class="money">{{dinhdangso($model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') + $model->sum('bhct'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stkpcd'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('luongtn'))}}</td>
        <td></td>
    </tr>
</table>

<table>
    <tr>
        <th colspan="6"></th>
        <th colspan="6">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</th>
    </tr>
    <tr style="font-weight: bold">
        <th colspan="6">Người lập bảng</th>
        <th colspan="6">{{$m_dv['cdlanhdao']}}</th>
    </tr>
    <tr style="font-style: italic">
        <th colspan="6">(Ghi rõ họ tên)</th>
        <th colspan="6">(Ký tên, đóng dấu)</th>
    </tr>
    <tr>
        <th></th>
    </tr>

    <tr>
        <th colspan="6">{{$m_dv['nguoilapbieu']}}</th>
        <th colspan="6">{{$m_dv['lanhdao']}}</th>
    </tr>
</table>

</body>
</html>