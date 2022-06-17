<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class CombImagesCarousel extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'combImagesCarousel';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Marek Łysiak';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Combination images carousel');
        $this->description = $this->l('This module allows you to display every combination main image on the product miniature');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall? ');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('COMBIMAGESCAROUSEL_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') && 
            $this->registerHook('displayProductCombinationImages');
    }

    public function uninstall()
    {
        Configuration::deleteByName('COMBIMAGESCAROUSEL_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitCombImagesCarouselModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCombImagesCarouselModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'COMBIMAGESCAROUSEL_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'COMBIMAGESCAROUSEL_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'COMBIMAGESCAROUSEL_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'COMBIMAGESCAROUSEL_LIVE_MODE' => Configuration::get('COMBIMAGESCAROUSEL_LIVE_MODE', true),
            'COMBIMAGESCAROUSEL_ACCOUNT_EMAIL' => Configuration::get('COMBIMAGESCAROUSEL_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'COMBIMAGESCAROUSEL_ACCOUNT_PASSWORD' => Configuration::get('COMBIMAGESCAROUSEL_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
    public function hookDisplayProductCombinationImages(array $params) 
    {
        $product = new Product($params['product']['id_product']);
        $product_id = $product->id;
        $product_name = $product->name;
        $combinations = $product->getAttributesResume($this->context->language->id); 
        $combination_images = $product->getCombinationImages($this->context->language->id);
        if($combination_images != null){
            //var_dump($combination_images);
        }
        //pobieranie okladki dla danej kombinacji 
        
        $db = Db::getInstance(); 

        $combination_covers = array(); 
        if($combinations != null){
            foreach($combinations as $combination){
                //$combination_id = $combination['id_product_attribute'];
                $result = $db->executeS('SELECT pi.id_image from ps_product_attribute_image pai LEFT JOIN ps_image pi on pi.id_image=pai.id_image where pai.id_product_attribute='. $combination['id_product_attribute'] .' order by pi.position asc limit 1');
                //$link = new Image();  
                $image_id = $result[0]['id_image'];
                
                $link = new Link(); 
                $image_link = $link->getImageLink(isset($product->link_rewrite[1]) ? $product->link_rewrite[1] : $product_name[1], (int)$image_id, 'large_default');
                // $result[0]['id_image']
                //$link->getExistingImgPath().'.jpg'
                array_push($combination_covers, array(
                    "url" => $image_link, 
                    "combination_id" => $combination['id_product_attribute']),
                );
            }

        }
        
        // foreach($combination_covers as $cover) {
             
        //     var_dump($link->getExistingImgPath().".jpg");
        // }
        $this->context->smarty->assign(array(
            'combinationImages' => $combination_covers,
            "product_url" => $product->getLink(),
            'product_id' => $product_id,
        ));

       return $this->display(__FILE__, 'views/templates/hook/carousel.tpl');

        






    }
}
