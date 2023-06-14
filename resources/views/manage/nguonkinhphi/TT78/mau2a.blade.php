<div id="tab2a" class="tab-pane active">
    <div class="form-body">
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <table id="sample_4" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Nhóm công tác</th>
                            <th class="text-center">Phân loại công tác</th>
                            <th class="text-center" style="width: 5%">Số lượng cán bộ</th>
                            <th class="text-center" style="width: 10%">Tổng hệ số lương</br>(Hệ số)</th>
                            <th class="text-center" style="width: 10%">Tổng các khoản phụ cấp</br>(Hệ số)</th>
                            <th class="text-center" style="width: 10%">Các khoản đóng góp</br>(Hệ số)</th>
                            <th class="text-center" style="width: 10%">Tổng cộng</th>

                            {{-- <th class="text-center">Thao tác</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($model_2a as $value)
                            <tr class="{{ getTextStatus($value->trangthai) }}">
                                <td class="text-center">{{ $i++ }}</td>
                                <td>{{ $a_nhucau[$value->nhomnhucau] ?? $value->nhomnhucau }}</td>
                                <td>{{ $a_ct[$value->mact] ?? $value->mact }}</td>
                                <td class="text-center">{{ dinhdangso($value->canbo_congtac) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($value->heso, 5) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($value->tonghs - $value->heso, 5) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($value->tongbh_dv, 5) }}</td>
                                <td class="text-right">{{ dinhdangsothapphan($value->tongbh_dv + $value->tonghs, 5) }}
                                </td>

                                {{-- <td> --}}
                                {{-- <button type="button" onclick="indutoan('{{$value->mact}}','{{$value->masodv}}')" class="btn btn-default btn-xs mbs" data-target="#indt-modal" data-toggle="modal"> --}}
                                {{-- <i class="fa fa-edit"></i>&nbsp; Sửa</button> --}}
                                {{-- </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
