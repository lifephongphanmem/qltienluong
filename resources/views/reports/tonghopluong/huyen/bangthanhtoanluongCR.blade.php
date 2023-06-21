@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:0 auto 25px; text-align: center;">
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
                BẢNG THANH TOÁN LƯƠNG
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{$thongtin['thang']}} năm {{$thongtin['nam']}}
            </td>
        </tr>
    </table>

    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1" style="margin: 20px auto; border-collapse: collapse;font:normal 12px Times, serif;">
        <tr style="padding-left: 2px;padding-right: 2px">
            <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
            <th style="width: 12%;" rowspan="3">Họ và tên</th>
            <th rowspan="3">Chức vụ</th>
            <th style="width: 3%;" rowspan="3">Mã số lương</th>
            <th style="width: 3%;" colspan="{{$col+3}}">Lương hệ số</th>
            <th colspan="3">Tiền truy linh do nâng lương</th>
            <th colspan="2">Trừ ốm đau, thai sản</th>
            <th rowspan="3">Tổng cộng tiền được hưởng</th>
            <th colspan="4">Các khoản trừ vào lương</th>
            <th rowspan="3">Thuế thu nhập cá nhân</th>
            <th rowspan="3">Giảm trừ gia cảnh</th>
            <th rowspan="3">Tổng số tiền lương còn lĩnh</th>
            <th rowspan="3">Ký nhận</th>
        </tr>
        <tr style="padding-left: 2px;padding-right: 2px">
            <th rowspan="2">Hệ số lương</th>
            <th colspan="{{$col}}">Hệ số phụ cấp</th>
            <th style="width: 3%;" rowspan="2">Cộng hệ số</th>
            <th style="width: 3%;" rowspan="2">Thành tiền</th>
            <th  rowspan="2">Lương</th>
            <th  rowspan="2">Thâm niên</th>
            <th  rowspan="2">Ưu đãi</th>
            <th  rowspan="2">Lương</th>
            <th  rowspan="2">Thâm niên</th>
            <th  >8%</th>
            <th  >1.5%</th>
            <th  >1%</th>
            <th  rowspan="2">Cộng</th>
        </tr>

        <tr style="padding-left: 2px;padding-right: 2px">

            @foreach($a_phucap as $key=>$val)
                <th >{!!$val!!}</th>
            @endforeach
            <th>BHXH</th>
            <th>BHYT</th>
            <th>BHTN</th>
        </tr>

        <tr>
            @for($i=1;$i<=21 + $col;$i++)
                <th>{{$i}}</th>
            @endfor
        </tr>
        @for($j=0;$j<count($a_nguon);$j++)
            <?php $i=1;
                $model_congtac = a_getelement($a_congtac,$a_nguon[$j]);
            ?>
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: left;" colspan="{{20 + $col}}">{{$a_nguon[$j]['tennguonkp']}}</td>
            </tr>
            @foreach($model_congtac as $congtac)
                <?php
                    $model_luong = $model->where('mact',$congtac['mact'])->where('manguonkp',$congtac['manguonkp']);
                        //dd($model_luong->toarray());
                ?>
                @if(count($model_luong)> 0)
                    <?php $stt=1; ?>
                    <tr style="font-weight: bold; font-style:italic ">
                        <td>{{convert2Roman($i++)}}</td>
                        <td style="text-align: left;" colspan="{{20+ $col}}">{{$congtac['tenct']}}</td>
                    </tr>
                    @foreach($model_luong as $ct)

                        <tr>
                            <td>{{$stt++}}</td>
                            <td style="text-align: left">{{$ct->tencanbo}}</td>
                            <td style="text-align: left">{{$ct->tencv}}</td>
                            <td style="text-align: left">{{$ct->msngbac}}</td>
                            <td style="text-align: right">{{dinhdangsothapphan($ct->heso,5)}}</td>

                            @foreach($a_phucap as $key=>$val)
                                <td>{{dinhdangsothapphan($ct->$key,5)}}</td>
                            @endforeach

                            <td>{{dinhdangsothapphan($ct->tonghs,5)}}</td>
                            <td>{{dinhdangso($ct->tonghop == "BANGLUONG"?$ct->ttl:0)}}</td>
                            <td>{{dinhdangso($ct->tonghop == "TRUYLINH"?$ct->ttl:0)}}</td>
                            <td>{{dinhdangso(0)}}</td>
                            <td>{{dinhdangso(0)}}</td>
                            <td>{{dinhdangso($ct->giaml)}}</td>
                            <td>{{dinhdangso(0)}}</td>
                            <td>{{dinhdangso($ct->ttl - $ct->giaml)}}</td>

                            <td>{{dinhdangso($ct->stbhxh)}}</td>
                            <td>{{dinhdangso($ct->stbhyt)}}</td>
                            <td>{{dinhdangso($ct->stbhtn)}}</td>
                            <td>{{dinhdangso($ct->ttbh)}}</td>
                            <td>{{dinhdangso(0)}}</td>
                            <td>{{dinhdangso(0)}}</td>
                            <td>{{dinhdangso($ct->tongtl - $ct->giaml-$ct->ttbh)}}</td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr style="font-weight: bold; text-align: center; font-style: italic">
                        <td colspan="4">Cộng</td>
                        <td>{{dinhdangsothapphan($model_luong->sum('heso') ,5)}}</td>
                        @foreach($a_phucap as $key=>$val)
                            <td>{{dinhdangsothapphan($model_luong->sum($key) ,5)}}</td>
                        @endforeach
                        <td>{{dinhdangsothapphan($model_luong->sum('tonghs') ,5)}}</td>

                        <td class="money">{{dinhdangso($model_luong->sum('ttl'))}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('giaml'))}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml'))}}</td>

                        <td class="money">{{dinhdangso($model_luong->sum('stbhxh'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('stbhyt'))}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('stbhtn'))}}</td>

                        <td class="money">{{dinhdangso($model_luong->sum('ttbh'))}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td>{{dinhdangso(0)}}</td>
                        <td class="money">{{dinhdangso($model_luong->sum('ttl') - $model_luong->sum('giaml')-($model_luong->sum('ttbh')))}}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        @endfor
        <tr style="font-weight: bold; text-align: center;">
            <td colspan="4">Tổng cộng</td>
            <td>{{dinhdangsothapphan($model->sum('heso') ,5)}}</td>
            @foreach($a_phucap as $key=>$val)
                <td>{{dinhdangsothapphan($model->sum($key) ,5)}}</td>
            @endforeach
            <td>{{dinhdangsothapphan($model->sum('tonghs') ,5)}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl'))}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td class="money">{{dinhdangso($model->sum('giaml'))}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhxh'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhyt'))}}</td>
            <td class="money">{{dinhdangso($model->sum('stbhtn'))}}</td>
            <td class="money">{{dinhdangso($model->sum('ttbh'))}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td>{{dinhdangso(0)}}</td>
            <td class="money">{{dinhdangso($model->sum('ttl') - $model->sum('giaml')-($model->sum('ttbh')))}}</td>

            <td></td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
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
@stop