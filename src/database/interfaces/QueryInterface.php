<?php
namespace Database\Interfaces;

interface QueryInterface
{
    public function queryBuilder();

    public function raw();
}