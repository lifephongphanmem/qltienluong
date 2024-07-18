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
                BÁO CÁO NHÂN VIÊN HỢP ĐỒNG THEO NGHỊ ĐỊNH 111 CỦA ĐƠN VỊ CÓ MẶT ĐẾN THÁNG 07 NĂM {{ $m_dutoan->namns - 1 }}
            </td>
        </tr>
       
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;font-style: italic; font-size: 14px;">
                Khối/Tổ công tác:
                {{ $inputs['mapb'] == 'ALL' ? 'Tất cả các khối/tổ công tác' : $a_phongban[$inputs['mapb']] ?? $inputs['mapb'] }}
            </td>
        </tr>
        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                (Đơn vị: Đồng)
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th style="width: 5%;">S</br>T</br>T</th>
                <th>Họ và tên</th>
                <th style="width: 8%;">Số đối tượng</th>
                <th style="width: 10%;">Mức lương ký hợp đồng</th>
                <th style="width: 10%;">Các khoản đóng góp</th>
                <th style="width: 10%;">Tổng cộng</th>
                <th style="width: 5%;">Số tháng</th>
                <th style="width: 10%" rowspan="3">Quỹ lương năm {{ $m_dutoan->namns }}</th>
            </tr>            
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG SỐ</td>
            <td class="text-right">{{ dinhdangso($model->count()) }}</td>           
            {{-- <td class="text-right">{{ dinhdangso($m_chitiet->sum('canbo_congtac')) }}</td>            --}}
            <td class="text-right">{{ dinhdangsothapphan($model->sum('ttl'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('ttbh_dv'), $lamtron) }}</td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('tongcong'), $lamtron) }}</td>           
            <td class="text-right"></td>
            <td class="text-right">{{ dinhdangsothapphan($model->sum('quyluong'), $lamtron) }}</td>
        </tr>
        <?php $i = 1; ?>
        @foreach ($model_congtac as $congtac)
            <?php $model_luong = $model->where('mact', $congtac->mact); ?>
            @if (count($model_luong) > 0)
                <?php $stt = 1; ?>
                <tr style="font-weight: bold;">
                    <td class="text-center">{{ convert2Roman($i++) }}</td>
                    <td style="text-align: left;">{{ $congtac->tenct }}</td>  
                    <td class="text-right">{{ dinhdangso($model_luong->sum('canbo_congtac')) }}</td>                  
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('ttl'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('ttbh_dv'), $lamtron) }}</td>
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('tongcong'), $lamtron) }}</td>
                    <td class="text-right"></td>                   
                    <td class="text-right">{{ dinhdangsothapphan($model_luong->sum('quyluong'), $lamtron) }}</td>
                </tr>
                @foreach ($model_luong as $ct)
                    <tr>
                        <td class="text-center">{{ $stt++ }}</td>
                        <td style="text-align: left">{{ $ct->tencanbo }}</td>
                        <td style="text-align: left"></td>
                       
                        <td class="text-right">{{ dinhdangsothapphan($ct->ttl, $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->ttbh_dv, $lamtron) }}</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->tongcong, $lamtron) }}</td>                       
                        <td class="text-right">12</td>
                        <td class="text-right">{{ dinhdangsothapphan($ct->quyluong, $lamtron) }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach

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
