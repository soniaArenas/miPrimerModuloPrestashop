<?php 
Class Product extends ProductCore
{
	public $afiliateLink;

	self::$definition['fields']['afiliateLink'] = array(
    'type' => self::TYPE_STRING,
    'validate' => 'isLinkRewrite',
    'lang' => false,
    'shop' => false,
     'size' => 128
);

	public static function updateTemporada($id_product, $afiliateLink)
{
    if (Validate::isLinkRewrite($afiliateLink) && Validate::isUnsignedInt($id_product)) {
        Db::getInstance()->update(
            'product',
            array('afiliateLink' => $afiliateLink),
            'id_product = '.(int)$id_product
        );
        return true;
    } else {
        return false;
    }
}




}


 ?>