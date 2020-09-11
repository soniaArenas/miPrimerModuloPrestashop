<?php 
if(!defined('_PS_VERSION_'))
exit;

Class SonyModulo extends Module
{
	public function __construct()
	{
		$this->name = 'sonymodulo';//name siempre el mismo que el del modulo
		$this->version = '1.00.0';
        $this->author = 'Sonia';
        $this->displayName = $this->l('Sony modulo');//para identificarlo
        $this->description = $this->l('Sony Modulo Descripción');
        $this->controllers = array('default');
        $this->bootstrap = 1;
        parent::__construct();
	}

  public function install()//instala
    {
        if( !parent::install() || !$this->registerHook('displayHome'))//en que hook insertamos el modulo 
            return false;
        return true;
    }

 public function uninstall()
    {
        if( !parent::uninstall() || !$this->unregisterHook('displayHome'))
            return false;
        return true;
    }

    public function getContent()//configuración del módulo
    {
    	return $this->postProcess() . $this->getForm();
    }

    public function getForm()//generamos un form para la config del modulo
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $this->context->controller->getLanguages();
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $this->context->controller->default_form_language;
        $helper->allow_employee_form_lang = $this->context->controller->allow_employee_form_lang;
        $helper->title = $this->displayName;

        $helper->submit_action = 'sonymodulo';
        $helper->fields_value['texto'] = Configuration::get('SONY_MODULO_TEXTO_HOME');
        
        $this->form[0] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->displayName
                 ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Texto'),
                        'desc' => $this->l('Qué texto quieres que aparezca en la página de inicio'),
                        'hint' => $this->l('Pista'),
                        'name' => 'texto',//unico siempre
                        'lang' => false,
                     ),
                 ),
                'submit' => array(
                    'title' => $this->l('Save')
                 )
             )
         );
        return $helper->generateForm($this->form);
    }
   public function postProcess()
    {
        if(Tools::isSubmit('sonymodulo')) {
            $texto = Tools::getValue('texto');
            Configuration::updateValue('SONY_MODULO_TEXTO_HOME', $texto);
            return $this->displayConfirmation($this->l('Updated Successfully'));
        }
    }

    public function hookDisplayHome($params)
    {
         $texto = Configuration::get('SONY_MODULO_TEXTO_HOME');
        $this->context->smarty->assign(array(
            'texto_variable' => $texto,
        ));
       
        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/home.tpl');
    }
    

    
}


 ?>