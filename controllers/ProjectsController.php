<?php
/**
 * Projects Controller
 * Handles create/update/delete logic for projects page
 */
class ProjectsController {
    private $projects;
    private $formHandler;
    private $projectData;

    public function __construct($projects, $formHandler) {
        $this->projects = $projects;
        $this->formHandler = $formHandler;
    }

    /**
     * Load project data for editing
     * @param int|null $projectId
     * @return array|null
     */
    public function loadProjectData($projectId = null) {
        if ($projectId) {
            $this->projectData = $this->projects->getById($projectId);
            if ($this->projectData) {
                // Also load documents
                $this->projectData['documents'] = $this->projects->getDocuments($projectId);
            }
        }
        return $this->projectData;
    }

    /**
     * Get project data
     * @return array|null
     */
    public function getProjectData() {
        return $this->projectData;
    }

    /**
     * Get all projects
     * @param bool $activeOnly
     * @return array
     */
    public function getAllProjects($activeOnly = false) {
        return $this->projects->getAll($activeOnly);
    }

    /**
     * Process incoming POST request from projects form
     * @param int|null $projectId - If provided, update existing project
     * @return bool
     */
    public function processRequest($projectId = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        // Basic required field validation
        $project_name = $this->formHandler->sanitize($_POST['project_name'] ?? '');
        if (!$this->formHandler->validateRequired($project_name, 'Project name')) {
            return false;
        }

        // Get existing data if editing
        $existingData = $projectId ? $this->projects->getById($projectId) : null;

        // Gather data
        $data = [];
        $data['comp_id'] = $this->formHandler->sanitize($_POST['comp_id'] ?? '999999');
        $data['project_id'] = $this->formHandler->sanitize($_POST['project_id'] ?? '');
        $data['project_name'] = $project_name;
        $data['project_amenities'] = $_POST['project_amenities'] ?? '';
        $data['status'] = isset($_POST['status']) ? 1 : 0;

        // Handle single image uploads with existing file fallback
        $data['project_map_thumbnail'] = $existingData['project_map_thumbnail'] ?? null;
        $data['project_map_full'] = $existingData['project_map_full'] ?? null;
        $data['project_payment_plan'] = $existingData['project_payment_plan'] ?? null;
        $data['project_amenities_image'] = $existingData['project_amenities_image'] ?? null;

        if (isset($_FILES['project_map_thumbnail']) && $_FILES['project_map_thumbnail']['size'] > 0) {
            $data['project_map_thumbnail'] = $this->projects->handleFileUpload(
                $_FILES['project_map_thumbnail'], 
                'project_map_thumbnail',
                $existingData['project_map_thumbnail'] ?? null
            );
        }
        if (isset($_FILES['project_map_full']) && $_FILES['project_map_full']['size'] > 0) {
            $data['project_map_full'] = $this->projects->handleFileUpload(
                $_FILES['project_map_full'], 
                'project_map_full',
                $existingData['project_map_full'] ?? null
            );
        }
        if (isset($_FILES['project_payment_plan']) && $_FILES['project_payment_plan']['size'] > 0) {
            $data['project_payment_plan'] = $this->projects->handleFileUpload(
                $_FILES['project_payment_plan'], 
                'project_payment_plan',
                $existingData['project_payment_plan'] ?? null
            );
        }
        if (isset($_FILES['project_amenities_image']) && $_FILES['project_amenities_image']['size'] > 0) {
            $data['project_amenities_image'] = $this->projects->handleFileUpload(
                $_FILES['project_amenities_image'], 
                'project_amenities_image',
                $existingData['project_amenities_image'] ?? null
            );
        }

        // Save or Update project
        if ($projectId) {
            // Update existing project
            if (!$this->projects->update($projectId, $data)) {
                $this->formHandler->addError('Error updating project.');
                return false;
            }
            $savedProjectId = $projectId;
            
            // Handle document updates for existing project
            $this->handleDocumentUpdates($projectId);
            
            $this->formHandler->addSuccess('Project updated successfully!');
        } else {
            // Create new project
            $savedProjectId = $this->projects->create($data);
            if (!$savedProjectId) {
                $this->formHandler->addError('Error creating project.');
                return false;
            }
            
            // Handle new documents
            $this->handleNewDocuments($savedProjectId);
            
            $this->formHandler->addSuccess('Project created successfully!');
        }

        return true;
    }

