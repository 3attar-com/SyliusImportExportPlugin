<?php

namespace FriendsOfSylius\SyliusImportExportPlugin\Service\Date;

class FromDateFactory extends DateHandler implements DateFactoryInterface
{
    public function getTo()
    {
        $fromStr = strtotime($this->getFrom());
        $to = date('Y-m-d', strtotime("+6 months", ($fromStr)));;
        return $to;
    }

    public function getFrom()
    {
        return $this->from;
    }
}
