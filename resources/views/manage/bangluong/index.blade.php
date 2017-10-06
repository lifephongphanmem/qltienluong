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
                    <div class="caption">DANH SÁCH BẢNG LƯƠNG</div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Tháng/Năm</th>
                                <th class="text-center">Nguồn kinh phí</th>
                                @if(session('admin')->level=!'KVXP')
                                    <th class="text-center">Lĩnh vực hoạt động</th>
                                @endif
                                <th class="text-center">Nội dung bảng lương</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->thang.'/'.$value->nam}}</td>
                                        <td>{{$value->tennguonkp}}</td>
                                        @if(session('admin')->level=!'KVXP')
                                            <td>{{$value->linhvuchoatdong}}</td>
                                        @endif
                                        <td>{{$value->noidung}}</td>
                                        <td>
                                            <button type="button" onclick="edit('{{$value->mabl}}')" class="btn btn-info btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Chỉnh sửa</button>
                                            <a href="{{url($furl.'maso='.$value->mabl)}}" class="btn btn-warning btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>
                                            <a href="{{url($furl.'in/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảng lương</a>
                                            <a href="{{url($furl.'in_bh/maso='.$value->mabl)}}" class="btn btn-success btn-xs mbs" TARGET="_blank">
                                                <i class="fa fa-print"></i>&nbsp; In bảo hiểm</a>
                                            <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal thông tin chi tiết -->

    {!! Form::open(['url'=>'/chuc_nang/bang_luong/store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Tạo bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <label class="control-label"> Tháng<span class="require">*</span></label>
                    {!! Form::select(
                    'thang',
                    array(
                    '01' => '01','02' => '02','03' => '03','04' => '04',
                    '05' => '05','06' => '06','07' => '07','08' => '08',
                    '09' => '09','10' => '10','11' => '11','12' => '12',
                    ),null,
                    array('id' => 'thang', 'class' => 'form-control'))
                    !!}

                    <label class="control-label"> Năm<span class="require">*</span></label>
                    {!! Form::select(
                    'nam',
                    array(
                    '2016' => '2016','2017' => '2017','2018' => '2018',
                    ),null,
                    array('id' => 'nam', 'class' => 'form-control'))
                    !!}

                    <label class="control-label"> Nội dung</label>
                    {!! Form::textarea('noidung',null,array('id' => 'noidung', 'class' => 'form-control','rows'=>'3'))!!}

                    <!-- Phân loại đơn vị xa phường ko cần lĩnh vực hoạt động -->
                    @if(session('admin')->level=!'KVXP')
                        <label class="control-label">Lĩnh vực công tác </label>
                        {!!Form::select('linhvuc',$m_linhvuc, null, array('id' => 'linhvuc','class' => 'form-control'))!!}
                    @endif

                    <label class="control-label">Nguồn kinh phí</label>
                    {!!Form::select('manguonkp',$m_nguonkp, null, array('id' => 'manguonkp','class' => 'form-control'))!!}

                    <label class="control-label">Phần trăm hưởng</label>
                    {!!Form::text('phantramhuong', 100, array('id' => 'phantramhuong','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}

                    <input type="hidden" id="id_ct" name="id_ct"/>
                    <input type="hidden" id="mabl" name="mabl"/>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


    <script>
        function add(){
            $('#thang').val('');
            $('#nam').val('');
            $('#noidung').val('');
            $('#phantramhuong').val(100);
            $('#mabl').val('');
            $('#id_ct').val(0);
            $('#chitiet-modal').modal('show');
        }

        function edit(mabl){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_ajax}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    mabl: mabl
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#thang').val(data.thang);
                    $('#nam').val(data.nam);
                    $('#noidung').val(data.noidung);
                    $('#manguonkp').val(data.manguonkp);
                    $('#phantramhuong').val(data.phantramhuong);
                    $('#mabl').val(data.mabl);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#chitiet-modal').modal('show');
        }

        $(function(){
            $('#create_bangluong :submit').click(function(){
                var ok = true, message='';
                var thang=$('#thang').val();
                var nam=$('#nam').val();

                if(thang==null){
                    ok=false;
                    message +='Tháng bảng lương không được bỏ trống. \n';
                }
                if(nam==null){
                    ok=false;
                    message +='Năm bảng lương không được bỏ trống. \n';
                }

                //Kết quả
                if ( ok == false){
                    toastr.error(message,"Lỗi!");
                    $("form").submit(function (e) {
                        e.preventDefault();
                    });
                }
                else{
                    $("form").unbind('submit').submit();
                }
            });
        });
    </script>
    @include('includes.modal.delete')
@stop