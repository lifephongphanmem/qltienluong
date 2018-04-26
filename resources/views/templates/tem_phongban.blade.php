<?php
/**
 * Created by PhpStorm.
 * User: MLC
 * Date: 28/06/2016
 * Time: 8:29 AM
 */
?>
<label class="form-control-label">Mã số<span class="require">*</span></label>
{!!Form::text('mapb', null, array('id' => 'mapb','class' => 'form-control','readonly'=>'true'))!!}

<label class="form-control-label">Tên khối/tổ công tác<span class="require">*</span></label>
{!!Form::text('tenpb', null, array('id' => 'tenpb','class' => 'form-control','required'=>'required'))!!}

<label class="form-control-label">Mô tả</label>
{!!Form::textarea('diengiai', null, array('id' => 'diengiai','class' => 'form-control','rows'=>'3'))!!}

<label class="form-control-label">Sắp xếp</label>
{!!Form::text('sapxep', null, array('id' => 'sapxep','class' => 'form-control'))!!}

<input type="hidden" id="id_pb" name="id_pb"/>