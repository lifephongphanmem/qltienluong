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
                    <div class="caption"> DANH SÁCH NÂNG LƯƠNG ĐỊNH KỲ - {{$tendv}}</div>
                    <div class="actions">
                        <button type="button" id="_btnadd" class="btn btn-success btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Tạo danh sách</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Ngày xét duyệt</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($model))
                            <?php $i=1;?>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-center">{{getDayVn($value->ngayxet)}}</td>
                                    <td>{{$value->kemtheo}}</td>
                                    <td>{{$value->trangthai}}</td>
                                    <td style="width: 25%">
                                        @if($value->trangthai != 'Đã nâng lương')
                                            <button type="button" onclick="edit({{$value->id}})" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                        @endif
                                            <a href="{{url($furl.'maso='.$value->manl)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-th-list"></i>&nbsp; Chi tiết</a>

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
    {!! Form::open(['url'=>'/chuc_nang/nang_luong/store','method'=>'post', 'id' => 'create_ngachbac','enctype'=>'multipart/form-data']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Tạo danh sách nâng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <label class="control-label"> Ngày xét duyệt (ngày hưởng lương)<span class="require">*</span></label>
                    {!! Form::date('ngayxet', date('Y-m-d'), array('id' => 'ngayxet', 'class' => 'form-control'))!!}

                    <label class="control-label"> Nội dung kèm theo</label>
                    {!! Form::textarea('kemtheo',null,array('id' => 'kemtheo', 'class' => 'form-control','rows'=>'3'))!!}
                    <input type="hidden" id="manl" name="manl"/>
                    <input type="hidden" id="id_ct" name="id_ct"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
    {!!Form::close()!!}
    <script>
        function add(){
            $('#kemtheo').val('');
            $('#manl').val('');
            $('#id_ct').val(0);
            $('#chitiet-modal').modal('show');
        }

        function edit(id){
            //var tr = $(e).closest('tr');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl_ajax}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#manl').val(data.manl);
                    $('#ngayxet').val(data.ngayxet);
                    $('#kemtheo').val(data.kemtheo);
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });

            $('#id_ct').val(id);
            $('#chitiet-modal').modal('show');
        }

        $(function(){
            $('#create_ngachbac :submit').click(function(){
                var ok = true;
                //var ngayxet=$('#ngayxet').val();

                if($('#ngayxet').val()==''){
                    ok = false;
                }

                //Kết quả
                if ( ok == false){
                    alert(' Ngày xét duyệt không được bỏ trống \n');
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