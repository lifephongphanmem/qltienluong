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
                        DANH MỤC KHU VỰC, ĐỊA BÀN QUẢN LÝ
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Cấp độ quản lý </label>
                            <div class="col-md-5">
                                {!! Form::select('mucbaomat',$a_baomat,$level,array('id' => 'mucbaomat', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>
                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">STT</th>
                                <th class="text-center">Tên khu vực, địa bàn</th>
                                <th class="text-center">Đơn vị quản lý</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($model))
                                @foreach($model as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$value->tendvbc}}</td>
                                        <td>{{$value->ghichu}}</td>
                                        <td>
                                            <a href="{{url('/danh_muc/tai_khoan/list_user?&madb='.$value->madvbc)}}" class="btn btn-default btn-xs mbs">
                                                <i class="fa fa-list-alt"></i>&nbsp; Danh sách đơn vị</a>
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
    <div id="phongban-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin khu vực, địa bàn quản lý</h4>
                </div>
                <div class="modal-body">
                    <label class="form-control-label">Tên khu vực, địa bàn quản lý<span class="require">*</span></label>
                    {!!Form::text('tendvbc', null, array('id' => 'tendvbc','class' => 'form-control'))!!}

                    <label class="form-control-label">Cấp độ quản lý</label>
                    {!! Form::select('level',$a_baomat,$level,array('id' => 'level', 'class' => 'form-control'))!!}

                    <label class="form-control-label">Ghi chú</label>
                    {!!Form::textarea('ghichu', null, array('id' => 'ghichu','class' => 'form-control','rows'=>'3'))!!}

                    <input type="hidden" id="madvbc" name="madvbc"/>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="cfPB()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#mucbaomat').change(function() {
                window.location.href = '/danh_muc/tai_khoan/list_user?level='+$('#mucbaomat').val();
            });
        })
    </script>

    @include('includes.modal.delete')
@stop