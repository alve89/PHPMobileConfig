# PHPMobileConfig
A PHP library to dynamically generate configuration profiles for Apple devices.
The profiles work on iPod, iPhone, iPad, Mac.

## Installation
See [Wiki: Installation & configuration](https://github.com/alve89/PHPMobileConfig/wiki/Installation-&-configuration)

## Notes
**Please note that all certificate files already exist but they are invalid (no valid content)!**

Either you fill them with valid content or you rename / remove them. Otherwise you won't get a readable profile!

**CGI mode possibly necessary**
For the signing process the script needs permissions to execute commandline commands. Depending on your server configuration or restrictions of your hoster you might need to run this in CGI. In case of failure commenting out [line 139 in index.php](https://github.com/alve89/PHPMobileConfig/blob/master/index.php#L139) and skip the signing process. 

**Not all options are available yet**

Only the following are included in the current version:
* Wifi
* CalDAV
* CardDAV
* Mail
