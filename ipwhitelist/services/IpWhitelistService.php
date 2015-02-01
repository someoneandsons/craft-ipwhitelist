<?php
namespace Craft;

class IpWhitelistService extends BaseApplicationComponent
{
	protected $plugin;

    public function __construct()
    {    
        $this->plugin = craft()->plugins->getPlugin('ipWhitelist');
    }	

    public function _checkCode($code = '') {
	    $settings = $this->plugin->getSettings();
		$codeSetting = $settings->code;
		
		if($code === $codeSetting && !$this->_checkIP(craft()->request->getIpAddress())) {
			$settings->setAttribute('whitelist', $settings->whitelist."\n".craft()->request->getIpAddress());
			
			craft()->plugins->savePluginSettings($this->plugin, $settings);
		}
    }
    
    public function _checkIP($ip = '127.0.0.1') {		
		$settings = $this->plugin->getSettings();
		$whitelistSetting = $settings->whitelist;
		
		$whitelist = array_map('trim', explode("\n", $whitelistSetting));
		
		return in_array($ip, $whitelist);
    }

    public function _isExcluded() {	
		$settings = $this->plugin->getSettings();
		$requestPath = craft()->request->getPath();
		
		$exclude = array_map('trim', explode('\n', $settings->exclude));

		$excluded = false;
		if(is_array($exclude))
		{
			foreach($exclude as $path)
			{
				if(strrpos($requestPath, $path, -strlen($requestPath)) !== FALSE)
				{
					$excluded = true;
					break;
				}

			}
		}
		return $excluded;
    }
    
}
