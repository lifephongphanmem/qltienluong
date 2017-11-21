<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 24/06/2016
 * Time: 4:00 PM
 */
        ?>
@extends('main')

@section('custom-style')
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{url('assets/global/plugins/select2/select2.css')}}"/>
@stop

@section('custom-script')
    <script type="text/javascript" src="{{url('assets/global/plugins/select2/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>

    <script src="{{url('assets/admin/pages/scripts/table-managed.js')}}"></script>
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
                    <div class="caption">
                        DANH SÁCH DỮ LIỆU TỔNG HỢP LƯƠNG TẠI ĐƠN VỊ
                    </div>
                    <div class="actions">

                    </div>
                </div>
                <div class="portlet-body form-horizontal">

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=1;?>
                        @if(isset($model))
                            @foreach($model as $value)
                                <tr class="{{getTextStatus($value['trangthai'])}}">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-center">{{$value['thang'].'/'.$nam}}</td>
                                    <td>{{$value['noidung']}}</td>
                                    <td class="text-center bold">{{$a_trangthai[$value['trangthai']]}}</td>
                                    <td>
                                        @if ($value['bangluong'] != NULL)
                                            @if ($value['mathdv'] != NULL)
                                                @if($value['trangthai'] !='DAGUI')
                                                    <a href="{{url($furl.'detail/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm">
                                                        <i class="fa fa-list-alt"></i>&nbsp; Số liệu tổng hợp</a>
                                                <!-- chưa phân trường hợp -->
                                                    @if($value['maphanloai'] == 'KVXP')
                                                        <a href="{{url($furl.'detail_diaban/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm">
                                                            <i class="fa fa-list-alt"></i>&nbsp; Số liệu địa bàn</a>
                                                    @endif
                                                    <button type="button" class="btn btn-default btn-sm" onclick="confirmChuyen('{{$value['mathdv']}}')" data-target="#chuyen-modal" data-toggle="modal"><i class="fa fa-share-square-o"></i>&nbsp;
                                                        Gửi dữ liệu</button>
                                                @else
                                                    <a href="{{url($furl.'printf_data/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                        <i class="fa fa-print"></i>&nbsp; Số liệu tổng hợp</a>
                                                    @if($value['maphanloai'] == 'KVXP')
                                                        <a href="{{url($furl.'printf_data_diaban/ma_so='.$value['mathdv'])}}" class="btn btn-default btn-sm" TARGET="_blank">
                                                            <i class="fa fa-print"></i>&nbsp; Số liệu địa bàn</a>
                                                    @endif
                                                @endif
                                            @else
                                                <a href="{{url($furl.'tonghop?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-sm">
                                                    <i class="fa fa-stack-overflow"></i>&nbsp; Tổng hợp dữ liệu</a>
                                            @endif
                                        @else

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Model chuyển-->
    <div class="modal fade" id="chuyen-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url'=>$furl.'senddata','id' => 'frm_chuyen','method'=>'POST'])!!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý chuyển số liệu?</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><b>Số liệu tổng hợp khi gửi đi sẽ không thể chỉnh sửa. Bạn hãy kiểm tra kỹ số liệu trước khi gửi.</b></label>
                    </div>
                <input type="hidden" name="mathdv" id="mathdv">
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn blue">Đồng ý</button>

                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    </div>

    <script>
        function confirmChuyen(mathdv) {
            document.getElementById("mathdv").value = mathdv;
        }

        function edit(madvbc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    madvbc: madvbc
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#tendvbc').val(data.tendvbc);
                    $('#level').val(data.level);
                    $('#ghichu').val(data.ghichu);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#madvbc').val(madvbc);
            $('#phongban-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var message='';
            var madvbc=$('#madvbc').val();
            var tendvbc=$('#tendvbc').val();
            var ghichu=$('#ghichu').val();

            if(tendvbc ==''){
                valid=false;
                message +='Tên khu vực, địa bàn không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(madvbc ==''){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            tendvbc: tendvbc,
                            level: $('#level').val(),
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            alert(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            tendvbc: tendvbc,
                            level: $('#level').val(),
                            ghichu: ghichu,
                            madvbc: madvbc
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message,'Lỗi!!');
                        }
                    });
                }
                $('#phongban-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!!');
            }

            return valid;
        }

        function unit_manage(madvbc){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get_list_unit',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    madvbc: madvbc
                },
                dataType: 'JSON',
                success: function (data) {
                    if(data.status=='success'){
                        $('#donvichuquan').replaceWith(data.message);
                    }else{
                        toastr.error('Khu vực, địa bàn này chưa có đơn vị nào.','Lỗi!');
                        $('#donvichuquan').replaceWith(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#madvbc_cq').val(madvbc);
            $('#chuquan-modal').modal('show');
        }

        function cfdvcq(){
            var madvbc=$('#madvbc_cq').val();
            var madvcq=$('#madvcq_cq').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //Cập nhật
                $.ajax({
                    url: '{{$furl}}' + 'set_management',
                    type: 'GET',
                    data: {
                        _token: CSRF_TOKEN,
                        madvcq: madvcq,
                        madvbc: madvbc
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status == 'success') {
                            location.reload();
                        }
                    },
                    error: function(message){
                        toastr.error(message,'Lỗi!!');
                    }
                });
            $('#chuquan-modal').modal('hide');
        }
    </script>

    @include('includes.modal.delete')
@stop