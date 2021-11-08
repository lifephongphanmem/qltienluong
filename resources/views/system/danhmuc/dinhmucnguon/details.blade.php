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
    @include('includes.script.scripts')
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
                        DANH SÁCH CÁC LOẠI PHỤ CẤP ĐƯỢC HƯỞNG (MÃ NGUỒN: {{$model_nguon->manguonkp}})
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-default btn-xs" onclick="upd_luongcb()" data-toggle="modal" data-target="#luongcb-modal"><i class="fa fa-edit"></i>&nbsp;Cập nhật lương cơ bản</button>
                        <button type="button" class="btn btn-default btn-xs" onclick="addCV()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Mã số</th>
                                <th class="text-center">Phụ cấp</th>
                                <th class="text-center" style="width: 10%">Lương cơ bản</th>
                                <th class="text-center">Cách tính lương<br>(chỉ áp dụng hệ số tính<br>theo số tiền)</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{$value->mapc}}</td>
                                    <td>{{$value->tenpc}}</td>
                                    <td class="text-right">{{dinhdangso($value->luongcoban)}}</td>
                                    <td>{{$value->tinhtheodm == 0 ? "Tính lương theo hồ sơ cán bộ" : "Tính lương theo số tiền của định mức"}}</td>
                                    <td>
                                       <button type="button" data-toggle="modal" data-target="#luongcb-modal" onclick="editCV('{{$value->mapc}}','{{$value->luongcoban}}','{{$value->tinhtheodm}}')" class="btn btn-default btn-xs">
                                           <i class="fa fa-edit"></i>&nbsp; Sửa</button>

                                       <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-offset-5 col-md-5">
                        <a href="{{url('/he_thong/dinh_muc/danh_sach')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chức vụ -->
    {!! Form::open(['url'=>$furl.'store_pc', 'id' => 'create_tttaikhoan', 'class'=>'horizontal-form']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin phụ cấp được hưởng</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Chọn phụ cấp<span class="require">*</span></label>
                    {!!Form::select('mapc',$model_phucap ,null, array('id' => 'mapc','class' => 'form-control select2me','required'=>'required'))!!}

                    <label class="control-label">Mức lương cơ bản</label>
                    {!!Form::text('luongcoban', $model_nguon->luongcoban, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}

                    <label class="form-control-label">Cách tính lương(chỉ áp dụng hệ số tính theo số tiền)</label>
                    {!!Form::select('tinhtheodm',['0'=>"Tính lương theo hồ sơ cán bộ" , '1'=>"Tính lương theo số tiền của định mức"] ,null, array('id' => 'tinhtheodm','class' => 'form-control select2me'))!!}

                </div>
                <input type="hidden" id="maso" name="maso" value="{{$model_nguon->maso}}"/>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


    <!--Modal thông tin chức vụ -->
    {!! Form::open(['url'=>$furl.'update_luongcb', 'id' => 'upd_luongcb', 'class'=>'horizontal-form', 'method'=>'POST']) !!}
    <div id="luongcb-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Cập nhật mức lương cơ bản</h4>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-control-label">Phụ cấp</label>
                                <select class="form-control select2me" name="mapc[]" id="mapc" multiple required="required">
                                    @foreach($model_phucap_dm as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Mức lương cơ bản</label>
                                {!!Form::text('luongcoban', session('admin')->luongcoban, array('id' => 'luongcoban','class' => 'form-control', 'data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Cách tính lương(chỉ áp dụng hệ số tính theo số tiền)</label>
                                {!!Form::select('tinhtheodm',['0'=>"Tính lương theo hồ sơ cán bộ" , '1'=>"Tính lương theo số tiền của định mức"] ,null, array('id' => 'tinhtheodm','class' => 'form-control select2me'))!!}
                            </div>
                        </div>
                        <input type="hidden" id="maso" name="maso" value="{{$model_nguon->maso}}"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" >Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <script>
        function upd_luongcb(){
            var selectedItems = [];
            var mapc = $('#upd_luongcb').find("[id='mapc']");
            mapc.find('option').each(function(){
                selectedItems.push($(this).val());
            });
            mapc.select2("val",selectedItems);
        }

        function editCV(mapc,luongcoban,tinhtheodm){
            var form = $('#upd_luongcb');
            var a_pc = mapc.split(',');
            form.find("[id='mapc']").select2("val",a_pc);
            form.find("[id='tinhtheodm']").val(tinhtheodm).trigger('change');
            form.find("[id='luongcoban']").val(luongcoban);
        }

        function addCV(){
            $('#chitiet-modal').modal('show');
        }
    </script>

    @include('includes.modal.delete')

@stop