<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue" id="form_wizard_1">
            <div class="portlet-title">
                <div class="caption">
                    Thông tin nhu cầu kinh phí thực hiện cải cách tiền lương
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>

            <div class="portlet-body form" id="form_wizard">
                <div class="form-body" style="padding-left: 10px; padding-right: 10px">
                    <ul class="nav nav-pills nav-justified steps">
                       
                        <li>
                            <a href="#tab2a" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2a</p>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2b" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2b</p>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2c" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2c và 2e</p>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2d" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2d</p>
                            </a>
                        </li>
                       
                        {{-- <li><a href="#tab2e" data-toggle="tab" class="step">
                                <p class="description"> <i class="glyphicon glyphicon-check"></i> Mẫu 2e</p>
                            </a>
                        </li> --}}
                        
                        <li><a href="#tab4a" data-toggle="tab" class="step">
                            <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4a</p>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="#tab4b" data-toggle="tab" class="step">
                            <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4b</p>
                        </a>
                    </li> --}}
                    </ul>

                    <div id="bar" class="progress progress-striped" role="progressbar">
                        <div class="progress-bar progress-bar-success">
                        </div>
                    </div>

                    <div class="tab-content">
                        @include('manage.nguonkinhphi.TT78.mau4a')
                        {{-- @include('manage.nguonkinhphi.TT78.mau4b') --}}
                        @include('manage.nguonkinhphi.TT78.mau2a')
                        @include('manage.nguonkinhphi.TT78.mau2b')
                        @include('manage.nguonkinhphi.TT78.mau2c')
                        @include('manage.nguonkinhphi.TT78.mau2d')
                        @include('manage.nguonkinhphi.TT78.mau2e')
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
