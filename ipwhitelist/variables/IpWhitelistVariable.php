<?php
namespace Craft;

class IpWhitelistVariable
{
    public function code() {
        return craft()->plugins->getPlugin('ipWhitelist')->getSettings()->code;
    }
    
    public function ip() {
	    return craft()->request->getIpAddress();
    }
}
