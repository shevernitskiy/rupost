<?php

/**
 * Класс заполнения бланков Почты России
 *
 * В качестве образцов используются официальные бланки, взятые с сайта с Почты РФ.
 * Заполнение происходит с помощью библиотеки TCPDF.
 * Возврат pdf позможен в разных видах, подробнее в описании конкретных функций генерации.
 * @see     CN23
 * @see     CP71
 * @see     F107
 * @uses    tcpdf lib
 * @uses    fpdi lib
 * @author  Kirill Shevernitskiy
 * @version 1.0
 * @example printRP.example.php
 */

if (!class_exists('FPDI')) {
    include_once("tcpdf/tcpdf.php");
    include_once("tcpdf/fpdi.php");
}

class shippingRupostPrintRP
{
    protected $fromName,$fromCompany,$fromAddress,$fromIndex,$fromCity,$fromCountry,
                $toName,$toCompany,$toAddress,$toIndex,$toCity,$toCountry,$toContact,
                //Переменные для формы CN23
                $isGift,$isDocument,$isCommercialGood,$isReturn,$isEtc,
                $comment,
                $isLicense,$license,$isCertificate,$certificate,$isInvoice,$invoice,
                $goods = array(),
                // Переменные для формы CP71
                $numPakages, $isPriority, $isEconom, $isReturnToSender, $declareValue;

    /**
     * Конструктор
     *
     * @throws Exception
     */
    public function __construct()
    {
        if (!file_exists(dirname(__FILE__).'/tcpdf/')) {
            throw new Exception(__METHOD__.' -> Отсутсвует библиотека tcpdf');
        }
        if (!file_exists(dirname(__FILE__).'/tcpdf/tcpdf.php')) {
            throw new Exception(__METHOD__.' -> Отсутсвует php класс библиотеки tcpdf');
        }
        if (!file_exists(dirname(__FILE__).'/tcpdf/fpdi.php')) {
            throw new Exception(__METHOD__.' -> Отсутсвует php класс библиотеки fpdi');
        }
    }

    /**
     * Универсальный сеттер
     *
     * @param string    $property
     * @param mixed     $value
     * @return mixed    $this
     */
    public function set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    /**
     * Универсальный геттер
     *
     * @param string    $property
     * @return mixed    $this
     * @throws Exception
     */
    public function get($property)
    {
        if (isset($this->$property)) {
            return $this->$property;
        } else {
            throw new Exception(__METHOD__.' -> Переменная не существует');
        }
    }

