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
    @include('includes.script.scripts')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <b>DANH SÁCH CÁN BỘ ĐƯỢC HƯỞNG TRUY LĨNH LƯƠNG</b>
                    </div>
                    <div class="actions">
                        <button type="button" id="_btnaddPB" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-offset-2 col-md-1" style="text-align: right">Tháng </label>
                            <div class="col-md-2">
                                {!! Form::select('thangct',getThang(),$inputs['thang'],array('id' => 'thangct', 'class' => 'form-control'))!!}
                            </div>
                            <label class="control-label col-md-1" style="text-align: right">Năm </label>
                            <div class="col-md-2">
                                {!! Form::select('namct',getNam(),$inputs['nam'], array('id' => 'namct', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Hệ số</br>truy lĩnh</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{isset($a_pl[$value->maphanloai])? $a_pl[$value->maphanloai]:'' }}</td>
                                        <td>{{$value->tencanbo}}</td>
                                        <td class="text-center">{{$value->heso}}</td>
                                        <td class="text-center">{{$value->mabl == null?"Chưa chi trả":'Đã chi trả'}}</td>

                                        <td>

                                            @if($value->mabl == null)
                                                <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>&nbsp; Sửa</a>
                                                <button type="button" onclick="cfDel('{{$furl.'del/'.$value->id}}')" class="btn btn-default btn-xs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            @else
                                                <a href="{{url($furl.'create?maso='.$value->maso)}}" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>&nbsp; Chi tiết</a>
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

    <!--Modal thêm mới thông tin truy lĩnh -->
    {!! Form::open(['url'=>'/nghiep_vu/truy_linh/create','method'=>'get', 'id' => 'create']) !!}
    <div id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin truy lĩnh lương của cán bộ</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Phân loại</label>
                    {!!Form::select('maphanloai',getPhanLoaiTruyLinh(), null, array('id' => 'maphanloai','class' => 'form-control select2me'))!!}

                    <label class="form-control-label">Họ và tên cán bộ</label>
                    <select name="macanbo" id="macanbo" class="form-control select2me">
                        @foreach($a_canbo as $key=>$val)
                            <option value="{{$key}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="madv" name="madv" value="{{session('admin')->madv}}" />
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}



    <script>
        function add(){
            $('#maso').val('ADD');
            $('#create-modal').modal('show');
        }

        function getLink(){
            var thang = $("#thangct").val();
            var nam = $("#namct").val();
            return '{{$furl}}'+'danh_sach?thang='+thang +'&nam='+nam;
        }
        $(function(){

            $('#thangct').change(function(){
                window.location.href = getLink();
            });

            $('#namct').change(function(){
                window.location.href = getLink();
            });

        })
    </script>

    @include('includes.modal.delete')
    @include('includes.script.scripts')
@stop