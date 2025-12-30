<?php
/**
 * Our Services Controller
 * Handles our services page logic
 */
class OurServicesController {
    private $ourServices;
    private $formHandler;
    private $serviceData;
    
    /**
     * Constructor
     * @param OurServices $ourServices
     * @param FormHandler $formHandler
     */
    public function __construct($ourServices, $formHandler) {
        $this->ourServices = $ourServices;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load service data
     * @param int|null $serviceId
     */
    public function loadServiceData($serviceId = null) {
        if ($serviceId) {
            $this->serviceData = $this->ourServices->getById($serviceId);
        }
        return $this->serviceData;
    }
    
    /**
     * Get all services
     * @param bool $activeOnly
     * @return array
     */
    public function getAllServices($activeOnly = false) {
        return $this->ourServices->getAll($activeOnly);
    }
    
    /**
     * Get service data
     * @return array
     */
    public function getServiceData() {
        return $this->serviceData;
    }
    
    /**
     * Process form submission
     * @param int|null $serviceId
     */
    public function processRequest($serviceId = null) {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }
        
        // Sanitize inputs
        $data = [
            'service_title' => $this->formHandler->sanitize($_POST['service_title'] ?? ''),
            'service_description' => $_POST['service_description'] ?? '', // HTML content
            'service_icon' => $this->formHandler->sanitize($_POST['service_icon'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'status' => isset($_POST['status']) ? 1 : 0
        ];
        
        // Validate required fields
        if (!$this->formHandler->validateRequired($data['service_title'], 'Service title')) {
            return false;
        }
        
        if (!$this->formHandler->validateRequired($data['service_description'], 'Service description')) {
            return false;
        }
        
        // Handle file upload
        $existingData = $serviceId ? $this->ourServices->getById($serviceId) : null;
        
        if (isset($_FILES['service_image']) && $_FILES['service_image']['size'] > 0) {
            $data['service_image'] = $this->ourServices->handleFileUpload(
                $_FILES['service_image'],
                'service_image',
                $existingData['service_image'] ?? null
            );
        } else {
            $data['service_image'] = $existingData['service_image'] ?? null;
        }
        
        // Save service
        if ($serviceId) {
            // Update existing service
            if ($this->ourServices->update($serviceId, $data)) {
                $this->formHandler->addSuccess("Service updated successfully!");
                return true;
            }
        } else {
            // Create new service
            if ($this->ourServices->create($data)) {
                $this->formHandler->addSuccess("Service created successfully!");
                return true;
            }
        }
        
        $this->formHandler->addError("Error saving service. Please try again.");
        return false;
    }
    
    /**
     * Delete service
     * @param int $serviceId
     * @return bool
     */
    public function deleteService($serviceId) {
        if ($this->ourServices->delete($serviceId)) {
            $this->formHandler->addSuccess("Service deleted successfully!");
            return true;
        }
        
        $this->formHandler->addError("Error deleting service. Please try again.");
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


