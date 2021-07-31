<?php

/**
 *
 * NOTICE OF LICENSE
 *
 *
 *  @author    Fotax <web@fotax.pl>
 *  @copyright 2021 Fotax
 *  @license   Fotax
 */

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;

require_once _PS_MODULE_DIR_ . 'cenniki/classes/CennikiModel.php';

class CennikiController extends AdminController
{
    private $cache;

    public function __construct()
    {
        $this->module = 'cenniki';
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->lang = false;
        $this->table = 'ppbs_product_field_option'; 
        $this->identifier = 'id_option';
        $this->className = 'CennikiModel';
        $this->required_fields = array('id_product', 'name', 'category');
        
        $this->explicitSelect = false;
        $this->allow_export = false;
        $this->delete = false;
        $this->filter_categories = true;
        $this->orderBy = 'name'; 
        $this->_orderWay = 'ASC';
        $this->shopLinkType = 'shop';
        $this->addRowAction('edit');
        $this->list_no_link = true;
        parent::__construct();

        $this->_select .= 'a.text AS wymiar, a.value AS cena, c.name AS name, d.name AS category, s.id_product AS id_product';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'ppbs_product_field` p ON p.`id_ppbs_product_field` = a.`id_ppbs_product_field`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` c ON c.`id_product` = p.`id_product`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'product` s ON s.`id_product` = c.`id_product`';
        $this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` d ON d.`id_category` = s.`id_category_default`';
        $this->_where .= 'AND s.id_product <> "" ';
        $this->_where .= 'AND p.input_type <> "opis" ';
        $this->_where .= 'AND CAST(a.text as UNSIGNED) <> CAST(a.value as UNSIGNED) ';
        $this->_where .= 'AND CAST(a.text as UNSIGNED) BETWEEN 9 AND 999';


        $this->fields_list = [
            'id_product'       => [
                'title' => $this->l('ID'),
                'type'  => 'text',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'filter' => false,
                'search' => false
            ],
            'name'     => [
                'title' => $this->l('Nazwa'),
                'type'  => 'text',
                'filter_key' => 'c!name',
                'filter_type' => 'text',
            ],
            'category'     => [
                'title' => $this->l('Category'),
                'type'  => 'text',
                'filter_key' => 'd!name',
                'filter_type' => 'text',

            ],
            'wymiar'     => [
                'title' => $this->l('Wymiar'),
                'type'  => 'decimal',
                'filter' => false,
                'orderby' => false,
                'search' => false
            ],
            'cena'     => [
                'title' => $this->l('Cena'),
                'type'  => 'decimal',
                'filter' => false,
                'orderby' => false,
                'search' => false
            ]

        ];
    }

    public function initToolbar(){

    }

    public function renderForm()
    {

        if (!($obj = $this->loadObject(true))) {
            return;
        };
        $this->fields_form = array(
            'legend' => [
                'title' => CennikiModel::get_name_prod($obj->id_ppbs_product_field)[0]['category_name'] .' / '. CennikiModel::get_name_prod($obj->id_ppbs_product_field)[0]['product_name'],
            ],
            'input'  => array(
           
                array(
                    'col'     => 4,
                    'type'     => 'text',
                    'label'    => 'Wymiar:',
                    'name'     => 'text',
                    'required' => true,
                ),
                array(
                    'col'     => 4,
                    'type'     => 'text',
                    'label'    => $this->l('Cena:'),
                    'name'     => 'value',
                    'required' => true,
                    'lang' => false
                ),

            ),
            'submit' => [
                'title' => $this->l('Save'),
            ],
        );

        return parent::renderForm($this->fields_form);
    }

    public function initContent()
    {
        parent::initContent();
    }
}
