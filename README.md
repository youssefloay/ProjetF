Module upload fichier

Composer Install

**Creation File controller**

// public const ALLOW_FILE_TYPE = ['jpg','png','jpeg','gif','pdf', 'doc', 'docx', 'csv'];
 
 
 //public const UPLOAD_DIRECTORY = __DIR__ ."/../../public/upload/";

     Pour definir le type des fichiers qu'on peut upload ainsi que leur lieu de sauvgarde " dans public/upload

**Creation des routes**


#route pour afficher la liste des fichiers dans la base de donnees 

public function list()
 {
    $files = $this->entityManager->getRepository(Files::class)->findAll();

#route pour afficher le template twig avec les actions et les variables 


public function new()


#route 
public function add()

fonction permet d'ajouter un fichier via POST

enfin une fonction BuildFile 
// cette fonction permet de créer / modifier un fichier selon l'action

Creation status message : j'ai du mal a le faire bien fonctionner 


Creation d'un slug : 


        public static function slugify($text, string $divider = '-')
        {
        
     // replace non letter or digits by divider
     
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
     $text = preg_replace('~[^-\w]+~', '', $text);

     // trim
     $text = trim($text, $divider);

     // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
     $text = strtolower($text);

        if (empty($text)) {
         return 'n-a';
        }

        return $text;
        }

EntityManager pour la creation de l'entité files 

**le travail a eté effectué dans**

abstractController

FileController

Entity/Files.php

public/upload

public/index.php

templates/files



















**CONCLUSION**


Entity manager dans abstrait controller 

Filemanager herite de l'abstrait controller 

Il faut utiliser le http foundation pour optismiser le code  

utilisation Slugify pour changer le nom de fichier

pour ameliorer le code aussi on pourra mettre le type de fichier dans le fichier .env


