<?php
namespace nalletje\imuis_api\Handlers;
use nalletje\imuis_api\Connector;
/**
 * Imuis (Query) Builder
 *
 * http://cswdoc.imuisonline.com/?page_id=266
 * @author Quirinus de Munnik <quirinus@q-online.eu>
 */
class Builder extends Connector
{
  /** @var string */
  protected $partnerKey;

  /** @var string */
  protected $environment;

  /** @var string */
  protected $url;

  /**
    * @var string
    */
    protected $TABLE;

  /**
    * EXAMPLE INPUT: 'NR'."\t".'OMSCHR'
    * @var string
    */
    protected $SELECT;

  /**
    * EXAMPLE INPUT: 'NR'."\t".'OMSCHR'
    * NOTICE :: There appears to be bug, in case you mix fields with varchar and integer values.
    * @var string
    */
    protected $WHERE_FIELDS;

  /**
    * EXAMPLE INPUT: =, >, >=, <, <=, LIKE
    * @var string
    */
    protected $WHERE_OPERATORS;

  /**
    * @var string
    */
    protected $WHERE_VALUES;

  /**
    * EXAMPLE INPUT: 'NR'."\t".'OMSCHR'
    * @var string
    */
    protected $ORDER_BY;

  /**
    * @var integer
    */
    protected $LIMIT = '10';

  /**
    * @var integer
    */
    protected $PAGESIZE = '10000';

  /**
    * @var integer
    */
    protected $SELECTPAGE = '1';

  /**
    * Constructor - Runs when loaded
    *
    * @param string $name
    */
    public function __construct($partnerKey, $environment, $url = 'https://cloudswitch.imuisonline.com/ws1_api.aspx'){
      parent::__construct($partnerKey, $environment, $url);
    }

  /**
    * Set Table
    *
    * @param string $str
    */
    public function setTable($str){
      $this->TABLE = $str;
    }

  /**
    * Get Table
    *
    * @return string
    */
    public function getTable(){
      return $this->TABLE;
    }

  /**
    * Set SELECT
    *
    * @param string $str
    */
    public function setSelect($str){
      $this->SELECT = $str;
    }

  /**
    * Get SELECT
    *
    * @return string
    */
    public function getSelect(){
      return $this->SELECT;
    }

  /**
    * Set WHERE FIELDS
    *
    * @param string $str
    */
    public function setWhereFields($str){
      $this->WHERE_FIELDS = $str;
    }

  /**
    * Get WHERE FIELDS
    *
    * @return string
    */
    public function getWhereFields(){
      return $this->WHERE_FIELDS;
    }

  /**
    * Set WHERE OPERATORS
    *
    * @param string $str
    */
    public function setWhereOperators($str){
      $this->WHERE_OPERATORS = $str;
    }

  /**
    * Get WHERE OPERATORS
    *
    * @return string
    */
    public function getWhereOperators(){
      return $this->WHERE_OPERATORS;
    }

  /**
    * Set WHERE VALUES
    *
    * @param string $str
    */
    public function setWhereValues($str){
      $this->WHERE_VALUES = $str;
    }

  /**
    * Get WHERE VALUES
    *
    * @return string
    */
    public function getWhereValues(){
      return $this->WHERE_VALUES;
    }

  /**
    * Set ORDER BY
    *
    * @param string $str
    */
    public function setOrderBy($str){
      $this->ORDER_BY = $str;
    }

  /**
    * Get ORDER BY
    *
    * @return string
    */
    public function getOrderBy(){
      return $this->ORDER_BY;
    }

  /**
    * Set LIMIT / MAX RESULTS
    *
    * @param integer $int
    */
    public function setLimit($int){
      $this->LIMIT = $int;
    }

  /**
    * Get LIMIT / MAX RESULTS
    *
    * @return integer
    */
    public function getLimit(){
      return $this->LIMIT;
    }

  /**
    * Set PAGESIZE
    *
    * @param integer $int
    */
    public function setPageSize($int){
      $this->PAGESIZE = $int;
    }

  /**
    * Get PAGESIZE
    *
    * @return integer
    */
    public function getPageSize(){
      return $this->PAGESIZE;
    }

  /**
    * Set SELECTPAGE
    *
    * @param integer $int
    */
    public function setSelectPage($int){
      $this->SELECTPAGE = $int;
    }

  /**
    * Get SELECTPAGE
    *
    * @return integer
    */
    public function getSelectPage(){
      return $this->SELECTPAGE;
    }

  /**
    * Return Query statements, convert to XML and send to cloudswitch
    *
    * @return array
    */
  public function getQueryArray(){
    $statements = [
      'Table1'  =>  [
        'TABLE'         => $this->getTable(),
        'SELECTFIELDS'  => $this->getSelect(),
        'WHEREFIELDS'   => $this->getWhereFields(),
        'WHEREOPERATORS'=> $this->getWhereOperators(),
        'WHEREVALUES'   => $this->getWhereValues(),
        'ORDERBY'       => $this->getOrderBy(),
        'MAXRESULT'     => $this->getLimit(),
        'PAGESIZE'      => $this->getPageSize(),
        'SELECTPAGE'    => $this->getSelectPage()
      ]
    ];
    return $statements;
  }

  /**
    * Return DATA, converted to XML and send to cloudswitch,return data
    *
    * @return array
    */
  public function getResults($action = 'GETSTAMTABELRECORDS', $definition = 'SELECTIE'){
    // Set to Array
    $statements = $this->arrayToXML($this->getQueryArray());
    // execute call and return data
    return $this->call($action, $definition, $statements);
  }
}
