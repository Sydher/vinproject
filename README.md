# vinproject

![master](https://github.com/Sydher/vinproject/workflows/build/badge.svg?branch=master)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Sydher_vinproject&metric=alert_status)](https://sonarcloud.io/dashboard?id=Sydher_vinproject)
[![codecov](https://codecov.io/gh/Sydher/vinproject/branch/master/graph/badge.svg)](https://codecov.io/gh/Sydher/vinproject)

[![License](https://i.creativecommons.org/l/by-nc-nd/4.0/80x15.png)](http://creativecommons.org/licenses/by-nc-nd/4.0/)

WIP

## Description
TODO

## Développement
### Pré-requis
TODO

Commande utile :
`grep -R --exclude-dir=vendor --exclude-dir=var "recherche" *`

### Configuration
TODO

#### Fichier de configuration
TODO .env.local
```
###> google/recaptcha ###
# To use Google Recaptcha, you must register a site on Recaptcha's admin panel:
# https://www.google.com/recaptcha/admin
GOOGLE_RECAPTCHA_SITE_KEY=MA_CLE
GOOGLE_RECAPTCHA_SECRET=MA_CLE_SECRETE
###< google/recaptcha ###

###> beelab/recaptcha2-bundle ###
APP_RECAPTCHA_SITE_KEY=MA_CLE
APP_RECAPTCHA_SECRET=MA_CLE_SECRETE
###< beelab/recaptcha2-bundle ###
```

...
