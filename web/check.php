<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    die('This script cannot be run from the CLI. Run it from a browser.');
}

if (!in_array(@$_SERVER['REMOTE_ADDR'], array(
    '127.0.0.1',    //localhost
    '10.0.2.2',     //virtualbox gateway
    '10.2.71.8',    //digitas proxy
    '::1',
))) {
    header('HTTP/1.0 403 Forbidden');
    die('This script is only accessible from localhost.');
}

$majorProblems = array();
$minorProblems = array();
$phpini = false;

// minimum
if (!version_compare(phpversion(), '5.3.2', '>=')) {
    $version = phpversion();
    $majorProblems[] = <<<EOF
        You are running PHP version "<strong>$version</strong>", but Silex
        needs at least PHP "<strong>5.3.2</strong>" to run. Before using Silex, install
        PHP "<strong>5.3.2</strong>" or newer.
EOF;
}

if (!is_writable(__DIR__ . '/../app/cache')) {
    $majorProblems[] = 'Change the permissions of the "<strong>app/cache/</strong>"
        directory so that the web server can write into it.';
}

if (!is_writable(__DIR__ . '/../app/logs')) {
    $majorProblems[] = 'Change the permissions of the "<strong>app/log/</strong>"
        directory so that the web server can write into it.';
}

if(extension_loaded('suhosin') && !in_array('phar', explode(',', ini_get("suhosin.executor.include.whitelist")))) {
    $majorProblems[] = 'Add <strong>suhosin.executor.include.whitelist="phar"</strong> to php.ini<a href="#phpini">*</a>.';
}

// extensions
if (!class_exists('DomDocument')) {
    $minorProblems[] = 'Install and enable the <strong>php-xml</strong> module.';
}

if (!defined('LIBXML_COMPACT')) {
    $minorProblems[] = 'Upgrade your <strong>php-xml</strong> extension with a newer libxml.';
}

if (!((function_exists('apc_store') && ini_get('apc.enabled')) || function_exists('eaccelerator_put') && ini_get('eaccelerator.enable') || function_exists('xcache_set'))) {
    $minorProblems[] = 'Install and enable a <strong>PHP accelerator</strong> like APC (highly recommended).';
}

if (!function_exists('token_get_all')) {
    $minorProblems[] = 'Install and enable the <strong>Tokenizer</strong> extension.';
}

if (!function_exists('mb_strlen')) {
    $minorProblems[] = 'Install and enable the <strong>mbstring</strong> extension.';
}

if (!function_exists('iconv')) {
    $minorProblems[] = 'Install and enable the <strong>iconv</strong> extension.';
}

if (!function_exists('utf8_decode')) {
    $minorProblems[] = 'Install and enable the <strong>XML</strong> extension.';
}

if (PHP_OS != 'WINNT' && !function_exists('posix_isatty')) {
    $minorProblems[] = 'Install and enable the <strong>php_posix</strong> extension (used to colorize the CLI output).';
}

if (!class_exists('Locale')) {
    $majorProblems[] = 'Install and enable the <strong>intl</strong> extension.';
}

if (!function_exists('mysql_connect') || !in_array('mysql', PDO::getAvailableDrivers())) {
    $majorProblems[] = 'Install and enable <strong>MySQL</strong> and <strong>PDO_MySQL</strong> extension.';
}

if (!function_exists('json_encode')) {
    $majorProblems[] = 'Install and enable the <strong>json</strong> extension.';
}

// php.ini
if (!ini_get('date.timezone')) {
    $phpini = true;
    $majorProblems[] = 'Set the "<strong>date.timezone</strong>" setting in php.ini<a href="#phpini">*</a> (like Europe/Paris).';
}

if (ini_get('short_open_tag')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>short_open_tag</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('magic_quotes_gpc')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>magic_quotes_gpc</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('register_globals')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>register_globals</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}

