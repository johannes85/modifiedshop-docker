<?php

$webroot = realpath(__DIR__ . '/../webroot');

$default = 'u-x,go-xw,u+rwX,go+rX';
$adminDir = 'admin';

$definition = '

Ordner                                                Rechte  Funktion
/admin/backups                                        777     Datenbank Backups
/admin/images/graphs                                  777     Statistik Graphen
/admin/images/icons                                   777     Kundengruppen Icons
/admin/rss                                            777     Dateien aus Admin Info * Nicht mehr ab 1.06 vorhanden
/cache                                                777     Cache Dateien von Smarty
/export                                               777     Export-Verzeichnis
/images                                               777     Bilderverzeichnis
/images/banner                                        777     Bilder für Banner
/images/categories                                    777     Kategorie-Bilder
/images/content                                       777     Bilder aus WYSIWYG Editor
/images/icons                                         777     Kundengruppen Icons
/images/manufacturers                                 777     Hersteller-Bilder
/images/product_images/info_images                    777     Info-Bilder
/images/product_images/midi_images                    777     Midi-Bilder
/images/product_images/mini_images                    777     Mini-Bilder
/images/product_images/original_images                777     Original-Bilder
/images/product_images/popup_images                   777     Popup-Bilder
/images/product_images/thumbnail_images               777     Thumbnail-Bilder
/images/tags                                          777     Bilder für Artikeleigenschaften
/import                                               777     Import-Verzeichnis
/includes/external/magnalister                        777     Magnalister-Verzeichnis
/log                                                  777     Log-Verzeichnis
/media/content                                        777     Dateien für ContentManager
/media/content/backup                                 777     Backup-Dateien für ContentManager
/media/products                                       777     Dateien für ProduktManager
/media/products/backup                                777     Backup-Dateien für ProduktManager
/templates/tpl_modified                               777     Template-Verzeichnis
/templates/xtc5                                       777     Template-Verzeichnis
/templates_c                                          777     Smarty-Verzeichnis
   
Datei                                                 Rechte  Funktion
/sitemap.xml                                          777     Datei für die Sitemap-Informationen
/includes/configure.php                               777     Konfigurationsdatei Shop
/admin/magnalister.php                                777     Magnalister-Datei
/magnaCallback.php                                    777     Magnalister-Datei

1.3 Webbasierte Installationsroutine ausführen
(Achtung: Es darf kein .htaccess Verzeichnis-Schutz auf dem Shop-Verzeichnis gesetzt sein!)
Dazu die SHOP URL im Browser eingeben und mit /_installer ergänzen, dann die Anleitungen im Installer befolgen.

Rechtevergabe nach der Installation für die configure-Dateien zu Sicherheitszwecken!

Datei                                       Rechte  Funktion
/includes/configure.php                     444     Konfigurationsdatei Shop

1.4 Verzeichnis "_installer" der Installationsroutine löschen

';

function chmodr(string $path, string $mod, bool $recursive = false): void {
  system(sprintf(
    'chmod %s %s%s',
    $mod,
    $path,
    $recursive ? ' --recursive' : ''
  ));
}

preg_match_all('%^\s*(/.+?)\s+([0-9]{3})%m', $definition, $result, PREG_SET_ORDER);

printf("Setting webroot to %s\n", $default);
chmodr($webroot.'/*', $default, 777, true);

foreach ($result as $m) {
  $path = $webroot . $m[1];
  $path = str_replace('/admin/', '/' . $adminDir . '/', $path);

  if (file_exists($path)) {
    printf(
      "Setting %s to %s\n",
      $path,
      $m[2]
    );
    chmodr($path, $m[2], true);
  } else {
    printf(
      "Skipping (not found) %s\n",
      $path
    );
  }

}

