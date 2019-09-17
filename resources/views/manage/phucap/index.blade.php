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
                        DANH SÁCH QUÁ TRÌNH HƯỞNG PHỤ CẤP CỦA CÁN BỘ
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-default btn-xs" onclick="add()"><i class="fa fa-plus"></i>&nbsp;Thêm mới</button>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Họ tên cán bộ </label>
                            <div class="col-md-5">
                                {!!Form::select('cbmacb',$a_cb,$inputs['canbo'],array('id'=>'cbmacb','class'=>'form-control select2me'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">STT</th>
                                <th class="text-center">Từ ngày</th>
                                <th class="text-center">Đến ngày</th>
                                <th class="text-center">Phụ cấp</th>
                                <th class="text-center">Hệ số</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($model as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{getDayVn($value->ngaytu)}}</td>
                                    <td class="text-center">{{getDayVn($value->ngayden)}}</td>
                                    <td>{{isset($a_pc[$value->mapc])?$a_pc[$value->mapc]:'' }}</td>
                                    <td class="text-center">{{$value->heso}}</td>
                                    <td>
                                        <a href="{{url($inputs['furl'].'edit?maso='.$value->maso)}}" class="btn btn-default btn-xs mbs">
                                            <i class="fa fa-edit"></i>&nbsp;Sửa</a>
                                        <button type="button" onclick="cfDel('{{$value->maso}}')" class="btn btn-danger btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                            <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin chi tiết -->
    <!--div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                    <h4 id="modal-header-primary-label" class="modal-title">Thông tin hồ sơ quá trình hưởng phụ cấp của cán bộ</h4>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Từ ngày<span class="require">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="ngaytu" id="ngaytu" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Đến ngày</label>
                            <div class="col-md-8">
                                <input type="date" name="ngayden" id="ngayden" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Phụ cấp</label>
                            <div class="col-md-8">
                                {!!Form::select('mapc',$a_pc,null,['id'=>'mapc','class'=>'form-control select2me'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label"> Hệ số phụ cấp</label>
                            <div class="col-md-8">
                                {!!Form::text('heso1', null, array('id' => 'heso1','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                            </div>
                        </div>

                        <input type="hidden" id="id_ct" name="id_ct"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" onclick="confirm()">Đồng ý</button>
                </div>
            </div>
        </div>
    </div-->

    {!! Form::open(['url'=>$inputs['furl'].'store','method'=>'post' , 'files'=>true, 'id' => 'create_bangluong']) !!}
    <div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin quá trình hưởng lương, phụ cấp của cán bộ</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <table id="sample_4" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">STT</th>
                            <th class="text-center">Họ tên cán bộ</th>
                            <th class="text-center">Chức vụ</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                        </thead>
                        <?php $i=1;?>
                        <tbody>
                            @foreach($m_cb as $key=>$value)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$value->tencanbo}}</td>
                                    <td>{{isset($a_cv[$value->macvcq])? $a_cv[$value->macvcq] : ''}}</td>
                                    <td class="text-center">
                                        <a href="{{url($inputs['furl'].'create?macanbo='.$value->macanbo)}}" class="btn btn-default btn-xs mbs">
                                            <i class="fa fa-edit"></i>&nbsp;Chọn</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer"></div>
        </div>
    </div>
    {!! Form::close() !!}

    <div id="delete-modal-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        {!! Form::open(['url'=>$inputs['furl'].'del','method'=>'post' , 'files'=>true, 'id' => 'frmDelete']) !!}
            <input type="hidden" id="maso" name="maso"/>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                class="close">&times;</button>
                        <h4 id="modal-header-primary-label" class="modal-title">Đồng ý xoá?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
                        <button type="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

    <script>
        function add(){
            $('#id').val('ADD');
            $('#chitiet-modal').modal('show');
        }

        $(function(){
            $("#cbmacb").change(function(){
                window.location.href = '{{$inputs['furl']}}' + 'danh_sach?canbo=' + $('#cbmacb').val();
            });
        });

        function cfDel(maso){
            $('#frmDelete').find('[id="maso"]').attr('value',maso);
        }
    </script>


@stop