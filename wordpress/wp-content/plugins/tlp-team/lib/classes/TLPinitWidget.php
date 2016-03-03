<?php

if(!class_exists('TLPinitWidget')):

	/**
	* 
	*/
	class TLPinitWidget
	{
		
		function __construct()
		{
			add_action( 'widgets_init', array($this, 'initWidget'));
		}


		function initWidget(){
			global $TLPteam;

			$TLPteam->loadWidget( $TLPteam->widgetsPath );
		}
	}


endif;