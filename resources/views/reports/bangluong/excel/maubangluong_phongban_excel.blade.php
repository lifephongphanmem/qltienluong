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

@foreach($model_pb as $k=>$v)
    <?php
        $model_lpb = $model->where('mapb',$k)
    ?>
    @if(count($model_lpb) > 0)
        <table>
            <tr>
                <th colspan="12">
                   Đơn vị chủ quản: {{$m_dv['tenct']}}
                </th>
            </tr>
            <tr>
                <th colspan="12">
                   Đơn vị: {{$m_dv['tendv']}}
                </th>
            </tr>
            <tr>
                <th colspan="12">
                    Mã đơn vị SDND: {{$m_dv['maqhns']}}
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
            @if($k != '' && $k != null)
                <tr>
                    <th colspan="12" style="text-align: center; font-style: italic">
                        Khối/Tổ công tác: {{$v}}
                    </th>
                </tr>
            @endif
        </table>

        <table>
            <tr>
                <td>S</br>T</br>T</td>
                <td>Mã số</br>công chức</td>
                <td>Họ và tên</td>
                <td>Cấp bậc</br>chức vụ</td>
                <td colspan="{{$col + 4}}">Lương hệ số</td>
                <td>Nghỉ việc</br>không được</br>hưởng lương</td>
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
                <td>Hệ số</br>lương</td>
                <td>Phụ cấp</br>thâm niên</br>vượt khung</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{!!$val!!}</td>
                @endforeach
                <td>Cộng</br>hệ số</td>
                <td>Thành tiền</td>

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
                <?php $model_luong = $model_lpb->where('mact',$congtac->mact)?>
                @if(count($model_luong)>0)
                    <?php $stt=1; ?>
                    <tr style="font-weight: bold;">
                            <td>{{convert2Roman($i++)}}</td>
                            <td style="text-align: left;" colspan="{{17+ $col}}">{{$congtac->tenct}}</td>
                        </tr>

                    @foreach($model_luong as $ct)
                        <tr>
                            <td>{{$stt++}}</td>
                            <td style="text-align: left">{{$ct->msngbac}}</td>
                            <td style="text-align: left">{{$ct->tencanbo}}</td>
                            <td style="text-align: left">{{$ct->tencv}}</td>

                            <td>{{dinhdangsothapphan(($ct->heso + $ct->hesott),5)}}</td>
                            <td>{{dinhdangsothapphan($ct->vuotkhung,5)}}</td>

                            @foreach($a_phucap as $key=>$val)
                                <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                            @endforeach

                            <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                            <td>{{dinhdangso($ct->ttl)}}</td>
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
                            <td colspan="4">Cộng</td>
                            <td>{{dinhdangsothapphan($model_luong->sum('heso') + $model_luong->sum('hesott') ,5)}}</td>
                            <td>{{dinhdangsothapphan($model_luong->sum('vuotkhung') ,5)}}</td>

                            @foreach($a_phucap as $key=>$val)
                                <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                            @endforeach

                            <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>

                            <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
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

            <tr style="font-weight: bold; text-align: center;">
                <td colspan="4">Tổng cộng</td>
                <td>{{dinhdangsothapphan($model_lpb->sum('heso') + $model_lpb->sum('hesott') ,5)}}</td>
                <td>{{dinhdangsothapphan($model_lpb->sum('vuotkhung') ,5)}}</td>

                @foreach($a_phucap as $key=>$val)
                    <td>{{dinhdangsothapphan($model_lpb->sum($key) ,5)}}</td>
                @endforeach

                <td>{{dinhdangsothapphan($model_lpb->sum('tonghs') ,5)}}</td>

                <td class="money">{{dinhdangso($model_lpb->sum('ttl'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('giaml'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('bhct'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('ttl') - $model_lpb->sum('giaml') + $model_lpb->sum('bhct'))}}</td>

                <td class="money">{{dinhdangso($model_lpb->sum('stbhxh'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('stbhyt'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('stkpcd'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('stbhtn'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('ttbh'))}}</td>
                <td class="money">{{dinhdangso($model_lpb->sum('luongtn'))}}</td>
                <td></td>
            </tr>
        </table>
        <p style="text-align: left; font-weight:bold;font-style: italic ">Tổng số tiền bằng chữ: {{Dbl2Str($model_lpb->sum('luongtn'))}}</p>
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
        <p style="page-break-before: always">
    @endif
@endforeach
</body>
</html>