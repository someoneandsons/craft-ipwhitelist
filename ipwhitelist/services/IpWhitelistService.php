<?php
namespace Craft;

class IpWhitelistService extends BaseApplicationComponent
{
    public function _checkCode($code = '') {
	    $settings = craft()->plugins->getPlugin('ipWhitelist')->getSettings();
		$codeSetting = $settings->code;
		
		if($code === $codeSetting && !$this->_checkIP(craft()->request->getIpAddress())) {
			$settings->setAttribute('whitelist', $settings->whitelist."\n".craft()->request->getIpAddress());
			
			craft()->plugins->savePluginSettings(craft()->plugins->getPlugin('ipWhitelist'), $settings);
		}
    }
    
    public function _checkIP($ip = '127.0.0.1') {		
		$settings = craft()->plugins->getPlugin('ipWhitelist')->getSettings();
		$whitelistSetting = $settings->whitelist;
		
		$whitelist = array_map('trim', explode("\n", $whitelistSetting));
		
		return in_array($ip, $whitelist);
    }
}
