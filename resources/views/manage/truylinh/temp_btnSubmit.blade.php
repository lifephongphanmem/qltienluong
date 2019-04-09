<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05/04/2019
 * Time: 4:00 PM
 */
        ?>
<div style="text-align: center; padding-bottom: 15px">
    @if(!isset($model->mabl) ||(isset($model->mabl) && $model->mabl == null))
        <button type="submit" class="btn green" onclick="validateForm()"><i class="fa fa-check"></i> Hoàn thành</button>
    @endif
    <a href="{{url($furl.'danh_sach')}}" class="btn btn-danger"><i class="fa fa-reply"></i>&nbsp;Quay lại</a>
</div>