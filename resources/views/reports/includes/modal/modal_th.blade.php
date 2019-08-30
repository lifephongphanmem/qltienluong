<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 6/14/2018
 * Time: 9:34 AM
 */
?>
<div id="thttkhoi-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    {!! Form::open(['url'=>'#','target'=>'_blank' ,'method'=>'post' ,'id' => 'thoaibc46', 'class'=>'form-horizontal form-validate']) !!}
    <div class="modal-dialog modal-content">
        <div class="modal-header modal-header-primary">
            <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
            <h4 id="modal-header-primary-label" class="modal-title">Thông tin kết xuất báo cáo</h4>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="modal-body">
                    <div class="form-horizontal">
                        @if(session('admin')->level=='H')
                            <div class="form-group">
                                <label class="col-md-4 control-label"> Đơn vị<span class="require">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2me" name="madv" id="madv" >
                                        <option value="">Tất cả các đơn vị</option>
                                        @if(isset($model_dv))
                                            @foreach($model_dv as $dv)
                                                <option value="{{$dv->madv}}">{{$dv->tendv}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(session('admin')->level=='T')
                            <div class="form-group">
                                <label class="col-md-4 control-label"> Khu vực, địa bàn<span class="require">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control select2me" id="madv" name="madv" class="form-control">
                                        <option value="">Tất cả các đơn vị</option>

                                        @if(session('admin')->username != 'khthso' && isset($model_dvbc))
                                            @foreach($model_dvbc as $dv)
                                                <option value="{{$dv->madvbc}}">{{$dv->tendvbc}}</option>
                                            @endforeach
                                        @endif
                                        @if(session('admin')->username == 'khthso' && isset($model_dvbcT))
                                            @foreach($model_dvbcT as $dvT)
                                                <option value="{{$dvT->madv}}">{{$dvT->tendv}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-4 control-label"> Đơn vị tính</label>
                            <div class="col-md-8">
                                {!! Form::select('donvitinh',getDonViTinh(),'1',array('id' => 'donvitinh', 'class' => 'form-control'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label"> </label>
                            <input type="checkbox" name="inchitiet" />
                            <label > In chi tiết các đơn vị</label>
                            </br>
                            <label class="col-md-4 control-label"> </label>
                            <input type="checkbox" name="excel" id = "excel"/>
                            Xuất dữ liệu ra file excel
                            </br>
                            <label class="col-md-4 control-label"> </label>
                            <input type="checkbox" name="inheso" id = "inheso"/>
                            In theo hệ số
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="urlbc67" id="urlbc67" value="">
                    </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">Hủy thao tác</button>
            <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Đồng ý</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

