<?php

namespace App\Controller;

use App\Entity\Files;
use App\Routing\Attribute\Route;

class FileController extends AbstractController
{

    // ici vous pouvez définir les formats de fichiers à importer
    public const ALLOW_FILE_TYPE = ['jpg','png','jpeg','gif','pdf', 'doc', 'docx', 'csv'];
    public const UPLOAD_DIRECTORY = __DIR__ ."/../../public/upload/";

  #[Route(path: "/files", name: "files_list")]
  public function list()
  {
    $files = $this->entityManager->getRepository(Files::class)->findAll();

    echo $this->twig->render(
        'files/list.html.twig',
        [
            'files' => $files,
            'title' => 'Liste des fichiers'
        ]
    );
  }

    #[Route(path: "/files/new", name: "files_new")]
    public function new()
    {
        echo $this->twig->render(
            'files/form.html.twig',
            [
                'title' => 'Ajouter un fichier',
                'action' => 'add'
            ]
        );
    }

    #[Route(path: "/files/add", name: "files_add")]
    public function add()
    {
        // cette fonction permet d'ajouter un fichier via POST
        if (!empty($_FILES)) {
            $fileName = basename($_FILES["file"]["name"]);
            $fileType = pathinfo(self::UPLOAD_DIRECTORY . $fileName,PATHINFO_EXTENSION);

            $file = new Files();
            $action = $_POST['action'];

            $dataFile = $this->buildFile($fileName, $fileType, $file, $action);

            if (isset($dataFile['file']) && $dataFile['file'] instanceof Files) {
                echo $dataFile['statusMsg'];
                $this->redirect('files');
            }
        }

        echo $this->twig->render(
            'files/form.html.twig',
            [
                'title' => 'Ajouter un fichier'
            ]
        );
    }

    #[Route(path: "/files/action", name: "files_action")]
    public function action()
    {
        if (!empty($_POST)) {
            if (isset($_POST['update'], $_POST['id'])) {
                echo $this->twig->render(
                    'files/form.html.twig',
                    [
                        'file' => $this->entityManager->getRepository(Files::class)->find($_POST['id']),
                        'title' => 'Modification ',
                        'action' => 'update'
                    ]
                );
            }elseif (isset($_POST['delete'], $_POST['id'])) {
                $file = $this->entityManager->getRepository(Files::class)->find($_POST['id']);
                unlink(self::UPLOAD_DIRECTORY. $file->getPath());
                $this->entityManager->remove($file);
                $this->entityManager->flush();
                // ici tu peux ajouter un message si tu veux
                $this->redirect('files');
            }
        }
    }

    private function buildFile(string $fileName, string $fileType, Files $file, $action) :array
    {
        // cette fonction permet de créer / modifier un fichier selon l'action
        if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {

            if(in_array($fileType, self::ALLOW_FILE_TYPE)){

                $temp = explode(".", $_FILES["file"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                $targetFilePath = self::UPLOAD_DIRECTORY . $newfilename;

                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    if ($action === 'add') {
                        $this->fileAction($file, $newfilename, $fileType);
                        // ici on crée le fichier
                        $this->entityManager->persist($file);
                    } elseif($action === 'update') {
                        // ici on chope le fichier et on le modifie
                        $file = $this->entityManager->getRepository(Files::class)->find($_POST['id']);
                        unlink(self::UPLOAD_DIRECTORY. $file->getPath());
                        $this->fileAction($file, $newfilename, $fileType);
                    }

                   $this->entityManager->flush();

                    if($file->getId() > 0){
                        $statusMsg = "The file ".$_POST['name']. " has been $action successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    }
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }

        return ['statusMsg' => $statusMsg, 'file' => $file];
    }

    /**
     * @param mixed $file
     * @param string $targetFilePath
     * @param string $fileType
     * @return void
     */
    private function fileAction(mixed $file, string $targetFilePath, string $fileType): void
    {
        $file->setName($_POST['name']);
        $file->setDescription($_POST['description'] ?? null);
        $file->setPath($targetFilePath);
        $file->setType($fileType);
        $file->setUploadedAt(new \DateTime());
        $file->setUser($this->getConnectedUser());
        $file->setSlug($this->slugify($_POST['name']));
    }

}
