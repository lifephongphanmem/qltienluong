<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02/04/2019
 * Time: 8:54 AM
 */
?>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    Thông tin nguồn kinh phí truy lĩnh
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body" style="display: block;">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-offset-10 col-md-2">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-default" data-target="#nguonkp-modal" data-toggle="modal" onclick="add_nkp()">
                                    Thêm mới
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="dsnkp">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table id="sample_3" class="table table-hover table-striped table-bordered" style="min-height: 230px">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">STT</th>
                                        <th class="text-center">Nguồn kinh phí</th>
                                        <th class="text-center" style="width: 15%">Số tiền</th>
                                        <th class="text-center" style="width: 15%">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=1;?>
                                    <tbody>
                                    @if(isset($model_nkp))
                                        @foreach($model_nkp as $key=>$value)
                                            <tr>
                                                <td class="text-center">{{$i++}}</td>
                                                <td>{{isset($a_nkp[$value->manguonkp]) ? $a_nkp[$value->manguonkp]: ''}}</td>
                                                <td class="text-right">{{dinhdangso($value->luongcoban)}}</td>
                                                <td>
                                                    <button type="button" onclick="edit_nkp('{{$value->id}}')" class="btn btn-default btn-xs mbs"
                                                            data-target="#nguonkp-modal" data-toggle="modal"><i class="fa fa-edit"></i>&nbsp; Sửa</button>
                                                    <button type="button" class="btn btn-default btn-xs mbs" onclick="cfDel('{{$value->id}}')"
                                                            data-target="#delete-modal-confirm" data-toggle="modal"><i class="fa fa-trash-o"></i>&nbsp;Xóa</button>
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
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
</div>

