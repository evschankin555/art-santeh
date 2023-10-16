<div class="alert alert-warning" role="alert">
    Warning: Your configurations in Appearance> Customize will be changed.
</div>

<div class="row">
    <div class="col-3 col-xl-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-header-preset" data-toggle="pill" href="#v-pills-header" role="tab" aria-controls="v-pills-header" aria-selected="true">Header</a>
            <a class="nav-link" id="v-pills-announcement-preset" data-toggle="pill" href="#v-pills-announcement" role="tab" aria-controls="v-pills-announcement" aria-selected="false">Announcement Bar</a>
            <a class="nav-link" id="v-pills-shop-catalog-preset" data-toggle="pill" href="#v-pills-shop-catalog" role="tab" aria-controls="v-pills-shop-catalog" aria-selected="false">Shop Catalog</a>
            <a class="nav-link" id="v-pills-single-product-preset" data-toggle="pill" href="#v-pills-single-product" role="tab" aria-controls="v-pills-single-product" aria-selected="false">Single Product</a>
        </div>
    </div>
    <div class="col-9 col-xl-10">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-header" role="tabpanel" aria-labelledby="v-pills-header-preset">
                <?php fmfw_show_preset_element('header');?>
            </div>
            <div class="tab-pane fade" id="v-pills-announcement" role="tabpanel" aria-labelledby="v-pills-announcement-preset">
                <?php fmfw_show_preset_element('announcement');?>
            </div>
            <div class="tab-pane fade" id="v-pills-shop-catalog" role="tabpanel" aria-labelledby="v-pills-shop-catalog-preset">
                <?php fmfw_show_preset_element('shop_catalog');?>
            </div>
            <div class="tab-pane fade" id="v-pills-single-product" role="tabpanel" aria-labelledby="v-pills-single-product-preset">
                <?php fmfw_show_preset_element('product');?>
            </div>
        </div>
    </div>
</div>
