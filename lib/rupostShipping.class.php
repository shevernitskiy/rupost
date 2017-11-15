<?php

class rupostShipping extends waShipping
{
    protected $cost,$weight,$api_login,$api_password;
    
    public function allowedCurrency()
    {
        return wa('shop')->getConfig()->getCurrency();
    }

    public function allowedWeightUnit()
    {
        return 'kg';
    }

    public function allowedLinearUnit()
    {
        return 'cm';
    }

    public function allowedAddress()
    {
        return json_decode(file_get_contents(dirname(__FILE__).'\allowedAddress.json'), true);
    }

    public function calculate()
    {
        if ($this->getTotalWeight() <= 0) {
            return _w('Zero weight of goods');
        }
        require_once dirname(__FILE__).'/classes/shippingRupostTariffRP.class.php';
        try {
            $t = new shippingRupostTariffRP();
            if ($this->getSettings('isavia')) {
                $t  ->setIsAvia(true);
            }
            if ($this->getSettings('addweight')) {
                if (is_numeric($this->getSettings('addweight'))) {
                    $this->weight = $this->getTotalWeight() + $this->getSettings('addweight');
                }
            } else {
                $this->weight = $this->getTotalWeight();
            }
            $t->setDeclareValue($this->getTotalPrice());
            $t  ->setWeight($this->weight)
                ->setDestination(strtoupper($this->getAddress('country')))
                ->setType('pakageDeclareValue');
            $this->cost = $t->getCost();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        if (is_numeric($this->getSettings('addcost'))) {
            if ($this->getSettings('addcost') > 0) {
                $this->cost += $this->getSettings('addcost');
            }
        }
        
        $services[0] = array(
            'name' => '',
            'rate' => $this->cost,
            'currency' => 'RUB',
        );
        if ($this->getSettings('isestdate')) {
            $services[0]['est_delivery'] = waDateTime::format('humandate', strtotime('now') + $this->getSettings('mindays')*24*60*60).' - '.waDateTime::format('humandate', strtotime('now') + $this->getSettings('maxdays')*24*60*60);
        }

        return $services;
    }

    public function getPrintForms(waOrder $order = null)
    {
        $forms = array();
        $forms['cn23'] = array(
            'name'        => 'CN23',
            'description' => '',
        );
        $forms['cp71'] = array(
            'name'        => 'CP71',
            'description' => '',
        );
        $forms['f107'] = array(
            'name'        => 'F107',
            'description' => '',
        );        
        return $forms;
    }

    public function displayPrintForm($id, waOrder $order, $params = array())
    {
        require_once dirname(__FILE__).'/classes/shippingRupostPrintRP.class.php';

        //$this->lg($params);
        $pf = new shippingRupostPrintRP();
        if ($this->getSettings('fromname')) {
            $pf->setFromName($this->getSettings('fromname'));
        }
        if ($this->getSettings('fromcompany')) {
            $pf->setFromCompany($this->getSettings('fromcompany'));
        }
        if ($this->getSettings('fromaddress')) {
            $pf->setFromAddress($this->getSettings('fromaddress'));
        }
        if ($this->getSettings('fromindex')) {
            $pf->setFromIndex($this->getSettings('fromindex'));
        }
        if ($this->getSettings('fromcity')) {
            $pf->setFromCity($this->getSettings('fromcity'));
        }
        if ($this->getSettings('fromcountry')) {
            $pf->setFromCountry($this->getSettings('fromcountry'));
        }
        if ($order->getContact()['name']) {
            $pf->setToName($order->getContact()['name']);
        }
        if ($order->shipping_address['street']) {
            $pf->setToAddress($order->shipping_address['street']);
        }
        if ($order->shipping_address['zip']) {
            $pf->setToIndex($order->shipping_address['zip']);
        }
        if ($order->shipping_address['city']) {
            $pf->setToCity($order->shipping_address['city']);
        }
        if ($order->shipping_address['country']) {
            $pf->setToCountry($this->getFullCountry($order->shipping_address['country']));
        }
        if ($order->getContact()['phone'][0]['value']) {
            $pf->setToContact($order->getContact()['phone'][0]['value']);
        }
        if ($this->getSettings('isgift')) {
            $pf->setIsGift(true);
        }
        if ($this->getSettings('isdocument')) {
            $pf->setIsDocument(true);
        }
        if ($this->getSettings('iscommercialgood')) {
            $pf->setIsCommercialGood(true);
        }
        if ($this->getSettings('isreturn')) {
            $pf->setIsReturn(true);
        }
        if ($this->getSettings('isetc')) {
            $pf->setIsEtc(true);
        }
        if ($this->getSettings('islicense')) {
            $pf->setIsLicense(true);
        }
        if ($this->getSettings('iscertificate')) {
            $pf->setIsCertificate(true);
        }
        if ($this->getSettings('isinvoice')) {
            $pf->setIsInvoice(true);
        }
        if ($this->getSettings('ispriority')) {
            $pf->setIsPriority(true);
        }
        if ($this->getSettings('iseconom')) {
            $pf->setIsEconom(true);
        }
        if ($this->getSettings('isreturntosender')) {
            $pf->setIsReturnToSender(true);
        }
        if ($this->getSettings('comment')) {
            $pf->setComment($this->getSettings('comment'));
        }
        if ($this->getSettings('license')) {
            $pf->setLicense($this->getSettings('license'));
        }
        if ($this->getSettings('certificate')) {
            $pf->setCertificate($this->getSettings('certificate'));
        }
        if ($this->getSettings('invoice')) {
            $pf->setInvoice($this->getSettings('invoice'));
        }
        if ($this->getSettings('numpakages')) {
            $pf->setNumPakages($this->getSettings('numpakages'));
        }
        $pf->setDeclareValue(true);
        $goods = $this->extractItems($order);
        if ($goods) {
            $pf->setGoods($goods);
        }
        
        switch ($id) {
            case 'cn23':
                $pf->CN23();
                break;
            case 'cp71':
                $pf->CP71();
                break;
            case 'f107':
                $pf->F107();
                break;                
            default:
                throw new waException('Invalid printform ID');
                break;
        }
    }

    public function getFullCountry($code)
    {
        $ar = json_decode(file_get_contents(dirname(__FILE__).'\classes\country.json'), true);
        $sar = array_column($ar, 'enName', 'code3');
        return $sar[strtoupper($code)];
    }

    public function extractItems(waOrder $order)
    {
        $array = array();
        foreach ($order->items as $key => $item) {
            $array[$key] = array(
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'weight' => $item['weight'],
                'cost' => round($item['price'], 0)
            );
        }
        return $array;
    }

    public function tracking($tracking_id = null)
    {
        if ($tracking_id) {
            require_once dirname(__FILE__).'/model/shippingRupost.model.php';
            $model = new waModel();
            try {
                $model->query('SELECT * FROM wa_shipping_rupost_tracking WHERE 0');
            } catch (waDbException $e) {
                $sql = 'CREATE TABLE wa_shipping_rupost_tracking (id CHAR(20), t DATETIME, data TEXT, PRIMARY KEY (id))';
                $model->exec($sql);
            }
            $status = '';
            $model = new shippingRupostModel();
            $result = $model->getByField('id', $tracking_id);
            if ($result) {
                $last = strtotime($result['t']);
                $now = strtotime('now');
                if (($now - $last)/60 > $this->getSettings('trackupdate')) {
                    if ($this->getSettings('tracklogin') && $this->getSettings('trackpass')) {
                        $this->api_login = $this->getSettings('tracklogin');
                        $this->api_password = $this->getSettings('trackpass');
                    } else {
                        return 'Задайте логин и пароль для api трекинга';
                    }
                    $status = $this->trackingRequest($tracking_id);
                    $model->replace(array(
                        'id' => $tracking_id,
                        't' => date("Y-m-d H:i:s"),
                        'data' => json_encode($status)
                    ));
                } else {
                    $status = json_decode($result['data'], true);
                }
            } else {
                if ($this->getSettings('tracklogin') && $this->getSettings('trackpass')) {
                    $this->api_login = $this->getSettings('tracklogin');
                    $this->api_password = $this->getSettings('trackpass');
                } else {
                    return 'Задайте логин и пароль для api трекинга';
                }
                $status = $this->trackingRequest($tracking_id);
                $model->insert(array(
                    'id' => $tracking_id,
                    't' => date("Y-m-d H:i:s"),
                    'data' => json_encode($status)
                ), 1);
            }
            if (count($status) > 0) {
                switch (wa()->getEnv()) {
                    case 'backend':
                        if ($this->getSettings('fulltrackback')) {
                            $html = '
                                <table class="zebra table" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th>'. _w('Date').'</th>
                                    <th>'. _w('Location').'</th>
                                    <th>'. _w('Status').'</th>
                                </tr>
                                </thead>
                                <tbody>
                            ';
                            foreach ($status as $stage) {
                                $html .= '
                                    <tr>
                                        <td>'.$stage[0].'</td>
                                        <td>'.$stage[1].', '.$stage[2].'</td>
                                        <td>'.$stage[3].'</td>
                                    </tr>
                                ';
                            }
                            $html .= '</tbody></table>';
                        } else {
                            $html = '<i>'._w('Date').':</i> <strong>'.$status[count($status)-1][0].'</strong> | <i>'._w('Location').':</i> <strong>'.$status[count($status)-1][1].'</strong>, <strong>'.$status[count($status)-1][2].'</strong> | <i>'._w('Status').':</i> <strong>'.$status[count($status)-1][3].'</strong>';
                        }
                        break;
                    default:
                        if ($this->getSettings('fulltrackfront')) {
                            $html = '
                                <br/>
                                <table class="zebra table" style="white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th>'. _w('Date').'</th>
                                    <th>'. _w('Location').'</th>
                                    <th>'. _w('Status').'</th>
                                </tr>
                                </thead>
                                <tbody>
                            ';
                            foreach ($status as $stage) {
                                $html .= '
                                    <tr>
                                        <td>'.$stage[0].'</td>
                                        <td>'.$stage[1].', '.$stage[2].'</td>
                                        <td>'.$stage[3].'</td>
                                    </tr>
                                ';
                            }
                            $html .= '</tbody></table>';
                        } else {
                            $html = '<br/><i>'._w('Date').':</i> <strong>'.$status[count($status)-1][0].'</strong> | <i>'._w('Location').':</i> <strong>'.$status[count($status)-1][1].'</strong>, <strong>'.$status[count($status)-1][2].'</strong> | <i>'._w('Status').':</i> <strong>'.$status[count($status)-1][3].'</strong>';
                        }                    
                        break;
                }
            } else {
                $html = _w('No operations registered at this moment');
            }
            return $html;
        }
    }

    public function trackingRequest($tracking_id)
    {
        $wsdlurl = 'https://tracking.russianpost.ru/rtm34?wsdl';
        $client = '';
        try {
            $client = new SoapClient($wsdlurl, array('trace' => 1, 'soap_version' => SOAP_1_2));
            $params = array (
                'OperationHistoryRequest' => array (
                    'Barcode' => $tracking_id,
                    'MessageType' => '0',
                    'Language' => 'ENG'),
                'AuthorizationHeader' => array (
                    'login' => $this->api_login,
                    'password'=> $this->api_password
                )
            );
            $result = $client->getOperationHistory(new SoapParam($params, 'OperationHistoryRequest'));
        } catch (SoapFault $ex) {
            return 'При запросе произошла ошибка: '.$ex->getMessage();
        }
        $array = array();
        foreach ($result->OperationHistoryData->historyRecord as $key => $record) {
            $array[$key] = array(
                waDateTime::format('datetime', $record->OperationParameters->OperDate),
                $record->AddressParameters->CountryOper->Code2A,
                $record->AddressParameters->OperationAddress->Description,
                $record->OperationParameters->OperType->Name
            );
        }
        return $array;
    }

    public function lg($text)
    {
        if (is_array($text)) {
            file_put_contents('log.txt', print_r($text, true));
        } else {
            file_put_contents('log.txt', $text);
        }
    }
}
