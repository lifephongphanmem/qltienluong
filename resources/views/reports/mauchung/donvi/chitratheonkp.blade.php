@extends('main_baocao')

@section('content')

    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:0 auto 25px; text-align: center;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị chủ quản: {{ $m_dv->tenct }}</b>
            </td>
            <td style="text-align: center;">
                <b></b>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_dv->tendv }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>
    </table>
    <p id="data_body" style="text-align: center; font-weight: bold; font-size: 20px;">TỔNG HỢP CHI TRẢ LƯƠNG TẠI ĐƠN VỊ THEO
        NGUỒN KINH PHÍ</p>
    <p id="data_body1" style="text-align: center; font-style: italic">Từ {{ $thongtin['tu'] }} đến {{ $thongtin['den'] }}
    </p>

    <p id="data_body2" style="text-align: right; font-style: italic">Đơn vị tính: đồng</p>
    <table id="data_body3" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr style="">
                <th style="width: 2%;" rowspan="2">STT</th>
                <th rowspan="2">Nguồn kinh phí</th>
                <th colspan="{{ $col + 1 }}">Lương và các khoản phụ cấp</th>

                <th colspan="5">Các khoản phải đóng góp</th>
                <th style="width: 6%;" rowspan="2">Tổng cộng</th>
            </tr>

            <tr>
                @foreach ($a_phucap as $key => $val)
                    <th style="width: 4%;">{!! $val !!}</th>
                @endforeach
                <th style="width: 5%;">Tổng tiền lương</th>
                <th style="width: 4%;">BHXH</th>
                <th style="width: 4%;">BHYT</th>
                <th style="width: 4%;">KPCĐ</th>
                <th style="width: 4%;">BHTN</th>
                <th style="width: 4%;">Cộng</th>
            </tr>

            <tr>
                @for ($i = 1; $i < 10 + $col; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <?php $stt = 1; ?>
        @foreach ($model as $tonghop)
            <?php $chitiet = $model_chitiet->where('mathdv', $tonghop->mathdv); ?>
            <tr style="font-weight: bold;">
                <td>{{ convert2Roman($stt++) }}</td>
                <td colspan="{{ 10 + $col }}">Tháng {{ $tonghop->thang }}</td>

            </tr>
            <?php $i = 1; ?>
            @foreach ($chitiet as $ct)
                <tr class="money">
                    <td style="text-align: center">{{ $i++ }}</td>
                    <td style="text-align: left">{{ $ct->tennguonkp }}</td>
                    @foreach ($a_phucap as $key => $val)
                        <td class="text-right">{{ dinhdangsothapphan($ct->$key, 5) }}</td>
                    @endforeach

                    <td class="text-right">{{ dinhdangso($ct->ttl) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->stbhxh_dv) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->stbhyt_dv) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->stkpcd_dv) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->stbhtn_dv) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->ttbh_dv) }}</td>
                    <td class="text-right">{{ dinhdangso($ct->tongcong) }}</td>
                </tr>
            @endforeach
            <!--Cộng theo nhóm-->
            <tr style="font-weight: bold; text-align: center" class="money">
                <td colspan="2">Cộng</td>
                @foreach ($a_phucap as $key => $val)
                    <td class="text-right">{{ dinhdangsothapphan($chitiet->sum($key), 5) }}</td>
                @endforeach

                <td class="text-right">{{ dinhdangso($chitiet->sum('ttl')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('stbhxh_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('stbhyt_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('stkpcd_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('stbhtn_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('ttbh_dv')) }}</td>
                <td class="text-right">{{ dinhdangso($chitiet->sum('tongcong')) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold; text-align: center" class="money">
            <td colspan="2">Tổng cộng</td>
            @foreach ($a_phucap as $key => $val)
                <td class="text-right">{{ dinhdangsothapphan($model_chitiet->sum($key), 5) }}</td>
            @endforeach
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('ttl')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('stbhxh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('stbhyt_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('stkpcd_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('stbhtn_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('ttbh_dv')) }}</td>
            <td class="text-right">{{ dinhdangso($model_chitiet->sum('tongcong')) }}</td>
        </tr>
    </table>

    <table id="data_footer" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="margin:20px auto; text-align: center;">
        <tr>
            <td style="text-align: left;" width="50%"></td>
            <td style="text-align: center; font-style: italic" width="50%">........,Ngày......tháng.......năm..........
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center;" width="50%">Người lập bảng</td>
            <td style="text-align: center;" width="50%">Thủ trưởng đơn vị</td>
        </tr>
        <tr style="font-style: italic">
            <td style="text-align: center;" width="50%">(Ghi rõ họ tên)</td>
            <td style="text-align: center;" width="50%">(Ký tên, đóng dấu)</td>
        </tr>
        <tr>
            <td><br><br><br><br><br></td>
        </tr>

        <tr>
            <td style="text-align: center;" width="50%">{{ '' }}</td>
            <td style="text-align: center;" width="50%">{{ $m_dv->lanhdao }}</td>
        </tr>
    </table>
@stop
