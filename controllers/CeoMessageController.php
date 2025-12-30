<?php
/**
 * CEO Message Controller
 * Handles CEO message page logic
 */
class CeoMessageController {
    private $ceoMessage;
    private $formHandler;
    private $ceoData;
    
    /**
     * Constructor
     * @param CeoMessage $ceoMessage
     * @param FormHandler $formHandler
     */
    public function __construct($ceoMessage, $formHandler) {
        $this->ceoMessage = $ceoMessage;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load CEO message data
     */
    public function loadCeoData() {
        $this->ceoData = $this->ceoMessage->get();
        return $this->ceoData;
    }
    
    /**
     * Get CEO data
     * @return array
     */
    public function getCeoData() {
        return $this->ceoData;
    }
    
    /**
     * Process form submission
     */
    public function processRequest() {
        if (!isset($_POST) || empty($_POST)) {
            return;
        }
        
        // Get existing data for file handling
        $existingData = $this->ceoMessage->get();
        
        // Sanitize inputs
        $data = [
            'ceo_message_paragraph_1' => $_POST['ceo_message_paragraph_1'] ?? '', // HTML content
            'ceo_message_paragraph_2' => $_POST['ceo_message_paragraph_2'] ?? '', // HTML content
            'status' => isset($_POST['status']) ? 1 : 0
        ];
        
        // Validate required fields
        if (!$this->formHandler->validateRequired($data['ceo_message_paragraph_1'], 'CEO message paragraph 1')) {
            return false;
        }
        
        // Handle file uploads
        if (isset($_FILES['ceo_picture_1'])) {
            $data['ceo_picture_1'] = $this->ceoMessage->handleFileUpload(
                $_FILES['ceo_picture_1'],
                'ceo_picture_1',
                $existingData['ceo_picture_1'] ?? null
            );
        } else {
            $data['ceo_picture_1'] = $existingData['ceo_picture_1'] ?? null;
        }
        
        if (isset($_FILES['ceo_picture_2'])) {
            $data['ceo_picture_2'] = $this->ceoMessage->handleFileUpload(
                $_FILES['ceo_picture_2'],
                'ceo_picture_2',
                $existingData['ceo_picture_2'] ?? null
            );
        } else {
            $data['ceo_picture_2'] = $existingData['ceo_picture_2'] ?? null;
        }
        
        // Save CEO message
        if ($this->ceoMessage->save($data)) {
            $this->formHandler->addSuccess("CEO Message saved successfully!");
            return true;
        } else {
            $this->formHandler->addError("Error saving CEO Message. Please try again.");
            return false;
        }
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


