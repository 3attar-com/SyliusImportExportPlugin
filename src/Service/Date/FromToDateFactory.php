<?php

namespace FriendsOfSylius\SyliusImportExportPlugin\Service\Date;

class FromToDateFactory extends DateHandler implements DateFactoryInterface
{

    public function getTo()
    {
       return $this->to;
    }

    public function getFrom()
    {
        $toStr = strtotime($this->getTo());
        $fromStr = strtotime(date('Y-m-d',strtotime("+6 months",(strtotime($this->from)))));
        return $toStr>$fromStr ? date('Y-m-d',strtotime("-6 months",($toStr))) :$this->from ;
    }
}
