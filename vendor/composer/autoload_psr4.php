<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'WpStarterPlugin\\' => array($baseDir . '/src/classes', $baseDir . '/src/classes/*', $baseDir . '/src/classes/*/*'),
    'VariableAnalysis\\' => array($vendorDir . '/sirbrillig/phpcs-variable-analysis/VariableAnalysis'),
    'PHPCSStandards\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => array($vendorDir . '/dealerdirect/phpcodesniffer-composer-installer/src'),
    'Doctrine\\Instantiator\\' => array($vendorDir . '/doctrine/instantiator/src/Doctrine/Instantiator'),
    'DeepCopy\\' => array($vendorDir . '/myclabs/deep-copy/src/DeepCopy'),
    'Composer\\Installers\\' => array($vendorDir . '/composer/installers/src/Composer/Installers'),
);
