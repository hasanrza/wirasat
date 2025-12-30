<?php

/**
 * NewsUpdatesController Class
 * Handles all news updates related operations
 */
class NewsUpdatesController {
    
    private $newsUpdates;
    private $formHandler;
    private $newsData;
    
    /**
     * Constructor
     * @param NewsUpdates $newsUpdates News updates object
     * @param FormHandler $formHandler Form handler object
     */
    public function __construct($newsUpdates, $formHandler = null) {
        $this->newsUpdates = $newsUpdates;
        $this->formHandler = $formHandler;
    }
    
    /**
     * Load news data for editing
     * @param int|null $newsId
     * @return array|null
     */
    public function loadNewsData($newsId = null) {
        if ($newsId) {
            $this->newsData = $this->newsUpdates->getById($newsId);
        }
        return $this->newsData;
    }
    
    /**
     * Get news data
     * @return array|null
     */
    public function getNewsData() {
        return $this->newsData;
    }
    
    /**
     * Get all news updates
     * @param bool $activeOnly
     * @return array Array of news updates
     */
    public function getAllNews($activeOnly = false) {
        return $this->newsUpdates->getAll($activeOnly);
    }
    
    /**
     * Get active news updates
     * @return array Array of active news updates
     */
    public function getActiveNews() {
        return $this->newsUpdates->getActive();
    }
    
    /**
     * Get news update by ID
     * @param int $id News ID
     * @return array News data
     */
    public function getNewsById($id) {
        return $this->newsUpdates->getById($id);
    }
    
    /**
     * Get total news count
     * @return int Total count
     */
    public function getNewsCount() {
        return $this->newsUpdates->getCount();
    }
    
    /**
     * Process form submission
     * @param int|null $newsId - If provided, update existing news
     * @return bool
     */
    public function processRequest($newsId = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        
        // Validate required fields
        $newsText = $_POST['news_text'] ?? '';
        if (empty(trim(strip_tags($newsText)))) {
            $this->formHandler->addError('News text is required.');
            return false;
        }
        
        // Get existing data if editing
        $existingData = $newsId ? $this->newsUpdates->getById($newsId) : null;
        
        // Gather data
        $data = [];
        $data['news_text'] = $newsText; // HTML content from Quill
        $data['status'] = isset($_POST['status']) ? 1 : 0;
        
        // Handle image upload
        $data['news_image'] = $existingData['news_image'] ?? null;
        if (isset($_FILES['news_image']) && $_FILES['news_image']['size'] > 0) {
            $data['news_image'] = $this->newsUpdates->handleFileUpload(
                $_FILES['news_image'],
                'news_image',
                $existingData['news_image'] ?? null
            );
        }
        
        // Handle video upload
        $data['news_video'] = $existingData['news_video'] ?? null;
        if (isset($_FILES['news_video']) && $_FILES['news_video']['size'] > 0) {
            $data['news_video'] = $this->newsUpdates->handleFileUpload(
                $_FILES['news_video'],
                'news_video',
                $existingData['news_video'] ?? null
            );
        }
        
        // Handle YouTube link
        $data['youtube_link'] = isset($_POST['youtube_link']) && !empty(trim($_POST['youtube_link'])) 
            ? trim($_POST['youtube_link']) 
            : null;
        
        // Save or Update
        if ($newsId) {
            // Update existing news
            if (!$this->newsUpdates->update($newsId, $data)) {
                $this->formHandler->addError('Error updating news.');
                return false;
            }
            $this->formHandler->addSuccess('News updated successfully!');
        } else {
            // Create new news
            $savedId = $this->newsUpdates->create($data);
            if (!$savedId) {
                $this->formHandler->addError('Error creating news.');
                return false;
            }
            $this->formHandler->addSuccess('News created successfully!');
        }
        
        return true;
    }
    
    /**
     * Delete news
     * @param int $newsId
     * @return bool
     */
    public function deleteNews($newsId) {
        if ($this->newsUpdates->delete($newsId)) {
            $this->formHandler->addSuccess("News deleted successfully!");
            return true;
        }
        
        $this->formHandler->addError("Error deleting news. Please try again.");
        return false;
    }
    
    /**
     * Toggle news status
     * @param int $id News ID
     * @return bool Success status
     */
    public function toggleNewsStatus($id) {
        return $this->newsUpdates->toggleStatus($id);
    }
    
    /**
     * Get success message
     * @return string|null
     */
    public function getSuccessMessage() {
        return $this->formHandler ? $this->formHandler->getFirstSuccess() : '';
    }
    
    /**
     * Get error message
     * @return string|null
     */
    public function getErrorMessage() {
        return $this->formHandler ? $this->formHandler->getFirstError() : '';
    }
}
