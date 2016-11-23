# PHPMobileConfig
A PHP library to dynamically generate configuration profiles for Apple devices.
The profiles work on iPod, iPhone, iPad, Mac.

## Installation
1. Get latest release from [releases](https://github.com/alve89/PHPMobileConfig/releases/latest) and extract it to your server
2. Configure /config/config.general.php
3. Add your routines to retrieve user details to /config/config.getUser.php
4. Add all accounts you want to be included within the profile in /config/config.addTheseAccounts.php
5. You're ready to go for an unsigned profile

To get signed profiles you need three more steps:

6. Add SSL certificate **cert.pem** to /data/certs/
7. Add certificate chain **ca-bundle.pem** to /data/certs/
8. Add private key **key.pem** to /data/certs/
9. Now you're ready for signed profiles

## Notes
**Please note that all certificate files already exist but they are invalid (no valid content)!**

Either you fill them with valid content or you rename / remove them. Otherwise you won't get a readable profile!

**Not all options are available yet**

Only the following are included in the current version:
1. Wifi
2. CalDAV
3. CardDAV
4. Mail
