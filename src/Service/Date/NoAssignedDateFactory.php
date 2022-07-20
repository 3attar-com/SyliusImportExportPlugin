<?php

namespace FriendsOfSylius\SyliusImportExportPlugin\Service\Date;

class NoAssignedDateFactory extends DateHandler implements DateFactoryInterface
{
    public function getTo()
    {
        return date('Y-m-d');
    }

    public function getFrom()
    {
        $toStr = strtotime($this->getTo());
        $from= date('Y-m-d',strtotime("-6 months",($toStr)));;
        return $from;
    }
}
