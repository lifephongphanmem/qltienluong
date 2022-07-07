<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 04/07/2016
 * Time: 3:50 CH
 */
?>
@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css"
        href="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/global/plugins/select2/select2.css') }}" />
@stop

@section('custom-script')
    <script type="text/javascript" src="{{ url('assets/global/plugins/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script src="{{ url('assets/admin/pages/scripts/table-managed.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            TableManaged.init();
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption text-uppercase">
                        DANH SÁCH CÁN BỘ - {{ $tendv }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4">
                        <label class="control-label " style="text-align: right">Phân loại đơn vị:</label>
                    </div>
                    <div class="col-md-8">
                        {!! Form::select('sunghiep', $a_sunghiep, $sunghiep, ['id' => 'sunghiep', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-3">
                        <label class="control-label " style="text-align: right">Đơn vị:</label>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control select2me" id="madv" name="madv">
                            <option value="ALL">--Chọn tất cả--</option>
                            @foreach ($m_donvi as $donvi)
                                <option value="{{ $donvi->madv }} " @if ($madv == $donvi->madv) selected @endif>
                                    {{ $donvi->tendv }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="portlet-body">

                    <table id="sample_3" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th width="96">Ảnh</th>
                                <th class="text-center">Họ tên</th>
                                <th class="text-center">Ngày sinh</th>
                                <th class="text-center">Giới tính</th>
                                <th class="text-center">Phân loại</br>công tác</th>
                                <th class="text-center">Tên đơn vị</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($model as $hs)
                                <tr>
                                    <td class="text-center">{{ $hs->stt }}</td>
                                    <td name="anh">
                                        <a href="{{ url($url . 'maso=' . $hs->id . '') }}">
                                            @if ($hs->anh != '')
                                                <img src="{{ url($hs->anh) }}" width="96">
                                            @else
                                                <img src="{{ url('images/avatar/no-image.png') }}" width="96">
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url($url . 'maso=' . $hs->id . '') }}"><b>{{ $hs->tencanbo }}</b></a>
                                        <p style="margin-top: 5px">Chức vụ: {{ $hs->tencvcq }}</p>
                                    </td>
                                    <td class="text-center">{{ getDayVn($hs->ngaysinh) }}</td>
                                    <td class="text-center">{{ $hs->gioitinh }}</td>
                                    <td class="text-center">{{ $hs->tenct }}</td>
                                    <td class="text-center">{{ $hs->tendv }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script>
    function getLink() {
        var sunghiep = $('#sunghiep').val();
        var madv = $('#madv').val();
        return '/nghiep_vu/ho_so/danh_sach_th?sunghiep=' + sunghiep + '&madv=' + madv;
    }

    $(function() {
        $('#sunghiep').change(function() {
            window.location.href = getLink();
        });
    })
    $(function() {
        $('#madv').change(function() {
            window.location.href = getLink();
        });
    })
</script>@stop
