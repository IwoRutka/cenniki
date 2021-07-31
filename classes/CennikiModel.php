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


class CennikiModel extends ObjectModel
{

    const MODULE_ADMIN_CONTROLLER = 'Cenniki';

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'ppbs_product_field_option',
        'primary' => 'id_option',
        'fields' => array(
            'text'       =>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 25, 'required' => true),
            'value'       =>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 25, 'required' => true),
            'id_ppbs_product_field'       =>  array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 25, 'required' => true),
        )
    );

    public static function get_name_prod($id_ppbs_product_field)
    {
        $sql = new DbQuery();
        $sql->select('c.name AS product_name, d.name AS category_name');
        $sql->from('ppbs_product_field', 'pf');
        $sql->innerJoin('product', 's', 's.id_product = pf.id_product');
        $sql->innerJoin('product_lang', 'c', 'c.id_product = pf.id_product');
        $sql->innerJoin('category_lang', 'd', 'd.id_category = s.id_category_default');
        $sql->where('pf.id_ppbs_product_field = ' . (int)$id_ppbs_product_field);

        return Db::getInstance()->executeS($sql);

    }
}
