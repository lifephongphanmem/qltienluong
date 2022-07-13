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
                <th style="width: 15%;">Mức khoán quỹ<br>phụ cấp</th>
                <th style="width: 15%;">Kinh phí khoán lương</th>
            </tr>
            <tr class="text-center">
                <th>A</th>
                <th>B</th>
                <th>(1)</th>
                <th>(2)</th>
                <th>(3)</th>
            </tr>
        </thead>

        <tr style="font-weight: bold;">
            <td></td>
            <td class="text-center">TỔNG SỐ</td>
            <td class="text-center"></td>
            <th class="text-center"></th>
            <td class="text-right">
                {{ dinhdangso($model->sum('tongsotienthon') + $model->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr style="font-weight: bold;">
            <td>I</td>
            <td>Xã, phường, thị trấn</td>
            <td class="text-center">1</td>
            <th class="text-center"></th>
            <td class="text-right">{{ dinhdangso($model->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Xã loại 1</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL1')->count(), 0) }}</td>
            <td class="text-center">{{ $model->where('phanloaixa', 'XL1')->first()->phanloaixa_heso ?? '16' }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL1')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Xã loại 2</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL2')->count(), 0) }}</td>
            <td class="text-center">
                {{ $model->where('phanloaixa', 'XL2')->first()->phanloaixa_heso ?? dinhdangsothapphan('13,7', 2) }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL2')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Xã loại 3</td>
            <td class="text-center">{{ dinhdangso($model->where('phanloaixa', 'XL3')->count(), 0) }}</td>
            <td class="text-center">
                {{ $model->where('phanloaixa', 'XL3')->first()->phanloaixa_heso ?? dinhdangsothapphan('11,4', 2) }}</td>
            <td class="text-right">
                {{ dinhdangso($model->where('phanloaixa', 'XL3')->sum('sotienphanloaixa'), 0) }}
            </td>
        </tr>

        <tr style="font-weight: bold;">
            <td>II</td>
            <td>Thôn, tổ dân phố</td>
            <td class="text-center">
                {{ dinhdangso($model->sum('tongsothon')) }}
            </td>
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
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã biên giới, hải đảo</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxabiengioi')) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxabiengioi_heso ?? 5, 2) }}</td>
            <td class="text-right">
                {{ dinhdangso($model->sum('sotienxabiengioi'), 5) }}
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Số xã khó khăn theo Quyết định 30/2007/QĐ-TTg</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã khó khăn theo Quyết định 30/2007/QĐ-TTg</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxakhokhan')) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxakhokhan_heso ?? 5, 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxakhokhan'), 5) }}
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Số xã loại I, loại II (không bao gồm số xã thuộc khoản 1, 2 phần II)</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã loại I, loại II</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxaloai1')) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxaloai1_heso ?? 5, 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxaloai1'), 5) }}
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>Số xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã trọng điểm, phức tạp về an ninh trật tự</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxatrongdiem')) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxatrongdiem_heso ?? 0.5, 2) }}</td>
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
        </tr>
        <tr>
            <td>-</td>
            <td>Thôn thuộc xã còn lại</td>
            <td class="text-center">{{ dinhdangso($model->sum('sothonxakhac')) }}</td>
            <td class="text-center">{{ dinhdangsothapphan($model->first()->sothonxakhac_heso ?? 3, 2) }}</td>
            <td class="text-right">
                {{ dinhdangsothapphan($model->sum('sotienxakhac'), 5) }}
            </td>
        </tr>
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
