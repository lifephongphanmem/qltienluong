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


<body style="font:normal 12px Times, serif;">

<table class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
    <tr>
        <th colspan="12">
            Đơn vị chủ quản: {{$m_dv->tenct}}
        </th>
    </tr>

    <tr>
        <th colspan="12">
            Đơn vị: {{$m_dv->tendv}}
        </th>
    </tr>

    <tr>
        <th colspan="12">
            Mã đơn vị SDNS: {{$m_dv->maqhns}}
        </th>
    </tr>

    <tr>
        <th colspan="12">
            BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
        </th>
    </tr>
    <tr>
        <th colspan="12">
            Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
        </th>
    </tr>
</table>

<table class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;">
    <tr>
        <td>STT</td>
        <td>Mã số</br>CBCC, viên</br>chức</td>
        <td>Họ và tên</td>
        <td>Cấp bậc</br>chức vụ</td>
        <td colspan="14">Lương hệ số</td>
        <td>Nghỉ việc</br>không được</br>hưởng lương</td>
        <td>BHXH trả</br>thay lương</td>
        <td>Tổng cộng</br>tiền lương</td>
        <td colspan="5">Các khoản phải khấu trừ</td>
        <td>Tổng tiền lương và BHXH còn được nhận</td>
    </tr>

    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>Hệ số</br>lương</td>
        <td>Phụ cấp</br>chức vụ</td>
        <td colspan="10">Hệ số phụ cấp khác</td>
        <td>Cộng</br>hệ số</td>
        <td>Thành tiền</td>
        <th></th>
        <th></th>
        <th></th>

        <td>BHXH</td>
        <td>BHYT</td>
        <td>BHTN</td>
        <td>Thuế</br>thu</br>nhập</td>
        <td>Cộng</td>
        <th></th>

    </tr>

    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>Phụ cấp</br>khu vực</td>
        <td>Phụ cấp</br>thu hút</td>
        <td>Phụ cấp</br>ưu đãi</td>
        <td>Phụ cấp</br>trách nhiệm</td>
        <td colspan="2">PCTNVK</td>
        <td colspan="2">PCTNNG</td>
        <td>Phụ cấp</br>lâu năm</td>
        <td>Phụ cấp</br>độc hại</td>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>

        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <td>%</td>
        <td>HS</td>
        <td>%</td>
        <td>H</td>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>

    <tr>
        <td>A</td>
        <td>B</td>
        <td>C</td>
        <td>D</td>
        @for($i=1;$i<=23;$i++)
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
                <td colspan="26">{{$congtac->tenct}}</td>
            </tr>
            @foreach($model_luong as $ct)
                <tr>
                    <td>{{$stt++}}</td>
                    <td>{{$ct->msngbac}}</td>
                    <td>{{$ct->tencanbo}}</td>
                    <td>{{$ct->tencv}}</td>
                    <td>{{dinhdangsothapphan(($ct->heso + $ct->hesott),5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pccv,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pckv,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->pcth,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcudn,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->pctn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->vk,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->vuotkhung,5)}}</td>

                    <td>{{dinhdangsothapphan($ct->tnn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pctnn,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcthni,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->pcdh,5)}}</td>
                    <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                    <td>{{dinhdangso($ct->ttl)}}</td>

                    <td> {{dinhdangso($ct->giaml)}}</td>
                    <td>{{dinhdangso($ct->bhct)}}</td>
                    <td>{{dinhdangso($ct->ttl - $ct->giaml + $ct->bhct)}}</td>

                    <td>{{dinhdangso($ct->stbhxh)}}</td>
                    <td>{{dinhdangso($ct->stbhyt)}}</td>
                    <td>{{dinhdangso($ct->stbhtn)}}</td>
                    <td class="money"></td>
                    <td>{{dinhdangso($ct->ttbh)}}</td>
                    <td>{{dinhdangso($ct->luongtn)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4">Cộng</td>
                <td>{{dinhdangsothapphan($model_luong->sum('heso') + $model_luong->sum('hesott'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pccv'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pckv'),5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('pcth'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcudn'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctn'),5)}}</td>

                <td></td>
                <td>{{dinhdangsothapphan($model_luong->sum('vuotkhung'),5)}}</td>
                <td></td>
                <td>{{dinhdangsothapphan($model_luong->sum('pctnn'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcthni'),5)}}</td>
                <td>{{dinhdangsothapphan($model_luong->sum('pcdh'),5)}}</td>

                <td>{{dinhdangsothapphan($model_luong->sum('tonghs'),5)}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>
                <td class="money"></td>
                <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml') - $model_luong->sum('bhct')- $model_luong->sum('ttbh'))}}</td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td colspan="4">Tổng cộng</td>
        <td>{{dinhdangsothapphan($model->sum('heso') + $model->sum('hesott'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pccv'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pckv'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcth'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcudn'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pctn'),5)}}</td>
        <td></td>
        <td>{{dinhdangsothapphan($model->sum('vuotkhung'),5)}}</td>
        <td></td>
        <td>{{dinhdangsothapphan($model->sum('pctnn'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcthni'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('pcdh'),5)}}</td>
        <td>{{dinhdangsothapphan($model->sum('tonghs'),5)}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
        <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
        <td class="money">{{dinhdangso($model->sum('bhct'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct'))}}</td>

        <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
        <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
        <td class="money"></td>
        <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
        <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml') - $model->sum('bhct') - $model->sum('ttbh'))}}</td>

    </tr>
</table>
<p style="text-align: left; font-weight:bold;font-style: italic ">Tổng số tiền bằng chữ: {{Dbl2Str($model->sum('luongtn'))}}</p>
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