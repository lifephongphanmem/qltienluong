@extends('main_baocao')

@section('content')

@foreach($model_pb as $k=>$v)
    <?php
        $model_lpb = $model->where('mapb',$k)
    ?>
    @if(count($model_lpb) > 0)
        <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
            <tr>
                <td  style="text-align: left;width: 60%">
                    <b>Đơn vị chủ quản: {{$m_dv['tenct']}}</b>
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
                    <b>Mã đơn vị SDND: {{$m_dv['maqhns']}}</b>
                </td>
                <td style="text-align: center; font-style: italic">

                </td>
            </tr>
        </table>
        <p style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
            @if($thongtin['innoidung'])
                {{$thongtin['noidung']}}
            @else
                BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
            @endif
        </p>
        <p style="text-align: center; font-style: italic">Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}</p>
        @if($k != '' && $k != null)
            <p style="text-align: center; font-style: italic">Khối/Tổ công tác: {{$v}}</p>
        @endif
        <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
            <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 3%;" rowspan="2">Mã số</br>công chức</th>
                <th style="width: 15%;" rowspan="2">Họ và tên</th>
                <th style="width: 3%;" rowspan="2">Cấp bậc</br>chức vụ</th>
                <th colspan="{{$col + 4}}">Lương hệ số</th>
                <th style="width: 4%;" rowspan="2">Nghỉ việc</br>không được</br>hưởng lương</th>
                <th style="width: 4%;" rowspan="2">BHXH trả</br>thay lương</th>
                <th style="width: 6%;" rowspan="2">Tổng cộng</br>tiền lương</th>
                <th colspan="5">Các khoản phải khấu trừ</th>
                <th style="width: 6%;" rowspan="2">Còn lại</th>
                <th style="width: 4%;" rowspan="2">Ký nhận</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th>Hệ số</br>lương</th>
                <th>Phụ cấp</br>thâm niên</br>vượt khung</th>

                @foreach($a_phucap as $key=>$val)
                    <th>{!!$val!!}</th>
                @endforeach

                <th>Cộng</br>hệ số</th>
                <th>Thành tiền</th>

                <th>BHXH</th>
                <th>BHYT</th>
                <th>KPCĐ</th>
                <th>BHTN</th>
                <th>Cộng</th>
            </tr>

            <tr>
                @for($i=1;$i<=18 + $col;$i++)
                    <th>{{$i}}</th>
                @endfor
            </tr>
            </thead>

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
        <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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
        <p style="page-break-before: always">
    @endif
@endforeach
@stop