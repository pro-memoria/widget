<?php
/* ----------------------------------------------------------------------
 * promemoriaWidget.php :
 * created by Promemoria snc (Turin - Italy) www.pro-memoria.it
 * version 1.0 - 08/03/2012
 * info@pro-memoria.it
 *This widget allow to view objects in a hierarchical structure
 *
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2010 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 	require_once(__CA_LIB_DIR__.'/ca/BaseWidget.php');
 	require_once(__CA_LIB_DIR__.'/ca/IWidget.php');
 	
 
	class promemoriaWidget extends BaseWidget implements IWidget {
		# -------------------------------------------------------
		private $opo_config;
		

		static $s_widget_settings = array();
		# -------------------------------------------------------
		public function __construct($ps_widget_path, $pa_settings) {
			$this->title = _t('Promemoria');
			$this->description = _t('Displays objects in a hierarchical structure');
			parent::__construct($ps_widget_path, $pa_settings);
			
			$this->opo_config = Configuration::load($ps_widget_path.'/conf/promemoriaWidget.conf');			
		}
		# -------------------------------------------------------
		/**
		 * Override checkStatus() to return true
		 */
		public function checkStatus() {
			$vb_available = ((bool)$this->opo_config->get('enabled'));
			
			/*if(!$this->getRequest() || !$this->getRequest()->user->canDoAction("is_administrator")){
				$vb_available = false;
			}*/
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => $vb_available
			);
		}
		# -------------------------------------------------------
		/**
		 *
		 */
		public function renderWidget($ps_widget_id, $pa_settings) {
			parent::renderWidget($ps_widget_id, $pa_settings);
			
			$this->opo_view->setVar('request', $this->getRequest());
                        $this->opo_view->setVar('field', $this->opo_config->get('order_field'));

			return $this->opo_view->render('main_html.php');
		}
		# -------------------------------------------------------
		/**
		 * Get widget user actions
		 */
		static public function getRoleActionList() {
			return array();
		}
		# -------------------------------------------------------
	}
	
	BaseWidget::$s_widget_settings['promemoriaWidget'] = array();
?>
