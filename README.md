Module upload fichier

Creation du files controlleur et l'entité files en utilisant Entity Manager / doctrine 
protected entitymanager dans le abstract controlleur 

php bin/doctrine orm:schema-tool:create

creation template twig pour la liste des fichiers 


creation des routages
// Nouveau un fichier
$router->addRoute(
    'files_new',
    '/files/new',
    'new'
);

// Ajouter un fichier
// Ajouter un fichier POST FORM
$router->addRoute(
    'files_add',
    '/files/add',
    'add'
);

// Action de modification/supression d'un fichier
$router->addRoute(
    'files_action',
    '/files/action',
    'POST',
    FileController::class,
    'action'
);

);

// Ajouter un fichier
$router->addRoute(
    'files_add',
    '/files/add',
    'POST',
    FileController::class,
    'add'
);

Ensuite , jai utilisé slugify pour cree un slug pour chaque fichier 

  private function buildFile(string $fileType, string $targetFilePath, Files $file)
  
  


