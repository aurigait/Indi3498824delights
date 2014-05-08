<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */ 

class GoMage_Social_Model_Type {
	
	const FACEBOOK = 1;	
	const LINKEDIN = 2; 
	const GOOGLE = 3;
    const TWITTER= 4;
    const TUMBLR= 5;
    const REDDIT= 6;


    public static function getTypeService($type){
        switch ($type) {
            case 2:
                return 'linkedin';
                break;
            case 4:
                return 'twitter';
                break;
            case 5:
                return 'tumblr';
                break;
            case 6:
               return 'reddit';
            break;
        }
    }
}