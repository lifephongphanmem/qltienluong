@extends('main_baocao')

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
                BẢNG TỔNG HỢP BIÊN CHẾ, HỆ SỐ TIỀN LƯƠNG VÀ PHỤ CẤP CÓ MẶT NĂM ...
            </td>
        </tr>

        <tr>
            <td style="text-align: right" colspan="2" style="font-weight:bold; font-size: 12px;">
                Đơn vị tính:
            </td>
        </tr>
    </table>
    <table id="data_body" cellspacing="0" cellpadding="0" border="1"
        style="margin: 20px auto; border-collapse: collapse;">
        <thead>
            <tr class="text-center">
                <th style="width: 2%;" rowspan="3">S</br>T</br>T</th>
                <th rowspan="3">NÔI DUNG</th>
                <th colspan="3">SỐ ĐỐI TƯỢNG</th>
                <th colspan="{{ 4 + 5 }}">HỆ SỐ TIỀN LƯƠNG, PHỤ CẤP, TRỢ CẤP</th>
                <th rowspan="3" style="width: 4%;">HỆ SỐ</th>
                <th style="width: 8%" rowspan="3">QUỸ LƯƠNG CẤP NĂM ...</th>
            </tr>

            <tr style="padding-left: 2px;padding-right: 2px">
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>GIAO</th>
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>CÓ<br>MẶT</th>
                <th rowspan="2" style="width: 3%;">BIÊN<br>CHẾ<br>ĐƯỢC<br>DUYỆT<br>NHƯNG<br>CHƯA<br>TUYỂN</th>

                <th rowspan="2" style="width: 4%;">TỔNG CỘNG</th>
                <th rowspan="2" style="width: 4%;">HỆ SỐ LƯƠNG</th>
                <th rowspan="2" style="width: 4%;">TỔNG HỆ<br>SỐ CÁC<br>KHOẢN PHỤ<br>CẤP TRỢ<br>CẤP</th>
                <th colspan="4">Trong đó</th>
                <th rowspan="2" style="width: 4%;">BH THẤT<br>NGHIỆP</th>
                <th rowspan="2" style="width: 4%;">CÁC KHOẢN<br>ĐÓNG GÓP<br>BHXH, BHYT,<br>KPCĐ</th>
            </tr>

            <tr>
                <th class="text-uppercase" style="width: 4%;">PHỤ CẤP KHU VỰC</th>
                <th class="text-uppercase" style="width: 4%;">PHỤ CẤP CHỨC VỤ</th>
                <th class="text-uppercase" style="width: 4%;">PHỤ CẤP THÂM NIÊN VƯỢT KHUNG</th>
                <th class="text-uppercase" style="width: 4%;">...</th>
            </tr>
        </thead>

        <tr style="font-weight: bold; text-align: center;">
            <td></td>
            <td>TỔNG CỘNG</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>

            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right"></td>
        </tr>
        <?php $i = 1; ?>
        {{-- vòng 1 --}}
        @foreach ($m_phanloai->where('capdo_nhom', '1')->sortby('sapxep') as $phanloai1)
            <?php
            
            $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai1->maphanloai_nhom);
            ?>
            <tr class="font-weight-bold">
                <td>{{ $phanloai1->sapxep }}</td>
                <td>{{ $phanloai1->tenphanloai_nhom }}</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
            </tr>
            @if (count($model_donvi) > 0)
                    {{-- in chi tiết từng đơn vị --}}
                    @if ($phanloai2->chitiet == '1')
                        @foreach ($model_donvi as $donvi)
                            <tr>
                                <td></td>
                                <td>{{ $donvi->tendv }}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                        @endforeach
                    @else
                        {{-- gộp các đơn vị thành 01 nhóm --}}
                        <tr>
                            <td></td>
                            <td>Biên chế</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Biên chế chưa tuyển</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                        </tr>
                    @endif
                @endif
            {{-- vòng 2 --}}
            @foreach ($m_phanloai->where('maphanloai_goc', $phanloai1->maphanloai_nhom)->sortby('sapxep') as $phanloai2)
                <?php
                $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai2->maphanloai_nhom);
                ?>
                <tr class="font-weight-bold">
                    <td>{{ $phanloai1->sapxep }}.{{ $phanloai2->sapxep }}</td>
                    <td>{{ $phanloai2->tenphanloai_nhom }}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                </tr>
                @if (count($model_donvi) > 0)
                    {{-- in chi tiết từng đơn vị --}}
                    @if ($phanloai2->chitiet == '1')
                        @foreach ($model_donvi as $donvi)
                            <tr>
                                <td></td>
                                <td>{{ $donvi->tendv }}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                        @endforeach
                    @else
                        {{-- gộp các đơn vị thành 01 nhóm --}}
                        <tr>
                            <td></td>
                            <td>Biên chế</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Biên chế chưa tuyển</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                        </tr>
                    @endif
                @endif

                {{-- vòng 3 --}}
                @foreach ($m_phanloai->where('maphanloai_goc', $phanloai2->maphanloai_nhom)->sortby('sapxep') as $phanloai3)
                    <?php
                    $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai3->maphanloai_nhom);
                    ?>
                    <tr class="font-weight-bold">
                        <td>{{ $phanloai1->sapxep }}.{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}</td>
                        <td>{{ $phanloai3->tenphanloai_nhom }}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                    @if (count($model_donvi) > 0)
                        {{-- in chi tiết từng đơn vị --}}
                        @if ($phanloai3->chitiet == '1')
                            @foreach ($model_donvi as $donvi)
                                <tr>
                                    <td></td>
                                    <td>{{ $donvi->tendv }}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach
                        @else
                            {{-- gộp các đơn vị thành 01 nhóm --}}
                            <tr>
                                <td></td>
                                <td>Biên chế</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Biên chế chưa tuyển</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                        @endif
                    @endif
                    {{-- vòng 4 --}}
                    @foreach ($m_phanloai->where('maphanloai_goc', $phanloai3->maphanloai_nhom)->sortby('sapxep') as $phanloai4)
                        <?php
                        $model_donvi = $m_donvi_baocao->where('maphanloai', $phanloai4->maphanloai_nhom);
                        ?>
                        <tr class="font-weight-bold">
                            <td>{{ $phanloai1->sapxep }}.{{ $phanloai2->sapxep }}.{{ $phanloai3->sapxep }}.{{ $phanloai4->sapxep }}</td>
                            <td>{{ $phanloai4->tenphanloai_nhom }}</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                        </tr>
                        @if (count($model_donvi) > 0)
                            {{-- in chi tiết từng đơn vị --}}
                            @if ($phanloai4->chitiet == '1')
                                @foreach ($model_donvi as $donvi)
                                    <tr>
                                        <td></td>
                                        <td>{{ $donvi->tendv }}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                        <td class="text-right"></td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- gộp các đơn vị thành 01 nhóm --}}
                                <tr>
                                    <td></td>
                                    <td>Biên chế</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Biên chế chưa tuyển</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            @endforeach
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
