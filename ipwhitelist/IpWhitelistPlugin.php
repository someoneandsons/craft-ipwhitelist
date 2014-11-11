<?php
namespace Craft;

class IpWhitelistPlugin extends BasePlugin
{
    protected function defineSettings()
    {
        return array(
            'activated' => array(AttributeType::Bool, 'default' => false),
            'whitelist' => array(AttributeType::String, 'default' => '127.0.0.1'),
            'code' => array(AttributeType::String, 'default' => sha1(md5(rand())))
        );
    }
    
    public function getName()
    {
         return Craft::t('IP Whitelist');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Mike Pierce';
    }

    public function getDeveloperUrl()
    {
        return 'http://someoneandsons.net';
    }
    
    public function init()
    {
	    if($this->getSettings()->activated) {
		    
		    craft()->ipWhitelist->_checkCode(craft()->request->getQuery('ipwhitelist'));
		    
		    if(!craft()->userSession->isAdmin() && !craft()->ipWhitelist->_checkIP(craft()->request->getIpAddress())) {
			    craft()->request->close('IP not whitelisted');
			    exit(0);
		    }
	    }
    }
    
    public function getSettingsHtml()
    {
	    return craft()->templates->render('ipwhitelist/settings', array(
			'settings' => $this->getSettings()
		));
    }
}
