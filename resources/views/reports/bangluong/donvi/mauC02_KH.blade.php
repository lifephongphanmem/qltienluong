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
                {{-- (Ban hành kèm theo QĐ số 19/2006/QĐ-BTC ngày 30/3/2006 và Thông tư số 185/2010/TT-BTC ngày 15/11/2010 của Bộ Tài chính) --}}
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
                <th style="width: 5%;" rowspan="5">Họ và tên</th>
                <th style="width: 1%;" rowspan="5">Chức</br>danh</th>
                <th style="width: 1%;" rowspan="5">Lương</br>tối</br>thiểu</th>
                <th style="width: 5%;" colspan="{{ $col + 2 }}">HỆ SỐ</th>
                <th style="width: 5%;" colspan="{{ $col + 5 }}">THÀNH TIỀN</th>
                <th style="width: 3%;" rowspan="2" colspan="4">CÁC KHOẢN PHẢI TRẢ</th>
                <th style="width: 2%;" rowspan="5">Tổng số</br>luong & PC</br>được nhận</th>
                <th style="width: 2%;" rowspan="2">BH cá</br>nhân nộp</th>
                <th style="width: 2%;" rowspan="5">Số tiền</br>thực nhận</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="4">Lương</th>
                <th colspan="{{ $col + 1 }}">PHỤ CẤP</th>
                <th colspan="2">TIỀN LƯƠNG</th>
                <th colspan="2">TIỀN CÔNG</th>
                <th colspan="{{ $col + 1 }}">PHỤ CẤP</th>
            </tr>
            <tr>
                @foreach ($a_phucap as $key => $val)
                    <th rowspan="2">{{ $val }}</th>
                @endforeach
                <th rowspan="2">Khác (HDTS)</th>
                <th rowspan="2">Biên chế</th>
                <th rowspan="2">Lương BC tăng thêm</th>
                <th rowspan="2">Hợp đồng thường xuyên</th>
                <th rowspan="2">HĐ thường xuyên tăng thêm</th>
                @foreach ($a_phucap as $key => $val)
                    <th rowspan="{{ $a_bh[$key] == 0 ? '' : 2 }}">{{ $val }}</th>
                @endforeach
                <th>Khác (HDTS)</th>
                <th rowspan="2">17,5% BHXH</th>
                <th rowspan="2">3% BHYT</th>
                <th rowspan="2">1% BHTN</th>
                <th rowspan="2">2% KPCD</th>
                <th rowspan="3">8% BHXH</br>1,5% BHYT</br>1% BHTN</th>
            </tr>
            <tr>
                @foreach ($a_phucap as $key => $val)
                    @if ($a_bh[$key] == 0)
                        <th>Không tính BH</th>
                    @endif
                @endforeach
                <th>Không tính BH</th>


            </tr>
            <tr>
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
                <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
                <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
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
                <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th>
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
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->count('tencv')) }}</td>
                <td></td>
                <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum('heso'), 2) }}</td>
                @foreach ($a_phucap as $key => $val)
                    <td style="text-align: center">{{ dinhdangsothapphan($model_luong->sum($key), 2) }}
                @endforeach
                <td></td>
                <td>{{ dinhdangso($model_luong->sum('st_heso')) }}</td>
                <td></td>
                <td></td>
                <td></td>
                @foreach ($a_phucap as $key => $val)
                    <?php $key = 'st_' . $key; ?>
                    <td>{{ dinhdangsothapphan($model_luong->sum($key)), 2 }}
                @endforeach
                <td></td>
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
                    <td>{{ dinhdangso($ct->luongcoban) }}</td>
                    <td style="text-align: center">{{ dinhdangsothapphan($ct->heso, 2) }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td  style="text-align: center">{{ dinhdangsothapphan($ct->$key, 2) }}</td>
                    @endforeach
                    <td></td>
                    <td>{{ dinhdangsothapphan($ct->st_heso, 2) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @foreach ($a_phucap as $key => $val)
                        <?php $key = 'st_' . $key; ?>
                        <td>{{ dinhdangsothapphan($ct->$key, 2) }}</td>
                    @endforeach
                    <td></td>
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
            <td style="text-align: center">{{ dinhdangsothapphan($model->sum('heso'), 2) }}</td>

            @foreach ($a_phucap as $key => $val)
                <td style="text-align: center">{{ dinhdangsothapphan($model->sum($key), 2) }}</td>
            @endforeach
            <td></td>
            <td>{{ dinhdangso($model->sum('st_heso')) }}</td>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $key = 'st_' . $key; ?>
                <td>{{ dinhdangso($model->sum($key)) }}</td>
            @endforeach
            <td></td>
            <td>{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('ttl')) }}</td>
            <td>{{ dinhdangso($model->sum('ttbh')) }}</td>
            <td>{{ dinhdangso($model->sum('luongtn')) }}</td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}" style="text-align: center">Chuyển nộp 8% BHXH</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 8) / 100) }}</td>
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Chuyển nộp 1,5% BHYT</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 1.5) / 100) }}</td>
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Chuyển nộp 1% BHTN</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 1) / 100) }}</td>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <td>{{ $a_bh[$key] == 0 ? '' : dinhdangso(($model->sum($k) * 1) / 100) }}</td>
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Cộng 10,5% BH</td>
            <td>{{ dinhdangso(($model->sum('st_heso') * 10.5) / 100) }}</td>
            <td></td>
            <td></td>
            <td></td>
            @foreach ($a_phucap as $key => $val)
                <?php $k = 'st_' . $key; ?>
                <td>{{ $a_bh[$key] == 0 ? '' : dinhdangso(($model->sum($k) * 10.5) / 100) }}</td>
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('ttbh')) }}</td>
            <td></td>
        </tr>

        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Chuyển nộp 17,5% BHXH + 3% BHYT + 1% BHTN</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @for ($i = 0; $i < $col; $i++)
                <td></td>
            @endfor

            <td></td>
            <td>{{ dinhdangso($model->sum('stbhxh_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhyt_dv')) }}</td>
            <td>{{ dinhdangso($model->sum('stbhtn_dv')) }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Chuyển nộp 2% KPCD</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @for ($i = 0; $i < $col; $i++)
                <td></td>
            @endfor
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ dinhdangso($model->sum('stkpcd_dv')) }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="font-weight: bold;text-align: right">
            <td colspan="{{ $col + 6 }}">Số chuyển qua thẻ ATM</td>
            <td></td>
            <td></td>
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
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr>
            <td colspan="{{ $col + 6 }}"></td>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Lương theo ngạch, bậc')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'heso')->where('noidung', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng')->first()->tieumuc }}
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
            <th>{{ $model_tm->where('mapc', 'pck')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhxh_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhyt_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stbhtn_dv')->first()->tieumuc }}</th>
            <th>{{ $model_tm->where('mapc', 'stkpcd_dv')->first()->tieumuc }}</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>
    <table id="data_footer1" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr style="float: right">
            <td>Dùng theo dõi và cập nhật vào giấy rút dự toán lương hàng tháng</td>
        </tr>
    </table>

@stop