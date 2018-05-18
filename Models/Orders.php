<?php
namespace nalletje\imuis_api\Models;
use nalletje\imuis_api\Handlers\Builder;
/**
 * Pre-Defined statements for usage iMuis cloudswitch
 *
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Orders extends Builder {
  /** @var string */
  protected $partnerKey;

  /** @var string */
  protected $environment;

  /** @var string */
  protected $url;

  /**
    * Constructor - Runs when loaded
    *
    * @param string $name
    */
  public function __construct($partnerKey, $environment, $url = 'https://cloudswitch.imuisonline.com/ws1_api.aspx'){
    parent::__construct($partnerKey, $environment, $url);
  }

  public function setOrderSorts(){
    $this->setTable("ORDSRT");
    $this->setSelect('*');
    $this->setWhereFields("HROW");
    $this->setWhereOperators(">");
    $this->setWhereValues('0');
    $this->setOrderBy("HROW");
  }

}
