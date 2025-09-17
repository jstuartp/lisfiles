<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

class FileManagerController extends AbstractController
{
    private string $baseDirectory;

    public function __construct(string $filesDirectory)
    {
        // Resuelve la ruta real para mayor seguridad
        $this->baseDirectory = realpath($filesDirectory);
    }

    #[Route('/{path}', name: 'app_file_manager_list', requirements: ['path' => '.+'], defaults: ['path' => ''])]
    public function listFiles(string $path): Response
    {
        // Construye y valida la ruta actual para evitar ataques de directory traversal
        $currentPath = realpath($this->baseDirectory . '/' . $path);
        if ($currentPath === false || strpos($currentPath, $this->baseDirectory) !== 0) {
            throw $this->createNotFoundException('Directorio no válido.');
        }

        // Obtener directorios
        $finderDirs = new Finder();
        $finderDirs->directories()->in($currentPath)->depth('== 0')->sortByname();

        // Obtener archivos
        $finderFiles = new Finder();
        $finderFiles->files()->in($currentPath)->depth('== 0')->sortByname();

        $files = [];
        foreach ($finderFiles as $file) {
            $files[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'modified' => $file->getMTime(),
            ];
        }

        // Generar la ruta relativa para la navegación
        $relativePath = trim(substr($currentPath, strlen($this->baseDirectory)), DIRECTORY_SEPARATOR);

        return $this->render('file_manager/index.html.twig', [
            'directories' => $finderDirs,
            'files' => $files,
            'current_path' => $relativePath,
            'parent_path' => $relativePath ? dirname($relativePath) : null,
        ]);
    }

    #[Route('/download', name: 'app_file_manager_download', methods: ['POST'])]
    public function downloadFiles(Request $request): Response
    {
        $selectedFiles = $request->request->all('files');
        $currentPathFromForm = $request->request->get('current_path', '');

        // Valida la ruta de la que provienen los archivos
        $downloadDirectory = realpath($this->baseDirectory . '/' . $currentPathFromForm);
        if ($downloadDirectory === false || strpos($downloadDirectory, $this->baseDirectory) !== 0) {
            throw $this->createAccessDeniedException('Acceso no permitido.');
        }

        if (empty($selectedFiles)) {
            $this->addFlash('warning', 'No se seleccionó ningún archivo.');
            return $this->redirectToRoute('app_file_manager_list', ['path' => $currentPathFromForm]);
        }

        if (count($selectedFiles) === 1) {
            $filePath = $downloadDirectory . '/' . $selectedFiles[0];
            if (file_exists($filePath)) {
                return $this->file($filePath);
            }
        }

        $zip = new ZipArchive();
        $zipFileName = 'descarga-' . date('Y-m-d-His') . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipFileName;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            $this->addFlash('error', 'No se pudo crear el archivo ZIP.');
            return $this->redirectToRoute('app_file_manager_list', ['path' => $currentPathFromForm]);
        }

        foreach ($selectedFiles as $fileName) {
            $filePath = $downloadDirectory . '/' . $fileName;
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $fileName);
            }
        }

        $zip->close();

        $response = new StreamedResponse(function() use ($zipPath) {
            readfile($zipPath);
            unlink($zipPath);
        });

        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $zipFileName . '"');

        return $response;
    }
}