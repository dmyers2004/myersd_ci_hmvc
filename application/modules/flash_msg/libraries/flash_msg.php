<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Flash_msg {
  public $CI;
  public $messages = array();
  public $type = array(
    'red'=>'error',
    'blue'=>'info',
    'yellow'=>'block',
    'green'=>'success',
    'error'=>'error',
    'info'=>'info',
    'block'=>'block',
    'success'=>'success',
    'notice'=>'block'
  );
  public $title = array(
    'error'=>'Error',
    'info'=>'Information',
    'block'=>'Notice',
    'success'=>'Success'
  );
  public $staytime = 5; /* setup the initial pause time seconds */
  public $disappear = 1500; /* disappear in thousands of a second */
  
  public function __construct() {
    $this->CI =& get_instance();
    
    /* test dependancies */
    if (!isset($this->CI->session)) show_error('The Session Libraries are required to be loaded before the Flash Msg Library');
    
    $this->html();
  }

  /* most basic add function */
  public function add($msg='',$type='yellow',$stay=null) {
    $type_mapped = $this->type[$type];
    $title = '<strong>'.$this->title[$type_mapped].'</strong>';
    
    $stay = ($stay != null) ? $stay : ($type_mapped == 'error') ? TRUE : FALSE;
    $staytime = ($stay == FALSE) ? ($this->disappear * $this->staytime++) : 0;
    
    $this->messages[$title.$msg.$type_mapped] = array('title'=>$title,'text'=>$msg,'type'=>$type_mapped,'stay'=>$stay,'stayTime'=>$staytime);
    
    $this->CI->session->set_flashdata('flash_messages',$this->messages);
    log_message('info', print_r($this->messages,true));
  }
  
  /* wrapper functions for add */
  public function __call($method, $param) {
    if (array_key_exists($method,$this->type)) {
      call_user_func_array(array($this,'add'), array($param[0],$method,$param[1]));
    }
  }

  public function html() {
    $msgs = array('msgs'=>$this->CI->session->flashdata('flash_messages'));
    $this->CI->data->flash_msg = $this->CI->load->view('flash_msg/flash_msg_template',$msgs,TRUE);
  }

} /* end class */