if (ini_get('session.auto_start')) {
    $phpini = true;
    $minorProblems[] = 'Set <strong>session.auto_start</strong> to <strong>off</strong> in php.ini<a href="#phpini">*</a>.';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Silex Configuration</title>
        <style type="text/css">
        html,legend{color:#000}html{background:#FFF}td,th,blockquote,p,textarea,button,input,legend,fieldset,form,code,pre,h6,h5,h4,h3,h2,h1,li,ol,ul,dd,dt,dl,div,body{margin:0;padding:0}table{border-collapse:collapse;border-spacing:0}img,fieldset,acronym,abbr{border:0}optgroup,var,th,strong,em,dfn,code,cite,caption,address{font-style:inherit;font-weight:inherit}ins,del,a{text-decoration:none}li{list-style:none;padding-bottom:18px}th,caption{text-align:left}h6,h5,h4,h3,h2,h1{font-size:100%;font-weight:normal}q:after,q:before{content:''}acronym,abbr{font-variant:normal}sup,sub{vertical-align:baseline}option,optgroup,select,textarea,button,input{font:inherit inherit inherit inherit}select,textarea,button,input{*font-size:100%}body,html{background:#efefef}body{font:14px "lucida sans unicode","lucida grande",verdana,arial,helvetica,sans-serif;color:#313131}a{color:#08C}a:hover{text-decoration:underline}strong,h2{font-weight:bold}em{font-style:italic}h3,h2,h1{font-family:Georgia,"Times New Roman",Times,serif;color:#404040}h1{font-size:45px;padding-bottom:30px}h2{background:#aacd4e;color:#fff;font-family:"Lucida Sans Unicode","Lucida Grande",Verdana,Arial,Helvetica,sans-serif;margin-bottom:10px;padding:2px 4px;display:inline-block;text-transform:uppercase}p{line-height:20px;padding-bottom:20px}ul a{background:url(../images/blue-arrow.png) no-repeat right 6px;padding-right:10px}ol,ul{padding-left:20px}ol li{list-style-type:decimal}ul li{list-style-type:none}#symfony-header{position:relative;padding:30px 30px 20px 30px}#symfony-wrapper{width:970px;margin:0 auto;padding-top:50px}#symfony-content{background:white;border:1px solid #dfdfdf;padding:50px;-moz-border-radius:16px;-webkit-border-radius:16px;border-radius:16px;margin-bottom:20px}.version{text-align:right;font-size:10px;margin-right:20px}
        </style>
    </head>
    <body>
        <div id="symfony-wrapper">
            <div id="symfony-content">
                <h1>Welcome!</h1>

                <?php if (count($majorProblems)): ?>
                    <h2>
                        <span><?php echo count($majorProblems) ?> Major problems</span>
                    </h2>
                    <p>Major problems have been detected and <strong>must</strong> be fixed before continuing :</p>
                    <ol>
                        <?php foreach ($majorProblems as $problem): ?>
                            <li><?php echo $problem; ?></li>
                        <?php endforeach ?>
                    </ol>
                <?php endif ?>

                <?php if (count($minorProblems)): ?>
                    <h2>Recommendations</h2>
                    <p>
                        <?php if ($majorProblems): ?>
                            Additionally, to
                        <?php else: ?>
                            To<?php endif; ?>
                        enhance your Silex experience, it’s recommended that you fix the following :
                    </p>
                    <ol>
                        <?php foreach ($minorProblems as $problem): ?>
                        <li><?php echo $problem; ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif ?>

                <?php if ($phpini): ?>
                        <a name="phpini"></a>
                            <p>*
                                <?php if (get_cfg_var('cfg_file_path')): ?>
                                    Changes to the <strong>php.ini</strong> file must be done in "<strong><?php echo get_cfg_var('cfg_file_path') ?></strong>".
                                <?php else: ?>
                                    To change settings, create a "<strong>php.ini</strong>".
                                <?php endif; ?>
                            </p>
                        </div>
                <?php endif; ?>

                <?php if (!count($majorProblems) && !count($minorProblems)): ?>
                <p>Everything seems to be OK !</p>
                <?php endif ?>
        </div>
        <div class="version">Silex-Sandbox by Digitas</div>
    </body>
</html>
