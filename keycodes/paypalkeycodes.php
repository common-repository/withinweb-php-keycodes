<?php
//-------------------------------------------------------
/**
*  PAYPAL specific
*  
*  Handles extracting the key codes from the tblitems 'keycodes' field.
*  There are three functions here which could be modified to fit in with other system of license generation.
*  The keycodes are located in the database for each item, one key code per line.
*  First, the number of key codes is calculated and checked against the lower limit.  If the number of key codes
*  has reached the lower limit, then an email is sent to the administrator.  The top key code is extracted and
*  the remainder are saved to the database.
*  key code functions :
*  - getnoofkeycodes returns the number of key codes that are in the database for this item.
*  - getlowerlimit returns the number defined in the database for the lower limit of key codes.
*  - getnextkeycodes returns the next key code, (or key codes), and removes it, (or them), from the database.
*
*	Each function has the array $paypal which passes all received paypal data.  These variables are listed in 
*	create_local_variables() function in com_functions.php file.  So the last name of the customer would be :
*			$paypal["last_name"]
*	It would be possible to use this information to calculate a keycode based on the user details.
*/

class withinweb_wwkc_keycodes_paypal {

	private static $_noofkeycodes;	
	public static function ret_noofkeycodes() {		
		return $_noofkeycodes;
	}
	
	//---------------------------------------
	//init
	public static function init() {
		add_filter('withinweb_wwkc_keycodes_getnoofkeycodes', array(__CLASS__, 'withinweb_wwkc_keycodes_getnoofkeycodes'), 10, 2);
		add_filter('withinweb_wwkc_keycodes_getlowerlimit', array(__CLASS__, 'withinweb_wwkc_keycodes_getlowerlimit'), 10, 2);
		add_filter('withinweb_wwkc_keycodes_getnextkeycodes', array(__CLASS__, 'withinweb_wwkc_keycodes_getnextkeycodes'), 10, 4);
	}
	//---------------------------------------
	/**
	* Given the record id of the product item, return the number of key codes that 
	* are in the database field
	* @param $recid is the record id of the item in tblitems
	* @param $paypal is all the paypal codes but is not used here and is provided just in case something needs to use it
	* @return integer
	*/
	public static function withinweb_wwkc_keycodes_getnoofkeycodes($paypal, $recid) {

		//Return a value from a hook.
		//https://wordpress.stackexchange.com/questions/144801/return-a-custom-value-in-a-function-added-to-an-action-hook		
		//https://codex.wordpress.org/Function_Reference/do_action_ref_array
		
		//Get table items details given the item_number
		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";

		//$row = $wpdb->get_row( "SELECT keycodes FROM " . $table_name . " WHERE recid = " . $recid );

		$row = $wpdb->get_row( $wpdb->prepare( 
			"
				SELECT keycodes FROM $table_name WHERE recid = %d
			", 
				array(			
				$recid			
			) 
		) );	


		if ( $row )
		{		
			if ($row->keycodes == "")
			{
				return 0;
			}
			else
			{
				return count(preg_split("/\n/", $row->keycodes));
			}
		}
		else
		{
			return 0;
		}
	}
	//---------------------------------------
	/**
	* Given the product recid return the lower limit number of pins to identify when an email is sent out
	* @param int $recid
	* @param $paypal which is not used but might be used at sometime
	* @return integer
	*/
	public static function withinweb_wwkc_keycodes_getlowerlimit($paypal, $recid) {

		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
		//$row = $wpdb->get_row( "SELECT lowerlimit FROM " . $table_name . " WHERE recid = " . $recid );

		$row = $wpdb->get_row( $wpdb->prepare(
			"
				SELECT lowerlimit FROM $table_name WHERE recid = %d
			", 
				array(
				$recid
			) 
		) );

		if ( $row ) 
		{
			return	$row->lowerlimit;	
		}
		else
		{
			return 0;
		}

	}
	//---------------------------------------
	/**
	* Given the product $recid return the series of pin numbers as a string separated by crlf (\r\n)
	* Delete that number from the table and save it back the remainder into the table
	* @param int $recid is the record id of the product item
	* @param int $notoretreive is the number of pins to retrieve
	* @param boolean $deletetopitem if true then don't delete the top item, else delete the top item
	* @return string
	*/
	public static function withinweb_wwkc_keycodes_getnextkeycodes($paypal, $recid, $notoretrieve, $deletetopitem) {

		//Retrieve all pin numbers.
		//Split into an array
		//Retrieve the top $notoretrieve
		//Save back the remainder
		//Return

		//Retrieve all pin numbers for this recid item
		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
		//$row = $wpdb->get_row( "SELECT keycodes FROM " . $table_name . " WHERE recid = " . $recid );

		$row = $wpdb->get_row( $wpdb->prepare(
			"
				SELECT keycodes FROM $table_name WHERE recid = %d
			", 
				array(
				$recid
			) 
		) );

		//echo($row[0]["keycodes"]);
		//exit();	

		//return $wpdb->last_query;
		//exit();	

		//Split into an array
		$keysarray = preg_split("/\n/", $row->keycodes );
		$keyscount = count($keysarray);

		//print_r($keysarray);
		//exit();

		//echo($keyscount);
		//echo($notoretrieve);
		//echo( $table[0]["keycodes"] );
		//exit();

		//Retrieve the top $notoretrieve
		if ($keyscount >= $notoretrieve) {

			//gets the key to return
			$keys = "";
			for ($i = 0; $i < $notoretrieve; $i++) {
				if ($i == 0)
				{
					$keys .= trim($keysarray[$i]);
				}
				else
				{
					$keys .= "\r\n" . trim($keysarray[$i]);		//add return if it is more than one
				}
			}

			$keysleft = "";
			for ($i = $notoretrieve; $i < $keyscount; $i++) {

				if ($i == ($keyscount - 1)) {
					$keysleft .= trim($keysarray[$i]);
				} else {
					$keysleft .= trim($keysarray[$i]) . "\r\n";
				}

			}
	
			$keycodesleft = trim($keysleft);

			//update the keycodes after removing the first one
			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
			$wpdb->query( $wpdb->prepare( 
				"
					UPDATE $table_name SET
					keycodes = %s
					WHERE 
					recid = %d
				", 
					array(
					$keycodesleft,
					$recid			
				) 
			) );
	

		} else 	{	//in this case, return all the list and delete everything from the table 
					//This is where the number to retrieve is more than the number in the table
			
			$keysarray = preg_split("/\n/", $row->keycodes );
			//gets the key to return
			$keys = "";	
			$i = 0;
			foreach( $keysarray as $value ) {
				if ($i == 0)
				{
					$keys .= trim($value);
				}
				else
				{
					$keys .= "\r\n" . trim($value);		//add return if it is more than one
				}
				$i++;
			}
				
			//Set keycodes to blank as all have been removed
			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
			$wpdb->query( $wpdb->prepare( 
				"
					UPDATE $table_name SET
					keycodes = ''
					WHERE 
					recid = %d
				", 
					array(
					$recid			
				) 
			) );		

		}

		return $keys;

	}
	//---------------------------------------	

}	
//-------------------------------------------------------

withinweb_wwkc_keycodes_paypal::init();
?>