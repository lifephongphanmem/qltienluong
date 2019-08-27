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
                        DANH MỤC NGUỒN KINH PHÍ
                    </div>
                    @if(can('dmnguonkp','create'))
                        <div class="actions">
                            <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="addPB()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                        </div>
                    @endif
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Mã nguồn kinh phí</th>
                                <th class="text-center">Tên nguồn kinh phí</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->manguonkp}}</td>
                                        <td>{{$value->tennguonkp}}</td>
                                        <td>{{$value->phanloai}}</td>
                                        <td>{{$value->macdinh == 1 ? 'Nguồn kinh phí mặc định': ''}}</td>
                                        <td>
                                            @if(can('dmnguonkp','edit'))
                                                <button type="button" onclick="editPB('{{$value->manguonkp}}')" class="btn btn-default btn-xs mbs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                            @endif
                                            @if(can('dmnguonkp','delete'))
                                                <button type="button" onclick="cfDel('/danh_muc/nguon_kinh_phi/del/{{$value->id}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
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

    <!--Modal thông tin phòng ban -->
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin nguồn kinh phí</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Mã số nguồn kinh phí</label>
                    {!!Form::text('manguonkp', null, array('id' => 'manguonkp','class' => 'form-control'))!!}

                    <label class="form-control-label">Tên nguồn kinh phí</label>
                    {!!Form::text('tennguonkp', null, array('id' => 'tennguonkp','class' => 'form-control'))!!}

                    <label class="form-control-label">Phân loại<span class="require">*</span></label>
                    {!!Form::select('phanloai', $a_phanloai, null, array('id' => 'phanloai','class' => 'form-control required'))!!}

                    <label class="form-control-label">Ghi chú</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}


                    <label class="control-label col-md-offset-3" style="padding-top: 15px">
                        <input name="macdinh" id="macdinh" type="checkbox" />Nguồn kinh phí mặc định
                    </label>

                    <input type="hidden" id="id" name="id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addPB(){
            //var date=new Date();
            $('#manguonkp').prop('readonly', false);
            $('#manguonkp').val('');
            $('#tennguonkp').val('');
            $('#linhvuchoatdong').val('');
            $('#id').val(0);
            $('#create-modal').modal('show');
        }

        function editPB(manguonkp){
            $('#manguonkp').prop('readonly', true);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{$furl}}' + 'get',
                type: 'GET',
                data: {
                    _token: CSRF_TOKEN,
                    manguonkp: manguonkp
                },
                dataType: 'JSON',
                success: function (data) {
                    //alert(data.macdinh);
                    $('#manguonkp').val(data.manguonkp);
                    $('#phanloai').val(data.phanloai);
                    $('#tennguonkp').val(data.tennguonkp);
                    $('#ghichu').val(data.ghichu);
                    $('#macdinh').prop('checked', data.macdinh == 0? false : true);
                    $.uniform.update();
                },
                error: function(message){
                    toastr.error(message,'Lỗi!');
                }
            });
            $('#id').val(1);
            $('#create-modal').modal('show');
        }

        function cfPB(){
            var valid=true;
            var message='';

            var id = $('#id').val();
            var manguonkp = $('#manguonkp').val();
            var tennguonkp = $('#tennguonkp').val();
            var phanloai = $('#phanloai').val();
            var ghichu=$('#ghichu').val();
            var macdinh= $('#macdinh').is(':checked');

            if(tennguonkp==''){
                valid=false;
                message +='Tên nguồn kinh phí không được bỏ trống \n';
            }

            if(valid){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                if(id==0){//Thêm mới
                    $.ajax({
                        url: '{{$furl}}' + 'add',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            manguonkp: manguonkp,
                            tennguonkp: tennguonkp,
                            phanloai: phanloai,
                            macdinh: macdinh == true ? 1 : 0,
                            ghichu: ghichu
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status == 'success') {
                                location.reload();
                            }
                        },
                        error: function(message){
                            toastr.error(message);
                        }
                    });
                }else{//Cập nhật
                    $.ajax({
                        url: '{{$furl}}' + 'update',
                        type: 'GET',
                        data: {
                            _token: CSRF_TOKEN,
                            manguonkp: manguonkp,
                            tennguonkp: tennguonkp,
                            phanloai: phanloai,
                            macdinh: macdinh == true ? 1 : 0,
                            ghichu: ghichu
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
                $('#create-modal').modal('hide');
            }else{
                toastr.error(message,'Lỗi!.');
            }

            return valid;
        }
    </script>

    @include('includes.modal.delete')
@stop