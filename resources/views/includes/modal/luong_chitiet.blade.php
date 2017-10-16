<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 8/8/2016
 * Time: 2:30 PM
 */
?>
<div id="chitiet-modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-primary">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                <h4 id="modal-header-primary-label" class="modal-title">Thông tin quá trình hưởng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" id="chitiet">
                    @include('includes.crumbs.tudenngay')

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Mã ngạch bậc<span class="require">*</span></label>
                        <div class="col-md-8">
                            {!!Form::text('msngbac', null, array('id' => 'msngbac','class' => 'form-control', 'readonly'=>'true'))!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Ngạch bậc </label>
                        <div class="col-sm-8">
                            <select class="form-control select2me" name="tennb" id="tennb" onchange="setMSNGBAC()">
                                @foreach($m_plnb as $plnb)
                                    <optgroup label="{{$plnb->tennhom}}">
                                        <?php $mode_ct=$m_pln->where('manhom',$plnb->manhom); ?>
                                        @foreach($mode_ct as $ct)
                                            <option value="{{$ct->msngbac}}">{{$ct->tenngachluong}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Bậc lương </label>

                        <div class="col-sm-8 controls">
                            <select class="form-control select2me" name="bac" id="bac" onchange="getHS()">
                                @for($i=1;$i<=16;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Hệ số lương</label>
                        <div class="col-md-8">
                            {!!Form::text('heso', null, array('id' => 'heso','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Hệ số vượt khung</label>
                        <div class="col-md-8">
                            {!!Form::text('vuotkhung', null, array('id' => 'vuotkhung','class' => 'form-control','data-mask'=>'fdecimal'))!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label"> Phần trăm hưởng lương</label>
                        <div class="col-md-8">
                            {!!Form::text('pthuong', null, array('id' => 'pthuong','class' => 'form-control','data-mask'=>'fdecimal'))!!}
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
</div>