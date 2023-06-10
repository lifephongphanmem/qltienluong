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
                        <li><a href="#tab1" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4a</p>
                            </a>
                        </li>
                        <li><a href="#tab3" data-toggle="tab" class="step">
                            <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 4b</p>
                        </a>
                    </li>
                        <li><a href="#tab2" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>Mẫu 2b</p>
                            </a>
                        </li>
                        <li><a href="#tab6" data-toggle="tab" class="step">
                                <p class="description"><i class="glyphicon glyphicon-check"></i>
                                    Mẫu 2đ</p>
                            </a>
                        </li>
                        <li><a href="#tab4" data-toggle="tab" class="step">
                                <p class="description"> <i class="glyphicon glyphicon-check"></i> Mẫu 2e</p>
                            </a>
                        </li>
                    </ul>

                    <div id="bar" class="progress progress-striped" role="progressbar">
                        <div class="progress-bar progress-bar-success">
                        </div>
                    </div>

                    <div class="tab-content">
                        @include('manage.nguonkinhphi.includes.maukhac')
                        @include('manage.nguonkinhphi.includes.mau2b')
                        @include('manage.nguonkinhphi.includes.mau2đ')
                        @include('manage.nguonkinhphi.includes.mau2e')
                        @include('manage.nguonkinhphi.includes.mau4b')
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>               
