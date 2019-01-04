<?php

// On charge l'autoloader de class de composer
require 'vendor/autoload.php';

// On stock dans un tableau la liste des différents cores

$cores = [
    \Framework\Cores\Home\HomeCore::class
];

// Instance du container de php-di
$builder = new \DI\ContainerBuilder();
// On ajoute une définition
$builder->addDefinitions(__DIR__ . '/Components/Config/Config.php');
// On parcours les définitions des différents cores
foreach ($cores as $core) {
    // Si une définition est présente
    if ($core::DEFINITIONS) {
        // On l'ajoute au builder
        $builder->addDefinitions($core::DEFINITIONS);
    }
}
// On construit le builder
$container = $builder->build();

// On initialise l'application en lui passant le container et le tableau des cores
$application = new \Components\Application\Application($container, $cores);
// On retourne l'application sous forme d'objet en lui passant en paramètre la reqûete
$response = $application->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
// Ensuite, on envoi l'objet nsous forme de réponse afin de pouvoir l'afficher à l'écran
\Http\Response\send($response);