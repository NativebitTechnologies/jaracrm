<head>
    <title><?=SITENAME?> - <?=(isset($headData->pageTitle)) ? $headData->pageTitle : '' ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="<?=base_url();?>assets/src/assets/img/favicon.ico"/>
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?=base_url();?>assets/layouts/collapsible-menu/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?=base_url();?>assets/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/src/jp_styles.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/src/assets/css/light/components/modal.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="<?=base_url();?>assets/src/plugins/src/sweetalerts2/sweetalerts2.css">
    <!--<link href="<?=base_url();?>assets/src/plugins/src/vanillaSelectBox/vanillaSelectBox.css" rel="stylesheet" type="text/css">-->
    <link href="<?=base_url();?>assets/src/plugins/src/vanillaSelectBox/vanillaSelectBox1.03.css" rel="stylesheet" type="text/css">

    <!--  BEGIN DT TABLE STYLE FILE  -->
    <?php if($DT_TABLE): ?>
        <link href="<?=base_url();?>assets/src/dt_table.css" rel="stylesheet" type="text/css" />
        <link href="<?=base_url();?>assets/src/plugins/css/light/table/datatable/dt-global_style.css" rel="stylesheet" type="text/css">
    <?php endif; ?>
    <!--  END DT TABLE STYLE FILE  -->

    <!--  START CUSTOM STYLE FILE  -->
    <link href="<?=base_url();?>assets/src/assets/css/light/elements/tooltip.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->
</head>
