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
                    <div class="caption">DANH SÁCH CÁC ĐƠN VỊ</div>
                    <div class="actions">
                        <div class="actions">
                            <a class="btn btn-default" href="{{url($url.'create?ma_so='.$inputs['ma_so'].'&phan_loai='.$inputs['phan_loai'])}}">
                                <i class="fa fa-plus"></i> Thêm mới
                            </a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body form-horizontal">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nhóm đơn vị sử dụng </label>
                            <div class="col-md-5">
                                {!! Form::select('phanloaitaikhoan',getPhanLoaiTaiKhoan(),$inputs['phan_loai'],array('id' => 'phanloaitaikhoan', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                    </div>

                    <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 4%">STT</th>
                                <th class="text-center">Mã đơn vị</th>
                                <th class="text-center">Tên đơn vị</th>
                                <th class="text-center">Đơn vị gửi dữ</br>liệu tổng hợp</th>
                                @if($inputs['phan_loai'] == 'TH')
                                    <th class="text-center">Phạm vị tổng</br>hợp dữ liệu</th>
                                @else
                                    <th class="text-center">Phân loại đơn vị</th>
                                @endif
                                <th class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(isset($model))
                                    @foreach($model as $key=>$value)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>{{$value->madv}}</td>
                                            <td>{{$value->tendv}}</td>
                                            <td>{{$value->tencqcq}}</td>
                                            @if($inputs['phan_loai'] == 'TH')
                                                <td>{{$value->phamvi}}</td>
                                            @else
                                                <td>{{$value->phanloai}}</td>
                                            @endif
                                            <td>
                                                <a class="btn btn-default btn-xs mbs" href="{{url($url.'ma_so='.$value->madvbc.'&don_vi='.$value->madv.'/edit')}}">
                                                    <i class="fa fa-edit"></i> Chỉnh sửa
                                                </a>

                                                <button type="button" class="btn btn-xs btn-default" data-target="#giatri-modal" data-toggle="modal"
                                                        onclick="giatri('{{$value->madv}}')">
                                                    <i class="fa fa-list-alt"></i> Gán thông tin
                                                </button>

                                                <button type="button" onclick="cfDel('{{$url.'del_donvi/'.$value->madv}}')" class="btn btn-default btn-xs mbs" data-target="#delete-modal-confirm" data-toggle="modal">
                                                    <i class="fa fa-trash-o"></i>&nbsp; Xóa</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-offset-5 col-md-8">
                        <a href="{{url('/danh_muc/khu_vuc/danh_sach?level=H')}}" class="btn btn-default"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal thông tin tùy chọn in bảng lương -->
    <div id="giatri-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-lg modal-dialog modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="hd-inbl" class="modal-title">Thông tin chung</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_0" data-toggle="tab" aria-expanded="true">
                                        Phân loại công tác </a>
                                </li>
                                <li>
                                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                                        Sự nghiệp </a>
                                </li>

                                <li>
                                    <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                        Nguồn kinh phí </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- phân loại công tác -->
                                <div class="tab-pane active" id="tab_0">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption"></div>
                                            <div class="tools"></div>
                                        </div>
                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->
                                            <form action="/danh_muc/khu_vuc/update_plct" class="form-horizontal" id="frm_plct">
                                                <div class="form-body">
                                                    <div class="form-horizontal">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Phân loại công tác cũ</label>
                                                                <select class="form-control select2me" name="mact_cu" id="mact_cu">
                                                                    @foreach($model_nhomct as $kieuct)
                                                                        <optgroup label="{{$kieuct->tencongtac}}">
                                                                            <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                                            @foreach($mode_ct as $ct)
                                                                                <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="control-label">Phân loại công tác mới</label>
                                                                <select class="form-control select2me" name="mact_moi" id="mact_moi">
                                                                    @foreach($model_nhomct as $kieuct)
                                                                        <optgroup label="{{$kieuct->tencongtac}}">
                                                                            <?php $mode_ct=$model_tenct->where('macongtac',$kieuct->macongtac); ?>
                                                                            @foreach($mode_ct as $ct)
                                                                                <option value="{{$ct->mact}}">{{$ct->tenct}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="madv" name="madv"/>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="row text-center">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn default">Hoàn thành</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>

                                <!-- sự nghiệp công tác -->
                                <div class="tab-pane" id="tab_1">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption"></div>
                                            <div class="tools"></div>
                                        </div>
                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->
                                            <form action="/danh_muc/khu_vuc/update_sunghiep" class="form-horizontal" id="frm_sunghiep">
                                                <div class="form-body">
                                                    <div class="form-horizontal">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Sự nghiệp cán bộ cũ</label>
                                                                <select class="form-control select2me" name="sunghiep_cu" id="sunghiep_cu">
                                                                    <option value="Công chức">Công chức</option>
                                                                    <option value="Viên chức">Viên chức</option>
                                                                    <option value="Khác">Khác</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="control-label">Sự nghiệp cán bộ mới</label>
                                                                <select class="form-control select2me" name="sunghiep_moi" id="sunghiep_moi">
                                                                    <option value="Công chức">Công chức</option>
                                                                    <option value="Viên chức">Viên chức</option>
                                                                    <option value="Khác">Khác</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="madv" name="madv"/>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="row text-center">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn default">Hoàn thành</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>

                                <!-- Nguồn kinh phí -->
                                <div class="tab-pane" id="tab_2">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption"></div>
                                            <div class="tools"></div>
                                        </div>
                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->
                                            <form action="/danh_muc/khu_vuc/update_nguonkp" class="form-horizontal" id="frm_sunghiep">
                                                <div class="form-body">
                                                    <div class="form-horizontal">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="control-label">Sự nghiệp cán bộ cũ</label>
                                                                <select class="form-control select2me" name="sunghiep_cu" id="sunghiep_cu">
                                                                    <option value="Công chức">Công chức</option>
                                                                    <option value="Viên chức">Viên chức</option>
                                                                    <option value="Khác">Khác</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="control-label">Sự nghiệp cán bộ mới</label>
                                                                <select class="form-control select2me" name="sunghiep_moi" id="sunghiep_moi">
                                                                    <option value="Công chức">Công chức</option>
                                                                    <option value="Viên chức">Viên chức</option>
                                                                    <option value="Khác">Khác</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="madv" name="madv"/>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="row text-center">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn default">Hoàn thành</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- END FORM-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>

    <script>
        function giatri(madv){
            $('#frm_plct').find("[id^='madv']").val(madv);
            $('#frm_sunghiep').find("[id^='madv']").val(madv);
        }

        $(function(){
            $('#phanloaitaikhoan').change(function() {
                window.location.href = '/danh_muc/khu_vuc/chi_tiet?ma_so='+'{{$inputs['ma_so']}}'+'&phan_loai='+$('#phanloaitaikhoan').val();
            });
        })
    </script>
    @include('includes.modal.delete')
@stop