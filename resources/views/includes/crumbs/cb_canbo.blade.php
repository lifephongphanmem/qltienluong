<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 7/13/2016
 * Time: 9:49 AM
 */
        ?>
<div class="row">
    <div class="form-group">
        <label class="control-label col-md-3">Họ tên cán bộ </label>
        <div class="col-md-5">
            <select class="form-control select2me" class="col-md-5" id="cbmacb" name="cbmacb" onchange="getInfo()">
                <option value="all" selected>-- Nhập họ tên cán bộ --</option>
                @foreach($m_pb as $pb)
                    <optgroup label="{{$pb->tenpb}}">
                        <?php $m=$m_cb->where('mapb',$pb->mapb); ?>

                        @if(isset($model))
                            @foreach($m as $cb)
                                <option value="{{$cb->macanbo}}" {{$cb->macanbo==$macanbo?'selected':''}}>{{$cb->tencanbo}}</option>
                            @endforeach
                        @else
                            @foreach($m as $cb)
                                <option value="{{$cb->macanbo}}">{{$cb->tencanbo}}</option>
                            @endforeach
                        @endif
                    </optgroup>
                    @endforeach
                            <!--Trường hợp cán bộ ko nhập phòng ban-->
                    <optgroup label="Không xác định">
                        <?php $m=$m_cb->where('mapb',null); ?>
                        @if(isset($model))
                            @foreach($m as $cb)
                                <option value="{{$cb->macanbo}}" {{$cb->macanbo==$macanbo?'selected':''}}>{{$cb->tencanbo}}</option>
                            @endforeach
                        @else
                            @foreach($m as $cb)
                                <option value="{{$cb->macanbo}}">{{$cb->tencanbo}}</option>
                            @endforeach
                        @endif
                    </optgroup>
            </select>
        </div>
    </div>
</div>