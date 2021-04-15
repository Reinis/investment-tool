<?php

namespace InvestmentTool\Views;

interface View
{
    public function render(string $name, array $context = []): string;
}
