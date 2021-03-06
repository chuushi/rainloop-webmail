<?php

OCP\User::checkLoggedIn();

if (@file_exists(__DIR__.'/app/index.php'))
{
	include_once OC_App::getAppPath('rainloop').'/lib/RainLoopHelper.php';

	OC_RainLoop_Helper::regRainLoopDataFunction();

	if (isset($_GET['OwnCloudAuth']))
	{
		$sEmail = '';
		$sEncodedPassword = '';

		$sUser = OCP\User::getUser();

		if (\OC::$server->getConfig()->getAppValue('rainloop', 'rainloop-autologin', false))
		{
			$sEmail = $sUser;
			$sEncodedPassword = \OC::$server->getConfig()->getUserValue($sUser, 'rainloop', 'rainloop-autologin-password', '');
		}
		else
		{
			$sEmail = \OC::$server->getConfig()->getUserValue($sUser, 'rainloop', 'rainloop-email', '');
			$sEncodedPassword = \OC::$server->getConfig()->getUserValue($sUser, 'rainloop', 'rainloop-password', '');
		}

		$sDecodedPassword = OC_RainLoop_Helper::decodePassword($sEncodedPassword, md5($sEmail));

		$_ENV['___rainloop_owncloud_email'] = $sEmail;
		$_ENV['___rainloop_owncloud_password'] = $sDecodedPassword;
	}

	include __DIR__.'/app/index.php';
}
