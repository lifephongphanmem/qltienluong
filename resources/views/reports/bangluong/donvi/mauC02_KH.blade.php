@extends('main_baocao')
@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b>Mẫu số C02</b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_dv['tendv']}} </b>
            </td>
            <td style="text-align: center; font-style: italic">
                Ban hành kèm theo Thông tư số 107/2017/TT- BTC ngày 10/10/2017 của Bộ Tài chính
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_dv->maqhns }}</b>
            </td>

            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                @if ($thongtin['innoidung'])
                    {{ $thongtin['noidung'] }}
                @else
                    BẢNG THANH TOÁN TIỀN LƯƠNG VÀ PHỤ CẤP
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-style: italic">
                Tháng {{ $thongtin['thang'] }} năm {{ $thongtin['nam'] }}
            </td>
        </tr>

    </table>
    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{$thongtin['cochu']}}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 1%;" rowspan="5">S</br>T</br>T</th>
                <th style="width: 10%;" rowspan="5">Họ và tên</th>
                <th style="width: 1%;" rowspan="5">Chức</br>danh</th>
                <th style="width: 1%;" rowspan="5">Lương</br>tối</br>thiểu</th>
                <th style="width: 5%;" colspan="{{ $col == 0?2:$col + 1 }}">HỆ SỐ</th>
                <th style="width: 5%;" colspan="{{ $col == 0?5:$col + 4 }}">THÀNH TIỀN</th>
                <th style="width: 3%;" rowspan="2" colspan="4">CÁC KHOẢN PHẢI TRẢ</th>
                <th style="width: 2%;" rowspan="5">Tổng số</br>luong & PC</br>được nhận</th>
                <th style="width: 2%;" rowspan="2">BH cá</br>nhân nộp</th>
                <th style="width: 2%;" rowspan="5">Số tiền</br>thực nhận</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="4">Lương</th>
                <th colspan="{{ $col==0?1:$col }}">PHỤ CẤP</th>
                <th colspan="2">TIỀN LƯƠNG</th>
                <th colspan="2">TIỀN CÔNG</th>
                <th colspan="{{ $col==0?1:$col }}">PHỤ CẤP</th>
            </tr>
            <tr>
                @if ($col == 0)
                    <th></th>
                @else
                @foreach ($a_phucap as $key => $val)
                <th >{{ $val }}</th>
            @endforeach 
                @endif

                {{-- <th>Khác (HDTS)</th> --}}
                <th >Biên chế</th>
                <th >Lương BC tăng thêm</th>
                <th >Hợp đồng thường xuyên</th>
                <th >HĐ thường xuyên tăng thêm</th>
                @if ($col == 0)
                <th></th>
            @else
                @foreach ($a_phucap as $key => $val)
                    <th>{{ $val }}</th>
                @endforeach
                @endif
                {{-- <th>Khác (HDTS)</th> --}}
                <th >17,5% BHXH</th>
                <th >3% BHYT</th>
                <th >1% BHTN</th>
                <th >2% KPCD</th>
                <th rowspan="2">8% BHXH</br>1,5% BHYT</br>1% BHTN</th>
            </tr>
           
            <tr>
                @if(!empty($a_tieumuc))
                @foreach ($a_phucap as $key => $val)
                    @if ($key == 'vuotkhung' || $key == 'pctnn')
                        <?php $m_tm = $model_tm->where('mapc', 'vuotkhung,pctnn'); ?>
                        <?php $key = 'vuotkhung,pctnn'; ?>
                    @else
                        <?php $m_tm = $model_tm->where('mapc', $key); ?>
                    @endif
                    @if (in_array($key, $a_tm))
                        @foreach ($m_tm as $v)
                            <th>{{ $v->tieumuc }}</th>
                        @endforeach
                    @else
                        <th></th>
                    @endif
                @endforeach
                @else
                    <th></th>
                @endif
                {{-- <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th> --}}
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
                </th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
                </th>
                @if(!empty($a_tieumuc))
                @foreach ($a_phucap as $key => $val)
                    @if ($key == 'vuotkhung' || $key == 'pctnn')
                        <?php $m_tm = $model_tm->where('mapc', 'vuotkhung,pctnn'); ?>
                        <?php $key = 'vuotkhung,pctnn'; ?>
                    @else
                        <?php $m_tm = $model_tm->where('mapc', $key); ?>
                    @endif
                    @if (in_array($key, $a_tm))
                        @foreach ($m_tm as $v)
                            <th>{{ $v->tieumuc }}</th>
                        @endforeach
                    @else
                        <th></th>
                    @endif
                @endforeach
                @else
                <th></th>
                @endif
                {{-- <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th> --}}
                <th>{{ $model_tm->where('mapc', 'stbhxh_dv')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'stbhyt_dv')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'stbhtn_dv')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'stkpcd_dv')->first()->tieumuc }}</th>
            </tr>

        </thead>
        <?php $i = 1;
        $stt = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>
            <tr style="font-weight: bold;text-align: right;">
                <td>{{ convert2Roman($i++) }}</td>
                <td style="text-align: left;">{{ $congtac->tenct }}</td>
                <td style="text-align: center">{{ dinhdangso($model_luong->count('tencv')) }}</td>
                <td></td>
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum('heso'), 3) }}</td>
                @if ($col == 0)
                    <td></td>
                @else
                @foreach ($a_phucap as $key => $val)
                @if (in_array($key,['pccovu','pclt','pcudn','pctnn','vuotkhung']))
                <td style="text-align: center"></td>
                @else
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum($key), 3) }}
                @endif
                @endforeach
                @endif

                {{-- <td></td> --}}
                <td>{{ dinhdangso($model_luong->sum('st_heso')) }}</td>
                <td></td>
                <td>{{ dinhdangso($model_luong->sum('st_luonghd')) }}</td>
                <td></td>
                @if ($col == 0)
                <td></td>
            @else
                @foreach ($a_phucap as $key => $val)
                    <?php $key = 'st_' . $key; ?>
                    <td>{{ dinhdangsothapphan($model_luong->sum($key)), 3 }}
                @endforeach
                @endif
                {{-- <td></td> --}}
                <td>{{ dinhdangso($model_luong->sum('stbhxh_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('stbhyt_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('stbhtn_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('stkpcd_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('ttl')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('ttbh')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('luongtn')) }}</td>
            </tr>
            @foreach ($model_luong as $ct)
                <tr style="text-align: right">
                    <td>{{ $stt++ }}</td>
                    <td style="text-align: left">{{ $ct->tencanbo }}</td>
                    <td style="text-align: center">{{ $ct->tencv }}</td>
                    <td class="text-right">{{ dinhdangso($ct->luongcoban) }}</td>
                    <td style="text-align: center">{{ dinhdangsothapphan($ct->heso, 3) }}</td>
                    @if ($col == 0)
                        <td></td>
                    @else
                    @foreach ($a_phucap as $key => $val)
                    @if (in_array($key,['pccovu','pclt','pcudn','pctnn','vuotkhung']))
                    <?php $k='hs_'.$key?>
                    <td style="text-align: center">{{ $ct->$key== 0?'':(dinhdangso($ct->$k) == 0?'':dinhdangso($ct->$k).'%') }}</td>
                    @else
                    <td style="text-align: center">{{ dinhdangsothapphan($ct->$key, 3) }}</td>
                    @endif
                    @endforeach
                    @endif

                    {{-- <td></td> --}}
                    <td>{{ dinhdangso($ct->st_heso) }}</td>
                    <td></td>
                    <td>{{ dinhdangso($ct->st_luonghd) }}</td>
                    <td></td>
                    @if ($col == 0)
                    <td></td>
                @else
                    @foreach ($a_phucap as $key => $val)
                        <?php $key = 'st_' . $key; ?>
                        <td>{{ dinhdangsothapphan($ct->$key, 3) }}</td>
                    @endforeach
                    @endif
                    {{-- <td></td> --}}
                    <td>{{ dinhdangso($ct->stbhxh_dv) }}</td>
                    <td>{{ dinhdangso($ct->stbhyt_dv) }}</td>
                    <td>{{ dinhdangso($ct->stbhtn_dv) }}</td>
                    <td>{{ dinhdangso($ct->stkpcd_dv) }}</td>
                    <td>{{ dinhdangso($ct->ttl) }}</td>
                    <td>{{ dinhdangso($ct->ttbh) }}</td>
                    <td>{{ dinhdangso($ct->luongtn) }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="font-weight: bold;text-align: right">
            <td colspan="2">Tổng cộng</td>
            <td style="text-align: center">{{ dinhdangso($model->count('tencv')) }}</td>
            <td></td>
            <td style="text-align: center">{{ dinhdangsothapphan($model->sum('heso'), 3) }}</td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <td style="text-align: center">{{ dinhdangsothapphan($model->sum($key), 3) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td>{{ dinhdangso($model->sum('st_heso')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('st_luonghd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <?php $key = 'st_' . $key; ?>
                <td>{{ dinhdangso($model->sum($key)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td>{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('ttl')) }}</td>
            <td>{{ dinhdangso($model->sum('ttbh')) }}</td>
            <td>{{ dinhdangso($model->sum('luongtn')) }}</td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <?php $model_bh=$model->where('ttbh','!=',0) ?>
            <td colspan="{{ $col ==0?6:$col + 5 }}" style="text-align: center">Chuyển nộp 8% BHXH</td>
            {{-- <td>{{ dinhdangso(($model_bh->sum('st_heso') * 8) / 100) }}</td> --}}
            <td>{{ dinhdangso($model->sum('bhxh_bc')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('bhxh_lhuonghd') ) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <?php
                    $bhxh_pc='bhxh_'.$key; 
                ?>
                <td>{{ dinhdangso($model->sum($bhxh_pc)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('stbhxh') ) }}</td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{$col ==0?6:$col + 5 }}">Chuyển nộp 1,5% BHYT</td>
            <td>{{ dinhdangso($model->sum('bhyt_bc')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('bhyt_luonghd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key;
                $bhyt_pc='bhyt_'.$key;
                 ?>
                <td>{{ dinhdangso($model->sum($bhyt_pc)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('stbhyt') ) }}</td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Chuyển nộp 1% BHTN</td>
            <td>{{ dinhdangso($model->sum('bhtn_bc') ) }}</td>
            <td></td>
            <td>{{dinhdangso($model->sum('bhtn_luonghd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key;
                 $bhtn_pc='bhtn_'.$key;
                 ?>
                <td>{{ dinhdangso($model->sum($bhtn_pc)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('stbhtn') ) }}</td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Cộng 10,5% BH</td>
            <td>{{ dinhdangso($model->sum('ttbh_bc')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('ttbh_luonghd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; 
                 $ttbh_pc='ttbh_'.$key;
                ?>
                <td>{{ dinhdangso($model->sum($ttbh_pc)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('ttbh')) }}</td>
            <td></td>
        </tr>

        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Chuyển nộp 17,5% BHXH + 3% BHYT + 1% BHTN</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @for ($i = 0; $i < $col; $i++)
                <td></td>
            @endfor
            @endif

            {{-- <td></td> --}}
            <td>{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Chuyển nộp 2% KPCD</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @for ($i = 0; $i < $col; $i++)
                <td></td>
            @endfor
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Số chuyển qua thẻ ATM</td>
            <td>{{ dinhdangso($model->sum('chuyenkhoan_bc')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('chuyenkhoan_hd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
            <?php $pc_ctck='ctck_'.$key ?>
            <td>{{ dinhdangso($model->sum($pc_ctck)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('chuyenkhoan')) }}</td>

        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col ==0?6:$col + 5 }}">Nhận tiền mặt</td>
            <td>{{ dinhdangso($model->sum('nhantienmat_bc')) }}</td>
            <td></td>
            <td>{{ dinhdangso($model->sum('nhantienmat_hd')) }}</td>
            <td></td>
            @if ($col == 0)
            <td></td>
        @else
            @foreach ($a_phucap as $key => $val)
            <?php $pc_cttm='cttm_'.$key ?>
            <td>{{ dinhdangso($model->sum($pc_cttm)) }}</td>
            @endforeach
            @endif
            {{-- <td></td> --}}
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('nhantienmat')) }}</td>

        </tr>
        <tr>
            <td colspan="{{ $col ==0?6:$col + 5 }}"></td>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
            </th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
            </th>
            @if ($col == 0)
                <th></th>
            @else
            @foreach ($a_phucap as $key => $val)
            @if ($key == 'vuotkhung' || $key == 'pctnn')
                <?php $m_tm = $model_tm->where('mapc', 'vuotkhung,pctnn'); ?>
                <?php $key = 'vuotkhung,pctnn'; ?>
            @else
                <?php $m_tm = $model_tm->where('mapc', $key); ?>
            @endif
            @if (in_array($key, $a_tm))
                @foreach ($m_tm as $v)
                    <th>{{ $v->tieumuc }}</th>
                @endforeach
            @else
                <th></th>
            @endif
        @endforeach
            @endif

            {{-- <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th> --}}
            <th>{{ $model_tm->where('mapc', 'stbhxh_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhyt_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhtn_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stkpcd_dv')->first()->tieumuc }}</th>
            <td></td>
            <td></td>
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
