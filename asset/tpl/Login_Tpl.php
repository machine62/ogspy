<html>
    <head>
        <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
        <title><?php echo $title; ?></title>
        <meta name="language" content="<?php echo $language; ?>"/>
        <link rel="stylesheet" type="text/css" href="./skin/OGSpy_skin/formate.css"/>
        <!-- custom CSS-->
        <?php if (isset($css)):?>
            <?php foreach ($css as $currentCSS):?>
                <link rel="stylesheet" type="text/css" href="<?php echo $currentCSS; ?>"/>
            <?php endforeach ; ?>
        <?php endif ; ?>

        <!-- custom JS-->.
        <?php if (isset($css)):?>
        <?php foreach ($js as $currentJS):?>
            <script type="text/javascript" src="<?php echo $currentJS;?>"></script>
        <?php endforeach ; ?>
        <?php endif ; ?>

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
    </body>
</html>




