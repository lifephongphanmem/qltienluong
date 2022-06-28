@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
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
                (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính)
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
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                @if($thongtin['innoidung'])
                    {{$thongtin['noidung']}}
                @else
                    BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>

    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="2">S</br>T</br>T</th>
            <th style="width: 3%;" rowspan="2">Mã</br>số</br>công</br>chức</th>
            <th style="width: 12%;" rowspan="2">Họ và tên</th>
            <th rowspan="2">Cấp</br>bậc</br>chức</br>vụ</th>
            <th rowspan="2">Mã số</br>ngạch</br>bậc</th>
            <th colspan="{{$col + 4}}">Lương hệ số</th>
            <th rowspan="2">Nghỉ việc</br>không được</br>hưởng lương</th>
            <th rowspan="2">BHXH trả</br>thay lương</th>
            <th rowspan="2">Tổng cộng</br>tiền lương</th>
            <th colspan="5">Các khoản phải khấu trừ</th>
            <th rowspan="2">Còn lại</th>
            <th style="width: 3%;"rowspan="2">Ký nhận</th>
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
            @for($i=1;$i<=19 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        </thead>
        <?php $i=1; ?>
        @foreach($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact',$congtac->mact)?>
            @if(count($model_luong)> 0)
                <?php $stt=1; ?>
                <tr style="font-weight: bold;">
                    <td>{{convert2Roman($i++)}}</td>
                    <td style="text-align: left;" colspan="{{18+ $col}}">{{$congtac->tenct}}</td>
                </tr>
                @foreach($model_luong as $ct)
                    <tr>
                        <td>{{$stt++}}</td>
                        <td style="text-align: left">{{$ct->macongchuc}}</td>
                        <td style="text-align: left">{{$ct->tencanbo}}</td>
                        <td style="text-align: left">{{$ct->tencv}}</td>
                        <td style="text-align: left">{{$ct->msngbac}}</td>
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
                    <td colspan="5">Cộng</td>
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
            <td colspan="5">Tổng cộng</td>
            <td>{{dinhdangsothapphan($model->sum('heso') + $model->sum('hesott') ,5)}}</td>
            <td>{{dinhdangsothapphan($model->sum('vuotkhung') ,5)}}</td>

            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach

            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>

            <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
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

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="35%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="35%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
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

@stop