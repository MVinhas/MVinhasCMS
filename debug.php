
<?php
use \Engine\Superglobals as Superglobals;
$globals = new Superglobals;
    if (isset($db_error))
        if (!empty($db_error)) print_r($db_error);
?>

    <pre class='debug'>
    <br><b>Script running time:</b> <?php print_r(round(microtime(true) - $time_start,3)); ?>  seconds.
    <br><b>PHP and MYSQL:</b>
    <div id="php-sql-errors"></div>
    <br><b>SESSION:</b>
    <?php print_r($globals->session()); ?>
    <br><b>POST:</b>
    <?php print_r($globals->post()); ?>
    <br><b>GET:</b>
    <?php print_r($globals->get()); ?>
    <br><b>SERVER:</b>
    <?php print_r($globals->server()); ?>
    <br><b>FILES:</b>
    <?php print_r($globals->files()); ?>
    </pre>
<script>
    let divs = document.getElementsByClassName("debug-silent");
    let elem = document.querySelector('#php-sql-errors');
    for (i=0;i<divs.length;i++) {
        var str = divs[i].innerText;
        elem.innerHTML = elem.innerHTML + str;
        divs[i].innerHTML = '';
        divs[i].style.visibility = 'hidden';
    }
 </script>