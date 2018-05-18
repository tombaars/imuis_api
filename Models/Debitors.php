<?php
namespace nalletje\imuis_api\Models;
use nalletje\imuis_api\Handlers\Builder;
/**
 * Pre-Defined statements for usage iMuis cloudswitch
 *
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Debitors extends Builder {
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

  public function setAll(){
    // configure
    $this->setTable("DEB");
    $this->setSelect('NR'."\t".'ZKSL'."\t".'NAAM'."\t".'POSTCD'."\t".'PLAATS');
    $this->setWhereFields("NR");
    $this->setWhereOperators(">");
    $this->setWhereValues("0");
    $this->setOrderBy("NR");
  }

  public function setFindByName($search_term){
    $this->setTable("DEB");
    $this->setSelect('NR'."\t".'ZKSL'."\t".'NAAM'."\t".'POSTCD'."\t".'PLAATS');
    $this->setWhereFields("ZKSL;NAAM");
    $this->setWhereOperators("LIKE");
    $this->setWhereValues('%'.$search_term.'%');
    $this->setOrderBy("NR");
  }

  public function setFindByZipcode($search_term){
    $this->setTable("DEB");
    $this->setSelect('NR'."\t".'ZKSL'."\t".'NAAM'."\t".'POSTCD'."\t".'PLAATS');
    $this->setWhereFields("POSTCD");
    $this->setWhereOperators("LIKE");
    $this->setWhereValues('%'.$search_term.'%');
    $this->setOrderBy("NR");
  }

  public function setFindByDebitorNR($NR){
    if(!is_numeric($NR)){
      throw new FailedLoginException('Given value should be a integer... Debitor numbers are integers.');
    }
    $this->setTable("DEB");
    $this->setSelect('NR'."\t".'ZKSL'."\t".'NAAM'."\t".'POSTCD'."\t".'PLAATS');
    $this->setWhereFields("NR");
    $this->setWhereOperators("=");
    $this->setWhereValues($NR);
    $this->setOrderBy("NR");
  }
  
}
