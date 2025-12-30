<?php
/**
 * About Us Controller
 * Handles about us page logic
 */
class AboutUsController {
    private $aboutUs;
    private $formHandler;
    private $aboutData;
    
    /**
     * Constructor
     * @param AboutUs $aboutUs
     * @param FormHandler $formHandler
     */
    public function __construct($aboutUs, $formHandler) {
        $this->aboutUs = $aboutUs;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load about us data
     */
    public function loadAboutData() {
        $this->aboutData = $this->aboutUs->get();
        return $this->aboutData;
    }
    
    /**
     * Get about data
     * @return array
     */
    public function getAboutData() {
        return $this->aboutData;
    }
    
    /**
     * Process form submission
     */
    public function processRequest() {
        if (!isset($_POST) || empty($_POST)) {
            return;
        }
        
        // Sanitize inputs
        $data = [
            'about_us_paragraph' => $_POST['about_us_paragraph'] ?? '', // HTML content
            'about_us_video' => $this->formHandler->sanitize($_POST['about_us_video'] ?? ''),
            'status' => isset($_POST['status']) ? 1 : 0
        ];
        
        // Validate required fields
        if (!$this->formHandler->validateRequired($data['about_us_paragraph'], 'About us paragraph')) {
            return false;
        }
        
        // Validate YouTube URL if provided
        if (!empty($data['about_us_video']) && !$this->aboutUs->validateYouTubeUrl($data['about_us_video'])) {
            $this->formHandler->addError("Invalid YouTube URL format");
            return false;
        }
        
        // Save about us
        if ($this->aboutUs->save($data)) {
            $this->formHandler->addSuccess("About Us saved successfully!");
            return true;
        } else {
            $this->formHandler->addError("Error saving About Us. Please try again.");
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


