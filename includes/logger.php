<?php
//--------------------------------------
//Log file system
class withinweb_wwkc_keycodes_Logger {

	//file handles
    private $_handles;

  	//--------------------------------------	
    //Constructor for the logger.
    public function __construct() {
        $this->_handles = array();
    }
	//--------------------------------------	
    //Destructor.
    public function __destruct() {
        foreach ($this->_handles as $handle) {
            @fclose(escapeshellarg($handle));
        }
    }
	//--------------------------------------	
	//Open file for writing
    private function open($handle) {
        if (isset($this->_handles[$handle])) {
            return true;
        }

        if ($this->_handles[$handle] = @fopen($this->withinweb_wwkc_keycodes_get_log_file_path($handle), 'a')) {
            return true;
        }

        return false;
    }
	//--------------------------------------
    //Add log message
    public function add($handle, $message) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            $time = date_i18n('m-d-Y @ H:i:s -'); // Grab Time
            @fwrite($this->_handles[$handle], $time . " " . $message . "\n");
        }
    }
	//--------------------------------------
	//Clear file
    public function clear($handle) {
        if ($this->open($handle) && is_resource($this->_handles[$handle])) {
            @ftruncate($this->_handles[$handle], 0);
        }
    }
	//--------------------------------------
    //Get file path
    public function withinweb_wwkc_keycodes_get_log_file_path($handle) {
        return trailingslashit(wwkc_KEYCODES_LOG_DIR) . $handle . '-' . sanitize_file_name(wp_hash($handle)) . '.txt';
    }

}