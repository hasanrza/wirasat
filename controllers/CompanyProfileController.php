<?php
/**
 * Company Profile Controller
 * Handles company profile page logic
 */
class CompanyProfileController {
    private $companyProfile;
    private $formHandler;
    private $profileData;
    
    /**
     * Constructor
     * @param CompanyProfile $companyProfile
     * @param FormHandler $formHandler
     */
    public function __construct($companyProfile, $formHandler) {
        $this->companyProfile = $companyProfile;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load company profile data
     * @param string $compId
     */
    public function loadProfileData($compId = '999999') {
        $this->profileData = $this->companyProfile->getByCompId($compId);
        return $this->profileData;
    }
    
    /**
     * Get profile data
     * @return array
     */
    public function getProfileData() {
        return $this->profileData;
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
            'comp_id' => $this->formHandler->sanitize($_POST['comp_id'] ?? '999999'),
            'company_name' => $this->formHandler->sanitize($_POST['company_name'] ?? ''),
            'company_address' => $_POST['company_address'] ?? '', // HTML content
            'facebook_link' => $this->formHandler->sanitize($_POST['facebook_link'] ?? ''),
            'youtube_link' => $this->formHandler->sanitize($_POST['youtube_link'] ?? ''),
            'twitter_link' => $this->formHandler->sanitize($_POST['twitter_link'] ?? ''),
            'instagram_link' => $this->formHandler->sanitize($_POST['instagram_link'] ?? ''),
            'website_url' => $this->formHandler->sanitize($_POST['website_url'] ?? ''),
            'email_address' => $this->formHandler->sanitize($_POST['email_address'] ?? ''),
            'uan' => $this->formHandler->sanitize($_POST['uan'] ?? ''),
            'mobile_1' => $this->formHandler->sanitize($_POST['mobile_1'] ?? ''),
            'mobile_2' => $this->formHandler->sanitize($_POST['mobile_2'] ?? ''),
            'ptcl_number' => $this->formHandler->sanitize($_POST['ptcl_number'] ?? ''),
            'longitude' => $this->formHandler->sanitize($_POST['longitude'] ?? ''),
            'latitude' => $this->formHandler->sanitize($_POST['latitude'] ?? ''),
            'status' => isset($_POST['status']) ? 1 : 0
        ];
        
        // Validate required fields
        if (!$this->formHandler->validateRequired($data['company_name'], 'Company name')) {
            return false;
        }
        
        if (!$this->formHandler->validateRequired($data['email_address'], 'Email address')) {
            return false;
        }
        
        if (!$this->companyProfile->validateEmail($data['email_address'])) {
            $this->formHandler->addError("Invalid email format");
            return false;
        }
        
        // Handle file uploads
        $existingData = $this->companyProfile->getByCompId($data['comp_id']);
        
        if (isset($_FILES['company_logo'])) {
            $data['company_logo'] = $this->companyProfile->handleFileUpload(
                $_FILES['company_logo'],
                'company_logo',
                $existingData['company_logo'] ?? null
            );
        } else {
            $data['company_logo'] = $existingData['company_logo'] ?? null;
        }
        
        if (isset($_FILES['company_background'])) {
            $data['company_background'] = $this->companyProfile->handleFileUpload(
                $_FILES['company_background'],
                'company_background',
                $existingData['company_background'] ?? null
            );
        } else {
            $data['company_background'] = $existingData['company_background'] ?? null;
        }
        
        if (isset($_FILES['footer_image'])) {
            $data['footer_image'] = $this->companyProfile->handleFileUpload(
                $_FILES['footer_image'],
                'footer_image',
                $existingData['footer_image'] ?? null
            );
        } else {
            $data['footer_image'] = $existingData['footer_image'] ?? null;
        }
        
        // Save profile
        if ($this->companyProfile->save($data)) {
            $this->formHandler->addSuccess("Company profile saved successfully!");
            return true;
        } else {
            $this->formHandler->addError("Error saving company profile. Please try again.");
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


