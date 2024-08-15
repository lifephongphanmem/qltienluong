@extends('main_baocao')

@section('style_css')
    <style type="text/css">
        table tr td:first-child {
            text-align: center;
        }
    </style>
@stop

@section('content')
    <table id="data_header" class="header" width="96%" border="0" cellspacing="0" cellpadding="8"
        style="text-align: center; font-size: 12px;">
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Đơn vị: {{ $m_donvi['tendv'] }}</b>
            </td>
            <td style="text-align: center;">

            </td>
        </tr>
        <tr>
            <td style="text-align: left;width: 60%">
                <b>Mã đơn vị SDNS: {{ $m_donvi->maqhns }}</b>
            </td>
            <td style="text-align: center; font-style: italic">

            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 20px; text-transform: uppercase">
                TỔNG HỢP KINH PHÍ ĐỂ THỰC HIỆN CHẾ ĐỘ PHỤ CẤP ĐỐI VỚI CÁN BỘ KHÔNG CHUYÊN TRÁCH CẤP THÔN, XÃ VÀ TỔ DÂN PHỐ NĂM
                {{ $inputs['namns'] }}
            </td>
        </tr>

        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                Đơn vị: {{getDonViTinh()[$inputs['donvitinh']]}}
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;font-size: {{$inputs['cochu'] ?? 12}}px;">
        <thead>
            <tr class="text-center">
                <th style="width: 5%;">STT</th>
                <th>Chỉ tiêu</th>
                <th style="width: 15%;">Tổng đơn vị hành<br>chính cấp xã, thôn</th>
                <th style="width: 15%;">Số cán bộ tăng thêm</th>
                <th style="width: 15%;">Mức khoán quỹ<br>phụ cấp</th>
                <th style="width: 15%;">Kinh phí khoán lương</th>
            </tr>
            <tr class="text-center">
                <th>A</th>
                <th>B</th>
                <th>(1)</th>
                <th>(2)</th>
                <th>(3)</th>
                <th>(4)</th>
            </tr>
        </thead>

        <tr style="font-weight: bold;">
            <td></td>
            <td class="text-center">TỔNG SỐ</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-right">
                {{ dinhdangso($model->sum('tongsotienthon') + $model->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr style="font-weight: bold;">
            <td>I</td>
            <td>Xã, phường, thị trấn</td>
            <td class="text-center">{{ dinhdangso($model->count(), 0) }}</td>
            <td></td>
            <th class="text-center">{{ dinhdangso($model->sum('phanloaixa_heso'), 0) }}</th>
            <td class="text-right">{{ dinhdangso($model->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Xã loại 1</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL1')->count(), 0) }}</td>
            <td></td>
            {{-- <td class="text-center">{{ $model->where('phanloaixa', 'XL1')->first()->phanloaixa_heso ?? '16' }}</td> --}}
            <td class="text-center">{{ $model->where('phanloaixa', 'XL1')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL1')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL1')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL1')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Xã loại 2</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL2')->count(), 0) }}</td>
            <td></td>
            <td class="text-center">
                {{-- {{ $model->where('phanloaixa', 'XL2')->first()->phanloaixa_heso ?? dinhdangsothapphan('13,7', 2) }}</td> --}}
                {{ $model->where('phanloaixa', 'XL2')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL2')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL2')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL2')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Xã loại 3</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL3')->count(), 0) }}</td>
            <td></td>
            {{-- <td class="text-center">
                {{ $model->where('phanloaixa', 'XL3')->first()->phanloaixa_heso ?? dinhdangsothapphan('11,4', 2) }}</td> --}}
                <td class="text-center">
                    {{ $model->where('phanloaixa', 'XL3')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL3')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL3')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL3')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>Phường loại 1</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL1')->count(), 0) }}</td>
            <td></td>
            {{-- <td class="text-center">
                {{ $model->where('phanloaixa', 'XL3')->first()->phanloaixa_heso ?? dinhdangsothapphan('11,4', 2) }}</td> --}}
                <td class="text-center">
                    {{ $model->where('phanloaixa', 'PL1')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL1')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL1')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL1')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>Phường loại 2</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL2')->count(), 0) }}</td>
            <td></td>
                <td class="text-center">
                    {{ $model->where('phanloaixa', 'PL2')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL2')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL2')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL2')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr>
            <td>6</td>
            <td>Phường loại 3</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL3')->count(), 0) }}</td>
            <td></td>
            {{-- <td class="text-center">
                {{ $model->where('phanloaixa', 'XL3')->first()->phanloaixa_heso ?? dinhdangsothapphan('11,4', 2) }}</td> --}}
                <td class="text-center">
                    {{ $model->where('phanloaixa', 'PL3')->first()->phanloaixa_heso ?? '' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL3')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số cán bộ tăng thêm</td>
            <td></td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'PL3')->sum('socanbotangthem'), 0) }}</td>
            <td class="text-center">1,5</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'PL3')->sum('sotiencbtangthem'), 0) }}
            </td>
        </tr>
        <tr style="font-weight: bold;">
            <td>II</td>
            <td>Thôn, tổ dân phố</td>
            <td class="text-center">
                {{ dinhdangso($model->sum('tongsothon')) }}
            </td>
            <th class="text-center"></th>
            <th class="text-center"></th>
            <td class="text-right">
                {{ dinhdangso($model->sum('tongsotienthon'), 0) }}
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Số xã biên giới, hải đảo</td>
            <td class="text-center"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã biên giới, hải đảo</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxabiengioi')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxabiengioi_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxabiengioi'), 5) }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Số xã có thôn, tổ dân phố có 350 hộ gia đình trở lên, xã trọng điểm, phức tạp về an ninh trật tự theo Quyết
                định của cơ quan thẩm quyền</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số thôn có 350 hộ gia đình trở lên, thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự theo Quyết định
                của cơ quan có thẩm quyền</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxa350ho')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxa350ho_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sothonxa350ho'), 5) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Số tổ dân phố có từ 500 hộ gia đình trở lên</td>
            <td class="text-center">{{ dinhdangso($model->sum('sotodanpho500ho'))}}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sotodanpho500ho_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotodanpho500ho'), 5) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Tổ dân phố thuộc xã trọng điểm về an ninh trật tự theo Quyết định của cơ quan có thẩm quyền</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxatrongdiem')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxatrongdiem_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sothonxatrongdiem'), 5) }}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Tổ dân phố chuyển từ thôn có 350 hộ gia đình trở lên do thành lập đơn vị hành chính đô thị cấp xã</td>
            <td class="text-center">{{ dinhdangso($model->sum('sochuyentuthon350hgd')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sochuyentuthon350hgd_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sochuyentuthon350hgd'), 5) }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Số xã, phường, thị trấn còn lại</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn còn lại</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxakhac')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxakhac_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sothonxakhac'), 5)}}
            </td>
        </tr>
        <tr>
            <td>-</td>
            <td>Tổ dân phố</td>
            <td class="text-center">{{ dinhdangso($model->sum('sotodanphokhac')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sotodanphokhac_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotodanphokhac'), 5) }}
            </td>
        </tr>
        {{-- <tr>
            <td>3</td>
            <td>Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã loại I, loại II</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxaloai1')) }}</td>
            <td></td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxaloai1_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxaloai1'), 5) }}
            </td>
        </tr> --}}
        {{-- <tr>
            <td>4</td>
            <td>Số xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxatrongdiem')) }}</td>
            <td></td>

            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxatrongdiem_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxatrongdiem'), 5) }}
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>Số xã còn lại</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã còn lại</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxakhac')) }}</td>
            <td></td>

            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxakhac_heso ?? '', 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxakhac'), 5) }}
            </td>
        </tr> --}}
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
            <td style="text-align: center;"></td>
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
