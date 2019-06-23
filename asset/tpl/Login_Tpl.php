<html>
<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
    <title><?php echo $title; ?></title>
    <meta name="language" content="<?php echo $language; ?>"/>
    <link rel="stylesheet" type="text/css" href="./skin/OGSpy_skin/formate.css"/>
    <!-- custom CSS-->
    <?php if (isset($css)): ?>
        <?php foreach ($css as $currentCSS): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $currentCSS; ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- toottipster for testing-->


    <link rel="stylesheet" type="text/css" href="vendor/tooltipster/tooltipster/dist/css/tooltipster.bundle.min.css"/>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
    <script type="text/javascript" src="vendor/tooltipster/tooltipster/dist/js/tooltipster.bundle.min.js"></script>


    <!-- fin test





    <!-- custom JS-->.
    <?php if (isset($css)): ?>
        <?php foreach ($js as $currentJS): ?>
            <script type="text/javascript" src="<?php echo $currentJS; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="icon" type="image/icon" href="favicon.ico"/>
</head>

<body>
<h1>
    <?php echo $title; ?>
</h1>
<div class="content">
    <?php echo $content; ?>
</div>
<div class="content">
    <span class="tooltip" data-tooltip-content="#tooltip_content">This span has a tooltip with HTML when you hover over it!</span>

    <div class="tooltip_templates" style="display: none; ">
        <span id="tooltip_content">
            <strong>This is the content of my tooltip!</strong>
        </span>
    </div>

</div>


</body>


<script>
    $(document).ready(function () {
        $('.tooltip').tooltipster();
    });
</script>


</html>





