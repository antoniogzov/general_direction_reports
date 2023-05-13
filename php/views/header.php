<div class="header bg-gradient-default pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0"><?= $info_header_module['sub_module_name'] ?></h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="<?= $info_header_module['link_module'] ?>"><?= $info_header_module['module_name'] ?></a></li>
              <li class="breadcrumb-item active" id="info_header_label" aria-current="page"><?= $info_header_module['some_text'] ?></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt--6">