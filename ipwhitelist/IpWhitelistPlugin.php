<?php
namespace Craft;

class IpWhitelistPlugin extends BasePlugin
{
    protected function defineSettings()
    {
        return array(
            'activated' => array(AttributeType::Bool, 'default' => false),
            'whitelist' => array(AttributeType::String, 'default' => '127.0.0.1'),
            'code' => array(AttributeType::String, 'default' => sha1(md5(rand()))),
            'exclude' => array(AttributeType::String, 'default' => ''),
            'template' => array(AttributeType::String, 'default' => '')
        );
    }
    
    public function getName()
    {
         return Craft::t('IP Whitelist');
    }

    public function getVersion()
    {
        return '1.1';
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

		    if(!craft()->userSession->isAdmin() && !craft()->request->isCpRequest() && !craft()->ipWhitelist->_isExcluded() && !craft()->ipWhitelist->_checkIP(craft()->request->getIpAddress())) {
			    if(!empty($this->getSettings()->template)) {
					echo craft()->templates->render($this->getSettings()->template);
			    } else {
					craft()->path->setTemplatesPath(craft()->path->getPluginsPath().'ipwhitelist/templates');
					echo craft()->templates->render('blocked');
			    }
			    
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
