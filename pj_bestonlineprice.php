<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pj_BestOnlinePrice extends Module
{
    public function __construct()
    {
        $this->name = 'pj_bestonlineprice';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Piotr Jaworski';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        // smutne to jest że nadal translacje działają tylko "po staremu", a po przeniesieniu plików tpl do szablonu, tylko "po nowemu"
        $this->displayName = $this->trans('PJ Best Online Price', array(), 'Modules.Pjbestonlineprice.Admin');
        $this->description = $this->trans('Aditional flag for products', array(), 'Modules.Pjbestonlineprice.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.6.0', 'max' => _PS_VERSION_);
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('actionProductFlagsModifier');
    }

    public function hookActionProductFlagsModifier(array $params)
    {
        // $params['product']['price_amount'], // cena w bieżącej walucie po uwzględnieniu promocji
        // $params['product']['regular_price_amount'], // cena bez promocji w bieżącej walucie
        // używam efektywnej ceny dla aktualnego kontekstu klienta bo nie zostało to sprecyzowane,
        // odnośnie waluty, ignoruję ją tylko dlatego że wydaje mi się że zadanie miało być "proste"
        // jestem świadom że domyślna waluta sklepu może być inna niż PLN i cena efektywwna zależna od wielu ustawień
        // (grupy klientów (netto/brutto), zniżek dla grup klientów, reguł cenowych katalogu, cen specjalnych...)
        if($params['product']['price_amount'] < 100){
            $params['flags']['bestonlineprice'] = ['type' => 'new', 'label' => $this->trans('Best online price', array(), 'Modules.Pjbestonlineprice.Shop')];
        }
    }
}