    /**
     * Сеттеры для переменных
     */
    public function setFromName($value)
    {
        $this->fromName = $value;
        return $this;
    }
    public function setFromCompany($value)
    {
        $this->fromCompany = $value;
        return $this;
    }
    public function setFromAddress($value)
    {
        $this->fromAddress = $value;
        return $this;
    }
    public function setFromIndex($value)
    {
        $this->fromIndex = $value;
        return $this;
    }
    public function setFromCity($value)
    {
        $this->fromCity = $value;
        return $this;
    }
    public function setFromCountry($value)
    {
        $this->fromCountry = $value;
        return $this;
    }
    public function setToName($value)
    {
        $this->toName = $value;
        return $this;
    }
    public function setToCompany($value)
    {
        $this->toCompany = $value;
        return $this;
    }
    public function setToAddress($value)
    {
        $this->toAddress = $value;
        return $this;
    }
    public function setToIndex($value)
    {
        $this->toIndex = $value;
        return $this;
    }
    public function setToCity($value)
    {
        $this->toCity = $value;
        return $this;
    }
    public function setToCountry($value)
    {
        $this->toCountry = $value;
        return $this;
    }
    public function setToContact($value)
    {
        $this->toContact = $value;
        return $this;
    }
    public function setIsGift($value)
    {
        $this->isGift = $value;
        return $this;
    }
    public function setIsDocument($value)
    {
        $this->isDocument = $value;
        return $this;
    }
    public function setIsReturn($value)
    {
        $this->isReturn = $value;
        return $this;
    }
    public function setIsCommercialGood($value)
    {
        $this->isCommercialGood = $value;
        return $this;
    }
    public function setIsEtc($value)
    {
        $this->isEtc = $value;
        return $this;
    }
    public function setComment($value)
    {
        $this->comment = $value;
        return $this;
    }
    public function setIsLicense($value)
    {
        $this->isLicense = $value;
        return $this;
    }
    public function setLicense($value)
    {
        $this->license = $value;
        return $this;
    }
    public function setIsCertificate($value)
    {
        $this->isCertificate = $value;
        return $this;
    }
    public function setCertificate($value)
    {
        $this->certificate = $value;
        return $this;
    }
    public function setIsInvoice($value)
    {
        $this->isInvoice = $value;
        return $this;
    }
    public function setInvoice($value)
    {
        $this->invoice = $value;
        return $this;
    }
    public function setGoods($array)
    {
        if (!is_array($array)) {
            throw new Exception(__METHOD__.' -> Переменная должна быть массивом');
        }
        foreach ($array as $key => $good) {
            if (!$good['name'] || !$good['quantity'] || !$good['weight'] || !$good['cost']) {
                throw new Exception(__METHOD__.' -> Неверная структура переданного массива, товар должен содержать поля name,quantity,weight,cost');
            }
        }
        $this->goods = $array;
        return $this;
    }
    public function setNumPakages($value)
    {
        $this->numPakages = $value;
        return $this;
    }
    public function setIsPriority($value)
    {
        $this->isPriority = $value;
        return $this;
    }
    public function setIsEconom($value)
    {
        $this->isEconom = $value;
        return $this;
    }
    public function setIsReturnToSender($value)
    {
        $this->isReturnToSender = $value;
        return $this;
    }
    public function setDeclareValue($value)
    {
        $this->declareValue = $value;
        return $this;
    }

    
    /**
     * Склоняем словоформу
     *
     * @author runcore
     * @param mixed $n
     * @param mixed $f1
     * @param mixed $f2
     * @param mixed $f5
     * @return mixed
     */
    protected function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) {
            return $f5;
        }
        $n = $n % 10;
        if ($n>1 && $n<5) {
            return $f2;
        }
        if ($n==1) {
            return $f1;
        }
        return $f5;
    }
    
    /**
    * Возвращает сумму прописью

    * @author   runcore
    * @uses     morph(...)
    */
    
    protected function num2str($num, $kopeykiFlag = true)
    {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',    1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        );
        //
        list($rub,$kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                if (!intval($v)) {
                    continue;
                }
                $uk = sizeof($unit)-$uk-1; // unit key
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2>1) {
                    $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
                } else {
                    $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                }
                // units without rub & kop
                if ($uk>1) {
                    $out[]= $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            } //foreach
        } else {
            $out[] = $nul;
        }
        $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        if ($kopeykiFlag) {
            $out[] = $kop.' '.$this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        }
        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }
    
    /**
     * Подсчет общей стоимости товаров из массива $goods
     *
     * @return mixed
     */
    protected function totalCost()
    {
        $cost = 0;
        foreach ($this->goods as $good) {
            $cost += $good['cost'];
        }
        return $cost;
    }

    /**
     * Подсчет общего количества товаров из массива $goods
     *
     * @return mixed
     */
    protected function totalQty()
    {
        $cost = 0;
        foreach ($this->goods as $good) {
            $cost += $good['quantity'];
        }
        return $cost;
    }    

    /**
     * Функция заполнения формы CN23
     *
     * @param string    $outputType - тип pdf, возвращаемый функцией
     *   I - вывод в браузер
     *   D - принудительная загрузка файла с диалогом "Сохранить"
     *   F - локальное (на сервере) сохранение файла
     *   S - вовзрат в качестве строки
     * @param string    $filename - название файла для сохранения
     * @return mixed    pdf
     * @uses            tcpdf lib
     * @uses            fpdi lib
     * @throws          Exception
     */

    public function CN23($outputType = 'I', $filename = 'temp.pdf')
    {
        if (!file_exists(dirname(__FILE__).'/template/template_CN23.pdf')) {
            throw new Exception(__METHOD__.' -> Отсутсвует шаблон /template/template_CN23.pdf');
        }
        if (count($this->goods) <= 0) {
            throw new Exception(__METHOD__.' -> Для генерации формы CN23 необходим массив с товарами');
        }
        $goodsChunk = array();
        $goodsChunk = array_chunk($this->goods, 4); // Делим массив товаров на части по 4 штуки, для каждой части формируем отдельную страницу формы
        $pages = count($goodsChunk);
        $pdf = new pdfer();
        foreach ($goodsChunk as $gkey => $goods) {
            $pageCount = $pdf->setSourceFile(dirname(__FILE__).'/template/template_CN23.pdf');    //  Загрузка бланка CN23
            $tplIdx = $pdf->importPage(1, '/MediaBox');
            $pdf->addPage();
            $pdf->useTemplate($tplIdx);
            $pdf->SetFont('freesans', '', 8);
            $pdf->SetPage($gkey+1);
            
            // Блок От кого и Кому
            
            if ($this->fromName) {
                $pdf->textCell(33, 20.5, 56, 5.5, $this->fromName);
            }
            if ($this->fromCompany) {
                $pdf->textCell(40, 25.8, 49, 5, $this->fromCompany);
            }
            if ($this->fromAddress) {
                $pdf->textCell(32, 30.55, 78, 4.7, $this->fromAddress);
            }
            if ($this->fromIndex) {
                $pdf->textCell(40, 35.2, 18, 4.7, $this->fromIndex);
            }
            if ($this->fromCity) {
                $pdf->textCell(65, 35.2, 45, 4.7, $this->fromCity);
            }
            if ($this->fromCountry) {
                $pdf->textCell(33, 40.1, 77, 4.7, $this->fromCountry);
            }
            if ($this->toName) {
                $pdf->textCell(33, 45, 56, 5.4, $this->toName);
            }
            if ($this->toCompany) {
                $pdf->textCell(40, 50, 70, 4.7, $this->toCompany);
            }
            if ($this->toAddress) {
                $pdf->textCell(32, 54.7, 78, 4.7, $this->toAddress);
            }
            if ($this->toIndex) {
                $pdf->textCell(40, 59.3, 18, 4.7, $this->toIndex);
            }
            if ($this->toCity) {
                $pdf->textCell(65, 59.3, 45, 4.7, $this->toCity);
            }
            if ($this->toCountry) {
                $pdf->textCell(33, 64.3, 77, 5.4, $this->toCountry);
            }
            
            // Блок рисования крестиков
            
            $pdf->SetFont('freesans', 'B', 10);   // Шрифт для Крестиков Х
            if ($this->isGift) {
                $pdf->textCell(23, 107.6, 4, 4, "X");
            }
            if ($this->isDocument) {
                $pdf->textCell(23, 111, 4, 4, "X");
            }
            if ($this->isCommercialGood) {
                $pdf->textCell(57.9, 103.4, 4, 4, "X");
            }
            if ($this->isReturn) {
                $pdf->textCell(57.9, 107.1, 4, 4, "X");
            }
            if ($this->isEtc) {
                $pdf->textCell(57.9, 110.85, 4, 4, "X");
            }
            if ($this->isLicense) {
                $pdf->textCell(23, 133.8, 4, 4, "X");
            }
            if ($this->isCertificate) {
                $pdf->textCell(57.9, 133.8, 4, 4, "X");
            }
            if ($this->isInvoice) {
                $pdf->textCell(92.5, 133.8, 4, 4, "X");
            }
            
            // Блок заполнения Описания товаров
            
            $pdf->SetFont('freesans', '', 7);
            if (isset($goods) && count($goods) > 0) {
                foreach ($goods as $key => $good) {
                    $y = 77.6+$key*4.4;
                    $pdf->textCell(23.2, $y, 49, 4.4, $good['name']);
                    $pdf->textCell(72.1, $y, 20.8, 4.4, $good['quantity']);
                    $pdf->textCell(92.9, $y, 20.8, 4.4, ($good['weight']/1000));
                    $pdf->textCell(113.8, $y, 20.8, 4.4, number_format($good['cost'], 2, '.', ''));
                }
            }
            if ($gkey == ($pages - 1)) {
                $pdf->textCell(113.8, 99, 20.8, 4.4, number_format($this->totalCost(), 2, '.', ''));
            }
            
            // Блок остальной информации
            
            $pdf->SetFont('freesans', '', 8);
            if ($this->toContact) {
                $pdf->textCell(153.5, 63.6, 38.5, 6.61, $this->toContact);
            }
            $pdf->SetFont('freesans', '', 6);
            if ($this->comment) {
                $pdf->textCell(92.5, 109.5, 42, 6.5, $this->comment);
            }
            if ($this->license) {
                $pdf->textCell(41, 140, 17, 6.5, $this->license);
            }
            if ($this->certificate) {
                $pdf->textCell(77, 140, 16, 6.5, $this->certificate);
            }
            if ($this->invoice) {
                $pdf->textCell(107, 140, 27.5, 6.5, $this->invoice);
            }
            $pdf->textCell(165, 146.2, 27.5, 4, "Page ".($gkey+1)." of ".$pages, 0, 'R');
        }
        return $pdf->Output($filename, $outputType);
    }

    /**
     * Функция заполнения формы CP71
     *
     * @param string    $outputType - тип pdf, возвращаемый функцией
     *   I - вывод в браузер
     *   D - принудительная загрузка файла с диалогом "Сохранить"
     *   F - локальное (на сервере) сохранение файла
     *   S - вовзрат в качестве строки
     * @param string    $filename - название файла для сохранения
     * @return mixed    pdf
     * @uses            tcpdf lib
     * @uses            fpdi lib
     * @throws          Exception
     */

    public function CP71($outputType = 'I', $filename = 'temp.pdf')
    {
        if (!file_exists(dirname(__FILE__).'/template/template_CP71.pdf')) {
            throw new Exception(__METHOD__.' -> Отсутсвует шаблон /template/template_CP71.pdf');
        }
        $pdf = new pdfer();
        $pageCount = $pdf->setSourceFile(dirname(__FILE__).'/template/template_CP71.pdf');    //  Загрузка бланка CP71
        $tplIdx = $pdf->importPage(1, '/MediaBox');
        $pdf->addPage();
        $pdf->useTemplate($tplIdx);
        $pdf->SetFont('freesans', '', 8);
        $pdf->SetPage(1);
            
        // Блок От кого и Кому
        
        if ($this->fromName) {
            $pdf->textCell(29, 20.5, 53, 5.5, $this->fromName);
        }
        if ($this->fromCompany) {
            $pdf->textCell(36, 25.8, 46, 5, $this->fromCompany);
        }
        if ($this->fromAddress) {
            $pdf->textCell(28, 30.55, 75, 4.7, $this->fromAddress);
        }
        if ($this->fromIndex) {
            $pdf->textCell(36, 35.2, 15, 4.7, $this->fromIndex);
        }
        if ($this->fromCity) {
            $pdf->textCell(61, 35.2, 42, 4.7, $this->fromCity);
        }
        if ($this->fromCountry) {
            $pdf->textCell(29, 40.1, 74, 4.7, $this->fromCountry);
        }
        if ($this->toName) {
            $pdf->textCell(29, 45, 53, 5.4, $this->toName);
        }
        if ($this->toCompany) {
            $pdf->textCell(36, 50, 67, 4.7, $this->toCompany);
        }
        if ($this->toAddress) {
            $pdf->textCell(28, 54.7, 75, 4.7, $this->toAddress);
        }
        if ($this->toIndex) {
            $pdf->textCell(36, 59, 15, 4.7, $this->toIndex);
        }
        if ($this->toCity) {
            $pdf->textCell(61, 59, 42, 4.7, $this->toCity);
        }
        if ($this->toCountry) {
            $pdf->textCell(29, 64.1, 74, 5.4, $this->toCountry);
        }

        //  Блок рисования крестиков

        $pdf->SetFont('freesans', 'B', 8);   // Шрифт для Крестиков Х
        if ($this->isPriority) {
            $pdf->textCell(19.05, 94.6, 4, 4, "X");
        }
        if ($this->isEconom) {
            $pdf->textCell(39.6, 94.6, 4, 4, "X");
        }
        $pdf->SetFont('freesans', 'B', 10);
        if ($this->isReturnToSender) {
            $pdf->textCell(156.5, 123.9, 4, 4, "X");
        }
       
        //Блок остальной информации

        if ($this->numPakages) {
            $pdf->textCell(162, 101.8, 7, 4, $this->numPakages);
        }
        $pdf->SetFont('freesans', 'B', 6);
        if ($this->declareValue) {
            if (is_numeric($this->declareValue)) {
                $dv = number_format($this->declareValue, 2, '.', '');
            } else {
                $dv = number_format($this->totalCost(), 2, '.', '');
            }
            $pdf->textCell(165.5, 35.7, 20, 3, $dv);
            $pdf->textCell(103.1, 35.7, 62.5, 3, $this->num2str($dv, false));
        }
        return $pdf->Output($filename, $outputType);
    }

    /**
     * Функция заполнения формы F107 (Опись вложения)
     *
     * @param string    $outputType - тип pdf, возвращаемый функцией
     *   I - вывод в браузер
     *   D - принудительная загрузка файла с диалогом "Сохранить"
     *   F - локальное (на сервере) сохранение файла
     *   S - вовзрат в качестве строки
     * @param string    $filename - название файла для сохранения
     * @return mixed    pdf
     * @uses            tcpdf lib
     * @uses            fpdi lib
     * @throws          Exception
     */

    public function F107($outputType = 'I', $filename = 'temp.pdf')
    {
        if (!file_exists(dirname(__FILE__).'/template/template_F107.pdf')) {
            throw new Exception(__METHOD__.' -> Отсутсвует шаблон /template/template_F107.pdf');
        }
        if (count($this->goods) <= 0) {
            throw new Exception(__METHOD__.' -> Для генерации формы F107 необходим массив с товарами');
        } elseif (count($this->goods) > 14) {
            throw new Exception(__METHOD__.' -> В форме F107 может быть не более 14 товаров');
        }

        $pdf = new pdfer();
        $pageCount = $pdf->setSourceFile(dirname(__FILE__).'/template/template_F107.pdf');    //  Загрузка бланка CN23
        $tplIdx = $pdf->importPage(1, '/MediaBox');
        $pdf->addPage('L');
        $pdf->useTemplate($tplIdx);
        $pdf->SetFont('freesans', '', 10);
        $pdf->SetPage(1);
        
        // Отправитель
        
        if ($this->fromName) {
            $pdf->textCell(20, 139.6, 77, 5.5, $this->fromName);
            $pdf->textCell(167.4, 139.6, 77, 5.5, $this->fromName);
        }
        
        // Блок заполнения Описания товаров
            
        $pdf->SetFont('freesans', '', 9);
        if (isset($this->goods) && count($this->goods) > 0) {
            foreach ($this->goods as $key => $good) {
                $y = 51+$key*5.22;
                $pdf->textCell(19, $y, 6, 4.4, ($key+1));
                $pdf->textCell(28, $y, 63, 4.4, $good['name']);
                $pdf->textCell(91, $y, 14.5, 4.4, $good['quantity'],0,'R');
                $pdf->textCell(105.8, $y, 27, 4.4, number_format($good['cost'], 2, '.', ''),0,'R');

                $pdf->textCell(166.4, $y, 6, 4.4, ($key+1));
                $pdf->textCell(175.4, $y, 63, 4.4, $good['name']);
                $pdf->textCell(238.4, $y, 14.5, 4.4, $good['quantity'],0,'R');
                $pdf->textCell(253.2, $y, 27, 4.4, number_format($good['cost'], 2, '.', ''),0,'R');
                
            }
            $pdf->SetFont('freesans', 'B', 10);
            $pdf->textCell(91, 123.1, 14.5, 4.4, $this->totalQty(),0,'R');
            $pdf->textCell(105.8, 123.1, 27, 4.4, number_format($this->totalCost(), 2, '.', ''),0,'R');
    
            $pdf->textCell(238.4, 123.1, 14.5, 4.4, $this->totalQty(),0,'R');
            $pdf->textCell(253.4, 123.1, 27, 4.4, number_format($this->totalCost(), 2, '.', ''),0,'R');  
        }       
        return $pdf->Output($filename, $outputType);
    }    
}

/**
 * Вспомогательный класс для библиотеки TCPDF с добавленной оберточной функцией
 */
class pdfer extends FPDI
{
    public function textCell($x, $y, $w, $h, $text, $border = 0, $align = 'L')
    {
        $this->SetXY($x, $y, true);
        $this->Cell($w, $h, $text, $border, 0, $align, false, '', 0, false, 'C', 'C');
    }
}
