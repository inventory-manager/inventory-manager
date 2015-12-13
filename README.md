# inventory-manager [![Build Status](https://travis-ci.org/inventory-manager/inventory-manager.svg?branch=master)](https://travis-ci.org/inventory-manager/inventory-manager)[![StyleCI](https://styleci.io/repos/46257313/shield)](https://styleci.io/repos/46257313)[![Coverage Status](https://coveralls.io/repos/inventory-manager/inventory-manager/badge.svg?branch=master&service=github)](https://coveralls.io/github/inventory-manager/inventory-manager?branch=master)

## This project is documented and developed in German

## Einrichtung des Projekts

### Voraussetzungen
- Composer
- PHP >= 5.6
- Git
- PDO-Treiber kompatible Datenbank (empfohlen: MySQL)

### Beschaffung des Quellcodes
```
$ git clone git@github.com:inventory-manager/inventory-manager.git
```

### Installation
```
$ cd inventory-manager
$ composer install
(Konfigurationsdaten für DB-Verbindung angeben)
```

### Einrichtung der Datenbank
```
($ app/console doctrine:database:drop --force)
$ app/console doctrine:database:create
$ app/console doctrine:schema:create
$ app/console doctrine:fixtures:load
```

### Starten des Entwicklungswebservers
```
$ app/console server:run
(Projekt ist unter der in der Konsole vermerkten Adresse erreichbar)
```

### Einrichtung der Entwicklungsumgebung (PhpStorm)
- PhpStorm starten
- `Create new project from existing files` auswählen
- `Source files are in a local directory, no Web server is yet configured` auswählen
- Projektordner als `Project-Root` markieren
- Wenn ein Popup zur automatischen Erkennung von PSR0 Namespaces kommt, dies akzeptieren
