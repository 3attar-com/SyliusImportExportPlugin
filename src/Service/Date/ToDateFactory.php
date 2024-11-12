<?php

namespace FriendsOfSylius\SyliusImportExportPlugin\Service\Date;

class ToDateFactory extends DateHandler implements DateFactoryInterface
{
    public function getTo()
    {
        return $this->to;
    }

    public function getFrom()
    {
        $toStr = strtotime($this->getTo());
        $from= date('Y-m-d',strtotime("-6 months",($toStr)));;
        return $from;
    }

}
