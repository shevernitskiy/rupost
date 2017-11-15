<?php

/**
 * @var waShipping $this
 */

return array(
    'isavia' => array(
        'value'        => '1',
        'title'        => _w('Use airmail type dilvery'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => _w('If possible, do calculation for airmail'),
    ),
    'isestdate' => array(
        'value'        => '1',
        'title'        => _w('Show estimated delivery date'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => _w('Shows estimated delivery date on checkout page'),
    ),    
    'mindays' => array(
        'value'        => '14',
        'title'        => _w('Minimum days delivery'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Russian Post is sucks, only manual setting is avaible'),
    ),
    'maxdays' => array(
        'value'        => '50',
        'title'        => _w('Maximum days delivery'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Russian Post is sucks, only manual setting is avaible'),
    ),
    'addweight' => array(
        'value'        => '0',
        'title'        => _w('Additional weight (gramms)'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Add this amount to total weight of order goods'),
    ),    
    'addcost' => array(
        'value'        => '0',
        'title'        => _w('Additional price (RUB)'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Add this amount to dilvery price'),
    ),
    'tracklogin' => array(
        'value'        => '',
        'title'        => _w('Api login'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Login for russian post tracking api'),
    ),
    'trackpass' => array(
        'value'        => '',
        'title'        => _w('Api password'),
        'control_type' => waHtmlControl::PASSWORD,
        'description'  => _w('Password for russian post tracking api'),
    ),
    'trackupdate' => array(
        'value'        => '60',
        'title'        => _w('Update interval (min)'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => _w('Tracking information will update after this interval (do not set it to small, it make backend interface slower)'),
    ),
    'fulltrackback' => array(
        'value'        => '0',
        'title'        => _w('Backend full track history'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => _w('Shows all stages of track history in backend'),
    ),
    'fulltrackfront' => array(
        'value'        => '1',
        'title'        => _w('Frontend full track history'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => _w('Shows all stages of track history in frontend'),
    ),                    
    'fromname' => array(
        'value'        => 'Chuck Norris',
        'title'        => _w('Sender name'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),    
    'fromcompany' => array(
        'value'        => 'Unicorn corp.',
        'title'        => _w('Sender company'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),
    'fromaddress' => array(
        'value'        => 'Naprimer street, 54, 182',
        'title'        => _w('Sender address'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),
    'fromindex' => array(
        'value'        => '123321',
        'title'        => _w('Sender index'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),
    'fromcity' => array(
        'value'        => 'Moscow',
        'title'        => _w('Sender city'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),
    'fromcountry' => array(
        'value'        => 'Russia',
        'title'        => _w('Sender country'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23, CP71',
    ),
    'comment' => array(
        'value'        => '',
        'title'        => _w('Comment'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23',
    ),
    'license' => array(
        'value'        => '',
        'title'        => _w('License number'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23',
    ),
    'certificate' => array(
        'value'        => '',
        'title'        => _w('Certificate number'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23',
    ),
    'invoice' => array(
        'value'        => '',
        'title'        => _w('Invoice number'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CN23',
    ),
    'numpakages' => array(
        'value'        => '1',
        'title'        => _w('Amount of pakages'),
        'control_type' => waHtmlControl::INPUT,
        'description'  => 'CP71',
    ),
    'isgift' => array(
        'value'        => '1',
        'title'        => _w('Gift checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'isdocument' => array(
        'value'        => '1',
        'title'        => _w('Document checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'iscommercialgood' => array(
        'value'        => '1',
        'title'        => _w('Commercial good checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'isreturn' => array(
        'value'        => '1',
        'title'        => _w('Return checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'isetc' => array(
        'value'        => '1',
        'title'        => _w('Etc checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'islicense' => array(
        'value'        => '1',
        'title'        => _w('License checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'iscertificate' => array(
        'value'        => '1',
        'title'        => _w('Certificate checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'iscommercialgood' => array(
        'value'        => '1',
        'title'        => _w('Commercial good checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'isinvoice' => array(
        'value'        => '1',
        'title'        => _w('Invoice checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CN23',
    ),
    'ispriority' => array(
        'value'        => '1',
        'title'        => _w('Priority mail checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CP71',
    ),
    'iseconom' => array(
        'value'        => '1',
        'title'        => _w('Econom checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CP71',
    ),
    'isreturntosender' => array(
        'value'        => '1',
        'title'        => _w('Return to sender checkbox'),
        'control_type' => waHtmlControl::CHECKBOX,
        'description'  => 'CP71',
    ),         
);
