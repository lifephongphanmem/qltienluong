@extends('main_baocao')

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{$m_donvi['tendv']}}</b>
            </td>
            <td style="text-align: center;">
               
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{$m_donvi->maqhns}}</b>
            </td>
            <td style="text-align: center; font-style: italic">
               
            </td>
        </tr>        

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                TỔNG HỢP CÁN BỘ CHUYÊN TRÁCH, CÔNG CHỨC CẤP XÃ NĂM {{ $m_dutoan->namns - 1 }}
            </td>
        </tr>
        {{-- <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                (Đơn vị: Đồng)
            </td>
        </tr> --}}
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
                <th style="width: 20%" rowspan="3">CHỨC DANH</th>
                <th rowspan="3">HỌ VÀ TÊN</th>                
                <th colspan="{{ 3 + $col }}">HỆ SỐ</th>
                <th rowspan="3" style="width: 5%;">CỘNG HỆ SỐ</th>
                <th style="width: 8%" rowspan="3">THÀNH TIỀN (01 THÁNG)</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 5%;">Hệ số lương</th>
                <th rowspan="2" style="width: 5%;">Các khoản đóng góp</th>
                <th colspan="{{ $col + 1 }}">PHỤ CẤP</th>
            </tr>

            <tr>
                <th style="width: 5%;">Cộng</th>
                @foreach ($a_phucap as $pc)
                    <th style="width: 5%;">{!! $pc !!}</th>
                @endforeach
            </tr>
        </thead>
        
        <?php $i = 1; ?>
        @if ($model != [])
        @foreach ($model_congtac as $congtac)
        <?php $model_luong = $model->where('mact', $congtac->mact); ?>
        @if (count($model_luong) > 0)
            <?php $stt = 1; ?>
            <tr style="font-weight: bold;">
                <td class="text-center">{{ convert2Roman($i++) }}</td>
                <td style="text-align: left;">{{ $congtac->tenct }}</td>
                <td style="text-align: left;"></td>
                
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('heso'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongbh_dv'), $lamtron) }}</td>                    
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongphucap'), $lamtron) }}</td>
                @foreach ($a_phucap as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum($key), $lamtron) }}</td>
                @endforeach
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongcong'), $lamtron) }}</td>
                <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('quyluong'), $lamtron) }}</td>
            </tr>
            @foreach ($model_luong as $ct)
                <tr>
                    <td class="text-center">{{ $stt++ }}</td>
                    <td style="text-align: left">{{ $ct->tencv }}</td>
                    <td style="text-align: left">{{ $ct->tencanbo }}</td>
                    
                    <td class="text-right">{{ dinhdangsothapphan($ct->heso, $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($ct->tongbh_dv, $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($ct->tongphucap, $lamtron) }}</td>
                    @foreach ($a_phucap as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($ct->$key, $lamtron, $lamtron) }}</td>
                    @endforeach
                    <td class="text-right">{{ dinhdangsothapphan($ct->tongcong, $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($ct->quyluong, $lamtron) }}</td>
                </tr>
            @endforeach
        @endif
    @endforeach

    <tr style="font-weight: bold; text-align: center;">
        <td></td>
        <td>TỔNG CỘNG</td>
        <td></td>
        <td class="text-right">{{ dinhdangsothapphan($model->sum('heso'), $lamtron) }}</td>
        <td class="text-right">{{ dinhdangsothapphan($model->sum('tongbh_dv'), $lamtron) }}</td>
        <td class="text-right">{{ dinhdangsothapphan($model->sum('tongphucap'), $lamtron) }}</td>
        @foreach ($a_phucap as $key => $val)
        <td class="text-right">{{ dinhdangsothapphan($model->sum($key), $lamtron) }}</td>
        @endforeach
        <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td>
        <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
    </tr>
        @endif


    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="60%"></td>
            <td style="text-align: center; font-style: italic" width="40%">
                {{ $m_donvi->diadanh . ', ' . Date2Str(null) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;">XÁC NHẬN CỦA PHÒNG NỘI VỤ</td>
            <td style="text-align: center;">{{ $m_donvi['cdlanhdao'] }}</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;"></td>
            <td style="text-align: center;">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td>
                <br><br><br><br><br><br><br>
            </td>
        </tr>

        <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;">{{ $m_donvi['lanhdao'] }}</td>
        </tr>
    </table>
@stop