    /**
     * Handle new documents for a project
     * @param int $projectId
     */
    private function handleNewDocuments($projectId) {
        $docIds = $_POST['doc_id'] ?? [];
        $docNames = $_POST['document_name'] ?? [];
        $thumbs = $_FILES['document_thumbnail'] ?? null;
        $files = $_FILES['document_file'] ?? null;

        $count = max(count($docIds), count($docNames));
        
        for ($i = 0; $i < $count; $i++) {
            $docId = $this->formHandler->sanitize($docIds[$i] ?? '');
            $docName = $this->formHandler->sanitize($docNames[$i] ?? '');

            // Handle thumbnail upload
            $thumbFilename = null;
            if ($thumbs && isset($thumbs['name'][$i]) && !empty($thumbs['name'][$i])) {
                $fileArr = [
                    'name' => $thumbs['name'][$i],
                    'type' => $thumbs['type'][$i],
                    'tmp_name' => $thumbs['tmp_name'][$i],
                    'error' => $thumbs['error'][$i],
                    'size' => $thumbs['size'][$i]
                ];
                $thumbFilename = $this->projects->handleFileUpload($fileArr, 'document_thumbnail');
            }

            // Handle document file upload
            $docFileFilename = null;
            if ($files && isset($files['name'][$i]) && !empty($files['name'][$i])) {
                $fileArr = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                $docFileFilename = $this->projects->handleFileUpload($fileArr, 'document_file');
            }

            // Only save if there's meaningful data
            if (!empty($docId) || !empty($docName) || !empty($thumbFilename) || !empty($docFileFilename)) {
                $docData = [
                    'doc_id' => $docId,
                    'document_thumbnail' => $thumbFilename,
                    'document_name' => $docName,
                    'document_file' => $docFileFilename,
                    'display_order' => $i
                ];
                $this->projects->addDocument($projectId, $docData);
            }
        }
    }

    /**
     * Handle document updates for existing project
     * @param int $projectId
     */
    private function handleDocumentUpdates($projectId) {
        // Get existing documents
        $existingDocs = $this->projects->getDocuments($projectId);
        $existingDocIds = array_column($existingDocs, 'id');
        
        // Get form data
        $docDbIds = $_POST['doc_db_id'] ?? []; // Database IDs for existing documents
        $docIds = $_POST['doc_id'] ?? [];
        $docNames = $_POST['document_name'] ?? [];
        $thumbs = $_FILES['document_thumbnail'] ?? null;
        $files = $_FILES['document_file'] ?? null;
        
        $processedDbIds = [];
        
        $count = max(count($docIds), count($docNames), count($docDbIds));
        
        for ($i = 0; $i < $count; $i++) {
            $dbId = isset($docDbIds[$i]) ? (int)$docDbIds[$i] : 0;
            $docId = $this->formHandler->sanitize($docIds[$i] ?? '');
            $docName = $this->formHandler->sanitize($docNames[$i] ?? '');
            
            // Get existing files if updating
            $existingThumb = null;
            $existingFile = null;
            if ($dbId > 0) {
                foreach ($existingDocs as $existDoc) {
                    if ($existDoc['id'] == $dbId) {
                        $existingThumb = $existDoc['document_thumbnail'];
                        $existingFile = $existDoc['document_file'];
                        break;
                    }
                }
            }
            
            // Handle thumbnail upload
            $thumbFilename = $existingThumb;
            if ($thumbs && isset($thumbs['name'][$i]) && !empty($thumbs['name'][$i])) {
                $fileArr = [
                    'name' => $thumbs['name'][$i],
                    'type' => $thumbs['type'][$i],
                    'tmp_name' => $thumbs['tmp_name'][$i],
                    'error' => $thumbs['error'][$i],
                    'size' => $thumbs['size'][$i]
                ];
                $thumbFilename = $this->projects->handleFileUpload($fileArr, 'document_thumbnail', $existingThumb);
            }

            // Handle document file upload
            $docFileFilename = $existingFile;
            if ($files && isset($files['name'][$i]) && !empty($files['name'][$i])) {
                $fileArr = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                $docFileFilename = $this->projects->handleFileUpload($fileArr, 'document_file', $existingFile);
            }
            
            // Only process if there's meaningful data
            if (!empty($docId) || !empty($docName) || !empty($thumbFilename) || !empty($docFileFilename)) {
                $docData = [
                    'doc_id' => $docId,
                    'document_thumbnail' => $thumbFilename,
                    'document_name' => $docName,
                    'document_file' => $docFileFilename,
                    'display_order' => $i
                ];
                
                if ($dbId > 0 && in_array($dbId, $existingDocIds)) {
                    // Update existing document
                    $this->projects->updateDocument($dbId, $docData);
                    $processedDbIds[] = $dbId;
                } else {
                    // Add new document
                    $this->projects->addDocument($projectId, $docData);
                }
            }
        }
        
        // Delete documents that were removed from the form
        foreach ($existingDocIds as $existingId) {
            if (!in_array($existingId, $processedDbIds)) {
                $this->projects->deleteDocument($existingId);
            }
        }
    }

    /**
     * Delete project
     * @param int $projectId
     * @return bool
     */
    public function deleteProject($projectId) {
        if ($this->projects->delete($projectId)) {
            $this->formHandler->addSuccess("Project deleted successfully!");
            return true;
        }
        
        $this->formHandler->addError("Error deleting project. Please try again.");
        return false;
    }

    /**
     * Delete project document
     * @param int $docId
     * @return bool
     */
    public function deleteDocument($docId) {
        if ($this->projects->deleteDocument($docId)) {
            return true;
        }
        return false;
    }

    /**
     * Get success message
     * @return string|null
     */
    public function getSuccessMessage() {
        return $this->formHandler->getFirstSuccess();
    }

    /**
     * Get error message
     * @return string|null
     */
    public function getErrorMessage() {
        return $this->formHandler->getFirstError();
    }
}
?>
