<?php

function d($variable)
{
    echo "<pre>".print_r($variable, true)."</pre>";
}

function dd($variable)
{
    d($variable);
    die();
}