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
    if( !parent::install() || 
        !$this->registerHook('displayHome') ||
        !$this->registerHook('displayHeader') ||
        !$this->instalarModulo()
        )//en que hook insertamos el modulo 
        return false;
    return true;
}

public function uninstall()
{
    if( !parent::uninstall() ||
     !$this->unregisterHook('displayHome') ||
     !$this->unregisterHook('displayHeader') ||
     !$this->desinstalarModulo()
 )
        return false;
    return true;
}
public function instalarModulo(){
      $langs = Language::getLanguages();//obtenemos array con los lenguajes disponibles
      foreach ($langs as $lang) {
       $id = $lang['id_lang'];
       $texto = $this->l('Texto Predeterminado');
       Configuration::updateValue('SONY_MODULO_TEXTO_HOME_'.$id, $texto);
   }
   return true;
}

public function desinstalarModulo(){
      $langs = Language::getLanguages();//obtenemos array con los lenguajes disponibles
      foreach ($langs as $lang) {
          $id = $lang['id_lang'];
          Configuration::deleteByName('SONY_MODULO_TEXTO_HOME_'.$id);
      }
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
        $langs = Language::getLanguages();//obtenemos array con los lenguajes disponibles
        foreach ($langs as $lang) {
           $id = $lang['id_lang'];
           $helper->fields_value['texto'][$id] = Configuration::get('SONY_MODULO_TEXTO_HOME_'.$id);
       }


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
                        'lang' => true,
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

              $langs = Language::getLanguages();//obtenemos array con los lenguajes disponibles
              foreach ($langs as $lang) {
               $id = $lang['id_lang'];
               $texto = Tools::getValue('texto_'.$id);
               Configuration::updateValue('SONY_MODULO_TEXTO_HOME_'.$id, $texto);
           }

           return $this->displayConfirmation($this->l('Updated Successfully'));
       }
   }

   public function hookDisplayHome($params)
   {
        /*print_r($this->context->language);*///par ver contexto
        $id = $this->context->language->id;
        $texto = Configuration::get('SONY_MODULO_TEXTO_HOME_'.$id);
        $this->context->smarty->assign(array(
            'texto_variable' => $texto,
        ));

        return $this->context->smarty->fetch($this->local_path.'views/templates/hook/home.tpl');
    }
    
    public function hookDisplayHeader($params)
    {
        if($this->context->controller->php_self && $this->context->controller->php_self == 'index')
        {
         $this->context->controller->addCSS($this->local_path.'views/css/style.css');
          $this->context->controller->addJS($this->local_path.'views/js/script.js');
        }
       
    }
    
}


?>