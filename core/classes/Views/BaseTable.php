<?php


namespace Core\Views;


use Core\View;

class BaseTable extends View
{
    public function render($template_path = ROOT . '/core/templates/baseTable.tpl.php')
    {
        return parent::render($template_path);
    }
}