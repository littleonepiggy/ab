<?php

namespace App\Main;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $templateDir, string $varDir)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templateDir);
        $this->smarty->setCompileDir($varDir . '/templates_c');
        $this->smarty->setCacheDir($varDir . '/cache');
        $this->smarty->setEscapeHtml(true);
    }

    public function render(string $template, array $data = []): string
    {
        $this->smarty->assign($data);

        return $this->smarty->fetch($template);
    }
}
