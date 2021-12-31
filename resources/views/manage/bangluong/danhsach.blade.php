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
        $(function(){
            $('#nambc').change(function() {
                var nambc = $('#nambc').val();
                var url = '{{$furl}}'+'index?nam='+nambc;
                window.location.href = url;
            });
        })
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        DANH SÁCH CHI TRẢ LƯƠNG CỦA ĐƠN VỊ
                    </div>
                    <div class="actions"></div>

                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-1">
                            <div class="form-group">
                                <label class="control-label">Năm dữ liệu </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!!Form::select('nam',getNam(), $nam, array('id' => 'nam','class' => 'form-control select2me'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
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
                                    <td class="text-center bold">{{$a_trangthai[$value['trangthai']]}}</td>
                                    <td>
                                        <a href="{{url($furl.'chi_tra?thang='.$value['thang'].'&nam='.$nam)}}" class="btn btn-default btn-sm">
                                            <i class="fa fa-list-alt"></i>&nbsp; Chi tiết chi trả</a>

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

    <!--Model Trả lại -->
    <div class="modal fade" id="tralai-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin lý do trả lại dữ liệu</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!!Form::textarea('lydo', null, array('id' => 'lydo','class' => 'form-control','rows'=>'3'))!!}
                    </div>

                    <div class="modal-footer">
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

        function getLyDo(mathdv){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'getlydo',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mathdv: mathdv
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#lydo').val(data.lydo);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            //$('#madvbc').val(madvbc);
            //$('#phongban-modal').modal('show');
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

    <script>
        function cfDel(url){
            $('#frmDelete').attr('action', url);
        }

        function subDel(){
            $('#frmDelete').submit();
        }
    </script>

    <div id="delete-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <form id="frmDelete" method="GET" action="#" accept-charset="UTF-8">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" onclick="subDel()" data-dismiss="modal" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop