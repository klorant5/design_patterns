<?php

//Ha egy objektum létrehozása után meg kell hívni pár metódusát ahhoz hogy használható legyen akkor használjuk a buildert.
//Ne a konstruktorában hívjuk meg ezeket a metódusokat hanem a builderben mert ha módosítani kell ezeknek a meghívását
//akkor szívás alakulhat ki :D
//A másik haszna ennek a mintának az, hogy így akár typecast-tal kényszeríthetjük a buildelt obj használatát

class product
{
    protected $_type = '';
    protected $_size = '';
    protected $_color = '';

    public function setType($type)
    {
        $this->_type = $type;
    }

    public function setSize($size)
    {
        $this->_size = $size;
    }

    public function setColor($color)
    {
        $this->_color = $color;
    }
}

// our product configuration received from other functionality
$productConfigs = array('type' => 'shirt', 'size' => 'XL', 'color' => 'red');

$product = new product();
//ezt a három metódust mindig meg kell hívni hogy kész legyen a product object
$product->setType($productConfigs['type']);
$product->setSize($productConfigs['size']);
$product->setColor($productConfigs['color']);

//------------------------------------------------------------------------------
//Megoldás:


class productBuilder
{
    protected $_product = null;
    protected $_configs = array();

    public function __construct($configs)
    {
        $this->_product = new product();
        $this->_configs = $configs;
    }

    public function build()
    {
        $this->_product->setSize($this->_configs['size']);
        $this->_product->setType($this->_configs['type']);
        $this->_product->setColor($this->_configs['color']);
    }

    public function getProduct()
    {
        return $this->_product;
    }
}


$productConfigs = array('type' => 'shirt', 'size' => 'XL', 'color' => 'red');

$builder = new productBuilder($productConfigs);
$builder->build();
$product = $builder->getProduct();


//-----------
//2. felhaszn. szerintem
function deleteBuiltProduct(productBuilder $productBuilder){
//    return $productBuilder->getProduct()->delete;
}