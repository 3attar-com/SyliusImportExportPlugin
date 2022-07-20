<?php

namespace FriendsOfSylius\SyliusImportExportPlugin\Service\Date;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DateHandler
{

    protected $from;
    protected $to;

    public function __construct($from, $to)
    {

        $this->from = $from;
        $this->to = $to;
    }

    private function toAssigned()
    {
        return $this->to == "" ? "" : "To";
    }

    private function fromAssigned()
    {
        return $this->from == "" ? "" : "From";
    }

    public function handel(): array
    {
        $className = "";
        $className .= $this->fromAssigned();
        $className .= $this->toAssigned();
        $className .= "DateFactory";
        $className = $className=="DateFactory" ? "NoAssignedDateFactory":$className;
        $class = 'FriendsOfSylius\\SyliusImportExportPlugin\\Service\\Date' . "\\{$className}";
        $test = new $class($this->from, $this->to);

        return [
            'from' => $test->getFrom(),
            'to' => $test->getTo()
        ];
    }

}
