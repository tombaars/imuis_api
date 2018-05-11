<?php
namespace nalletje\imuis_api\Models;
use nalletje\imuis_api\Connector;
/**
 * Pre-Defined statements for usage iMuis cloudswitch
 *
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Orders extends Connector {
  /** @var string */
  protected $partnerKey;

  /** @var string */
  protected $environment;

  /** @var string */
  protected $url;

  /** @var string */
  protected $MaxResults = 10;

  /** @var string */
  protected $SelectPage = 1;

  /**
    * MAKE SURE EVERY SELECT FIELD HAS \t after it!
    * @var string
    */
  protected $Select = '*';

  /** @var string */
  protected $OrderBy = "HROW";

  /**
    * Constructor - Runs when loaded
    *
    * @param string $name
    */
  public function __construct($partnerKey, $environment, $url = 'https://cloudswitch.imuisonline.com/ws1_api.aspx'){
    parent::__construct($partnerKey, $environment, $url);
  }

  /**
    * Set Max Result
    *
    * @param integer $str
    */
  public function setMaxResults($str){
    if(is_numeric($str)){
      $this->MaxResults = $str;
    }
  }

  /**
    * Get Max Result
    *
    * @return integer
    */
  public function getMaxResults(){
    return $this->MaxResults;
  }

  /**
    * Set Select Page
    *
    * @param integer $str
    */
  public function setSelectPage($str){
    if(is_numeric($str)){
      $this->SelectPage = $str;
    }
  }
  /**
    * Get Select Page
    *
    * @return integer
    */
  public function getSelectPage(){
    return $this->SelectPage;
  }

  /**
    * Set Select Statement
    *
    * @param string $str
    */
  public function setSelect($str){
    $this->Select = $str;
  }

  /**
    * Get Select Statement
    *
    * @return string $str
    */
  public function getSelect(){
    return $this->Select;
  }

  /**
    * Set Order By Statement
    *
    * @param string $str
    */
  public function setOrderBy($str){
    $this->OrderBy = $str;
  }

  /**
    * Get Order By Statement
    *
    * @return string $str
    */
  public function getOrderBy(){
    return $this->OrderBy;
  }

  public function getOrderSorts(){
    $statements = [
      'Table1'  =>  [
        'TABLE'         => "ORDSRT",
        'SELECTFIELDS'  => $this->getSelect(),
        'WHEREFIELDS'   => "HROW",
        'WHEREOPERATORS'=> ">",
        'WHEREVALUES'   => "0",
        'ORDERBY'       => $this->getOrderBy(),
        'MAXRESULT'     => $this->getMaxResults(),
        'PAGESIZE'      => "10000",
        'SELECTPAGE'    => $this->getSelectPage()
      ]
    ];
    $statements = $this->arrayToXML($statements);
    return $this->call('GETSTAMTABELRECORDS', 'SELECTIE', $statements);
  }

}
