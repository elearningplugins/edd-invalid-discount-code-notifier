<?php 
/*
Plugin Name: Easy Digital Downloads - Expired Discount Code Notifier
Description: Notifies the store owner via email when a customer attempts to make a purchase with an expired discount code.
Plugin URI: https://wisdomplugin.com
Author: Brian Batt
Author URI: https://wisdomplugin.com
Version: 1.1

*/

if(!class_exists('EDD_Expired_Discount_Code_Notifier'))
{
	class EDD_Expired_Discount_Code_Notifier
	{


		public function __construct(){

			add_filter( 'edd_ajax_discount_response', array( $this, 'check_edd_ajax_discount_response'), 100, 1 );
		}

		public function check_edd_ajax_discount_response( $response ){
			if( is_array($response) )
			{
				if( isset($response['msg'] ) )
				{
					if( $response['msg'] !="valid" )
					{
						$this->send_notification( $response );
					}
				}
			}
			return $response;
		}
		public function send_notification( $response )
		{
			$code = isset( $response['code'] )? $response['code'] : "";
			$name_of_store = get_bloginfo("name");
			
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$to = get_bloginfo('admin_email');
			$subject = "Invalid discount code used";
			
			$body = "Someone tried to use this discount code:<br><br>";
			$body.=$code."<br><br>";
			$body.="Thanks,<br><br>";
			$body.=$name_of_store;	
			
			wp_mail( $to, $subject, $body, $headers);
		}


	}//end class
	new EDD_Expired_Discount_Code_Notifier();
}//end if
