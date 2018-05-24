<?php
namespace nalletje\imuis_api\Models;
use \DateTime;
use nalletje\imuis_api\Connector;
/**
 * Create Order for iMuis cloudswitch
 *
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class CreateOrder extends Connector {
  /** @var string */
  protected $partnerKey;

  /** @var string */
  protected $environment;

  /** @var string */
  protected $url;

/**
  * @var integer :: ORDKOP
  */
  protected $DEBNR;

/**
  * @var datetime :: YYYY-MM-DD HH:II:SS (Y-m-d H:i:s)
  */
  protected $ORDERDATE;

/**
  * @var string :: ORDKOP
  */
  protected $ORDERSORT;

/**
  * @var string :: Reference (KENM)
  */
  protected $REFERENCE;

/**
  * @var string :: J/N (GEBRORDBEV)
  */
  protected $CONFIRMATION = "N";

/**
  * @var integer :: ORDRG
  */
  protected $ORDERARTICLES = [];

  public function __construct($partnerKey, $environment, $url = 'https://cloudswitch.imuisonline.com/ws1_api.aspx'){
    parent::__construct($partnerKey, $environment, $url);
    $this->setDateTime(date("Y-m-d H:i:s"));
  }

/**
  * Set Debitor Number
  *
  * @param integer $int
  */
  public function setDebitorNr($int){
    if(is_numeric($int)){
      $this->DEBNR = $int;
    } else {
      throw new FailedLoginException("Debitor numbers need to be integer");
    }
  }

/**
  * Get Debitor Number
  *
  * @return int
  */
  public function getDebitorNr(){
    return $this->DEBNR;
  }

/**
  * Set Order Date
  *
  * @param datetime $str
  */
  public function setDateTime($str){
    $this->ORDERDATE = date("Y-m-d H:i:s", strtotime($str));
  }

/**
  * Get Order Date
  *
  * @return datetime
  */
  public function getDateTime(){
  /*  MMM Documentation says something else then the API errors...
    $datetime = new DateTime($this->ORDERDATE);
    return $datetime->format(DATE_ATOM);*/
    return date("d/m/Y", strtotime($this->ORDERDATE));
  }

/**
  * Set Order Sort
  *
  * @param string $str
  */
  public function setOrderSort($str){
    $this->ORDERSORT = $str;
  }

/**
  * Get Order Sort
  *
  * @return string
  */
  public function getOrderSort(){
    return $this->ORDERSORT;
  }

/**
  * Set Reference (*Kenmerk)
  *
  * @param string $str
  */
  public function setReference($str){
    $this->REFERENCE = $str;
  }

/**
  * Get Reference
  *
  * @return string
  */
  public function getReference(){
    return $this->REFERENCE;
  }

/**
  * Set Confirmation
  *
  * @param boolean $str
  */
  public function setConfirmation($str){
    if(strtolower($str) === true || strtolower($str) === "J"){
      $this->CONFIRMATION = "J";
    } else {
      $this->CONFIRMATION = "N";
    }
  }

/**
  * Get Reference
  *
  * @return boolean
  */
  public function getConfirmation(){
    return $this->CONFIRMATION;
  }

/**
  * reset Order Articles (cleanup)
  */
  public function resetOrderArticles(){
    $this->ORDERARTICLES = [];
  }

/**
  * Add a order article
  * Required: Article Number & Quantity
  */
  public function addOrderArticles($ART, $AANT, $OMSCHR = false, $OPMINT = false, $OPMEXT = false, $DAT = false, $BLOK = false, $PERCKORT = false, $MAG = false, $DATLEV = false){
    $array = [];
    $array['_remove_key'] = true;
    $array['_use_key'] = 'ORDRG';
    $array['ART']   = $ART;
    $array['AANT']  = $AANT;
    if($OMSCHR){    $array['OMSCHR']  = $OMSCHR; }
    if($OPMINT){    $array['OPMINT']  = $OPMINT; }
    if($OPMEXT){    $array['OPMEXT']  = $OPMEXT; }
    if($DAT){       $array['DAT']  = $DAT; } else { $array['DAT'] = $this->getDateTime(); }
    if($BLOK){      $array['BLOK']  = $BLOK; }
    if($PERCKORT){  $array['PERCKORT']  = $PERCKORT; }
    if($MAG){       $array['MAG']  = $MAG; }
    if($DATLEV){    $array['DATLEV']  = $DATLEV; }
    $this->ORDERARTICLES[] = $array;
  }

/**
  * get Order Articles
  *
  * @return array
  */
  public function getOrderArticles(){
    return $this->ORDERARTICLES;
  }

/**
  * Set array to Convert to XML
  *
  * @return array
  */
  public function getQueryArray(){
    ## Set Order Kop
    $statements = ['ORDKOP' => [
        'DEB' => $this->getDebitorNr(),
        'DAT' => $this->getDateTime(),
        'ORDSRT' => $this->getOrderSort(),
        'KENM' => $this->getReference(),
        'GEBRORDBEV' => $this->getConfirmation()
      ],
      'ORDRG' => $this->getOrderArticles()
    ];
    ## Return array
    return $statements;
  }

/**
  * create the Order
  *
  * @return array
  */
  public function createOrder($action = 'CREATEVERKOOPORDER', $definition = 'VERKOOPORDER'){
    // Set to Array
    $statements = $this->arrayToXML($this->getQueryArray());
    // execute call and return data
    return $this->call($action, $definition, $statements);
  }

}
