@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">

            </td>
            <td style="text-align: center;">
                <b></b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_dv['tendv'] }} </b>
            </td>
            <td style="text-align: center; font-style: italic">
                {{-- (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính) --}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_dv->maqhns }}</b>
            </td>

            <td style="text-align: left; font-style: italic">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>{{ $thongtin['nguonkp'] }}</b>
            </td>

            <td style="text-align: left; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px;text-transform: uppercase">
                @if ($thongtin['innoidung'])
                    {{ $thongtin['noidung'] }}
                @else
                    DANH SÁCH LƯƠNG CÁN BỘ CÔNG CHỨC, CHUYÊN TRÁCH THÁNG {{ $thongtin['thang'] . '/' . $thongtin['nam'] }}. MỨC
                    LƯƠNG {{dinhdangso($thongtin['mucluong']).' đ'}}
                @endif
            </td>
        </tr>

    </table>
    <table id="data_body" class="money" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font:normal {{ $thongtin['cochu'] }}px Times, serif;">
        <thead>
            <tr style="padding-left: 2px;padding-right: 2px">
                <th style="width: 1%;" rowspan="2">S</br>T</br>T</th>
                <th style="width: 5%;" rowspan="2">Họ và tên</th>
                <th style="width: 1%;" rowspan="2">Chức</br>danh</th>
                <th style="width: 1%;" rowspan="2">Lương cơ sở</br>{{dinhdangso($thongtin['mucluong'])}}đ</br>tháng</th>
                <th rowspan="2" style="width: 2%;">Lương</th>
                <th style="width: 5%;" colspan="{{ $col }}">HỆ SỐ PHỤ CẤP</th>
                <th style="width: 5%;" colspan="{{ $col + 2 }}">SỐ TIỀN</th>
                <th style="width: 3%;" colspan="3">CÁC KHOẢN PHẢI TRẢ</th>
                <th style="width: 2%;" rowspan="2">BH cá</br>nhân nộp 8%</br>BHXH</br>1,5% BHYT</br>(6001+6101) </th>
                <th style="width: 2%;" rowspan="2">Tổng số</br>luong & PC</br>được nhận</th>

            </tr>

            <tr>
                @foreach ($a_phucap as $key => $val)
                    <th>{{ $val }}</th>
                @endforeach
                <th>Biên chế</th>
                <th>Hợp đồng</th>
                @foreach ($a_phucap as $key => $val)
                    <th>{{ $val }}</th>
                @endforeach
                <th>17,5% BHXH</th>
                <th>3% BHYT</th>
                <th>2% KPCD</th>

            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
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

                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}
                </th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
                </th>
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
                {{-- <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th> --}}
                <th>{{ $model_tm->where('mapc', 'stbhxh_dv')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'stbhyt_dv')->first()->tieumuc }}</th>
                {{-- <th>{{ $model_tm->where('mapc', 'stbhtn_dv')->first()->tieumuc }}</th> --}}
                <th>{{ $model_tm->where('mapc', 'stkpcd_dv')->first()->tieumuc }}</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <?php $i = 1;
        $stt = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>
            <tr style="font-weight: bold;text-align: right;">
                <td>{{ convert2Roman($i++) }}</td>
                <td style="text-align: left;">{{ $congtac->tenct }}</td>
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->count('tencv')) }}</td>
                <td></td>
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum('heso'), 2) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum($key), 2) }}
                @endforeach
                <td>{{ dinhdangso($model_luong->sum('st_heso')) }}</td>
                <td></td>
                @foreach ($a_phucap as $key => $val)
                    <?php $key = 'st_' . $key; ?>
                    <td>{{ dinhdangsothapphan($model_luong->sum($key)), 2 }}
                @endforeach
                <td>{{ dinhdangso($model_luong->sum('stbhxh_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('stbhyt_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('stkpcd_dv')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('ttbh')-$model_luong->sum('stbhtn')) }}</td>
                <td>{{ dinhdangso($model_luong->sum('luongtn')) }}</td>
            </tr>
            @foreach ($model_luong as $ct)
                <tr style="text-align: right">
                    <td>{{ $stt++ }}</td>
                    <td style="text-align: left">{{ $ct->tencanbo }}</td>
                    <td style="text-align: center">{{ $ct->tencv }}</td>
                    <td>{{ dinhdangso($ct->luongcoban) }}</td>
                    <td style="text-align: center">{{ dinhdangsothapphan($ct->heso, 2) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td  style="text-align: center">{{ dinhdangsothapphan($ct->$key, 2) }}</td>
                    @endforeach
                    <td>{{ dinhdangsothapphan($ct->st_heso, 2) }}</td>
                    <td></td>
                    @foreach ($a_phucap as $key => $val)
                        <?php $key = 'st_' . $key; ?>
                        <td>{{ dinhdangsothapphan($ct->$key, 2) }}</td>
                    @endforeach
                    <td>{{ dinhdangsothapphan($ct->stbhxh_dv, 2) }}</td>
                    <td>{{ dinhdangsothapphan($ct->stbhyt_dv, 2) }}</td>
                    <td>{{ dinhdangsothapphan($ct->stkpcd_dv, 2) }}</td>
                    <td>{{ dinhdangsothapphan($ct->ttbh-$ct->stbhtn, 2) }}</td>
                    <td>{{ dinhdangsothapphan($ct->luongtn, 2) }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{$col + 5}}">Cộng: {{$model->count('tencb')}} người {{$model->unique('tencv')->count()}} chức danh</td>

            <td>{{ dinhdangso($model->sum('st_heso')) }}</td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $key = 'st_' . $key; ?>
                <td>{{ dinhdangso($model->sum($key)) }}</td>
            @endforeach
            <td>{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('ttbh')-$model->sum('stbhtn')) }}</td>
            <td>{{ dinhdangso($model->sum('luongtn')) }}</td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 5 }}" style="text-align: center">Cá nhân nộp 8% BHXH</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 8) / 100) }}</td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <td>{{ $a_bh[$key] == 0 ? '' : dinhdangso(($model->sum($k) * 8) / 100) }}</td>
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 5}}">Cá nhân nộp 1,5% BHYT</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 1.5) / 100) }}</td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <td>{{ $a_bh[$key] == 0 ? '' : dinhdangso(($model->sum($k) * 1.5) / 100) }}</td>
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 5 }}">Cá nhân nộp 9,5% bảo hiểm</td>
            <td></td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <td></td>
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td>{{dinhdangso($model->sum('stbhxh')+$model->sum('stbhyt'))}}</td>
            <td></td>

        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 5 }}">Thực nhận chuyển khoản</td>
            <td></td>
            <td></td>

            @for ($i = 0; $i < $col; $i++)
                <td></td>
            @endfor
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="{{ $col + 5 }}"></td>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
            </th>
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
            <th>{{ $model_tm->where('mapc', 'stbhxh_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhyt_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stkpcd_dv')->first()->tieumuc }}</th>
            <td></td>
            <td></td>

        </tr>

    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8" style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="25%"></td>
            <td style="text-align: left;" width="30%"></td>
            <td style="text-align: center; font-style: italic" width="45%">{{$m_dv->diadanh .', '.Date2Str($thongtin['ngaylap'])}}</td>
        </tr>
        <tr style="font-weight: bold">
            <th style="text-align: center;" width="30%">{{$m_dv->cdketoan}}</th>
            <td style="text-align: center;" width="25%"></td>
            <td style="text-align: center;" width="45%">{{$m_dv['cdlanhdao']}}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="30%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="25%"></td>
            <td style="text-align: center;" width="45%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>

            <td style="text-align: center;" width="30%">{{$m_dv['ketoan']}}</td>
            <td style="text-align: center;" width="25%"></td>
            <td style="text-align: center;" width="45%">{{$m_dv['lanhdao']}}</td>
        </tr>
    </table>


@stop
