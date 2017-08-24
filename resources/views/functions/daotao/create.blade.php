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
    <h3 class="page-title">
        Thông tin danh sách đào tạo, bồi dưỡng cán bộ
    </h3>

    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet-body form">
                {!! Form::open(['url'=>$furl.'store', 'id' => 'create_form', 'class'=>'horizontal-form']) !!}
                    <input type="hidden" name="madv" id="madv" value="{{$madv}}">
                    <input type="hidden" name="mads" id="mads" value="{{$mads}}">
                <div class="form-body">
                    <h4 class="form-section" style="color: #0000ff">Thông tin quyết định</h4>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Số quyết định<span class="require">*</span></label>
                                <input type="text" name="soqd" id="soqd" class="form-control required" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Ngày quyết định<span class="require">*</span></label>
                                <input type="date" name="ngayqd" id="ngayqd" class="form-control" required="required"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cơ quan quyết định<span class="require">*</span></label>
                                <input type="text" name="coquanqd" id="coquanqd" class="form-control required" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Người ký quyết định</label>
                                <input type="text" name="nguoiky" id="nguoiky" class="form-control " />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Thời gian từ ngày</label>
                                <input type="date" name="ngaytu" id="ngaytu" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Thời gian đến ngày</label>
                                <input type="date" name="ngayden" id="ngayden" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nguồn kinh phí</label>
                                <input type="text" name="nguonkinhphi" id="nguonkinhphi" class="form-control " />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Trang thái</label>
                                <input type="text" name="trangthai" id="trangthai" class="form-control " />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label class="control-label">Nội dung</label>
                                <div>
                                    <textarea id="noidung" class="form-control" name="noidung"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label class="control-label">Ghi chú</label>
                                <div>
                                    <textarea id="ghichu" class="form-control" name="ghichu"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--/row-->
                    <h4 class="form-section" style="color: #0000ff">Thông tin chi tiết cán bộ</h4>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" data-target="#modal-create" data-toggle="modal" class="btn btn-default" onclick="get_canbo()"><i class="fa fa-plus"></i>&nbsp;Thêm cán bộ</button>
                                &nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="row" id="thongtinchitiet">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" id="sample_3">
                                <thead>
                                <tr>
                                    <th style="text-align: center">STT</th>
                                    <th style="text-align: center">Họ và tên</th>
                                    <th style="text-align: center">Ngày sinh</th>
                                    <th style="text-align: center">Giới tính</th>
                                    <th style="text-align: center">Phòng ban</th>
                                    <th style="text-align: center">Chức vụ</th>
                                    <th style="text-align: center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <div class="row" style="text-align: center">
                        <a href="{{url('/chuc_nang/dao_tao/danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                        <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i>&nbsp;Nhập lại</button>
                        <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
                    </div>
                </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
            <!-- END EXAMPLE TABLE PORTLET-->
    </div>

    <!-- BEGIN DASHBOARD STATS -->

    <!-- END DASHBOARD STATS -->
    <div class="clearfix">
    </div>

    <!--Validate Form-->
    <script type="text/javascript">
        function validateForm(){

            var validator = $("#create_form").validate({
                rules: {
                    ten :"required"
                },
                messages: {
                    ten :"Chưa nhập dữ liệu"
                }
            });
        }
    </script>

    <div class="modal fade bs-modal-lg" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Thông tin cán bộ</h4>
                </div>
                <div class="modal-body" id="thongtincanbo">

                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                    <button type="button" class="btn btn-primary" onclick="add_canbo()">Thêm mới</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!--Modal Wide Width-->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Đồng ý xóa cán bộ ra khỏi danh sách?</h4>
                </div>
                <input type="hidden" id="iddelete" name="iddelete">
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Thoát</button>
                    <button type="button" class="btn btn-primary" onclick="deleteRow()">Đồng ý</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    @include('includes.script.create-header-scripts')
    <script>
        function get_canbo(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/chuc_nang/dao_tao/get_canbo_temp',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mads:  $('#mads').val(),
                    madv: $('#madv').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#thongtincanbo').replaceWith(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
        }
        function add_canbo(){
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}'+'add_canbo_temp',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    macanbo: $('#macb_chon').val(),
                    mads: $('#mads').val(),
                    madv: $('#madv').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#thongtinchitiet').replaceWith(data.message);
                    }
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#modal-create').modal('hide');
        }

        function getid(id){
            document.getElementById("iddelete").value=id;
        }

        function deleteRow() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/kkgdvlt/delete',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: $('input[name="iddelete"]').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    //if(data.status == 'success') {
                    toastr.success("Bạn đã xóa thông tin phòng nghỉ thành công!", "Thành công!");
                    $('#dsts').replaceWith(data.message);
                    jQuery(document).ready(function() {
                        TableManaged.init();
                    });

                    $('#modal-delete').modal("hide");

                    //}
                }
            })

        }
    </script>
@stop