<?php

/**
 * GalleryController Class
 * Handles all gallery related operations
 */
class GalleryController {
    
    private $galleryPictures;
    private $galleryVideos;
    private $success_message = '';
    private $error_message = '';
    
    /**
     * Constructor
     * @param GalleryPictures $galleryPictures Gallery pictures object
     * @param GalleryVideos $galleryVideos Gallery videos object
     */
    public function __construct($galleryPictures, $galleryVideos) {
        $this->galleryPictures = $galleryPictures;
        $this->galleryVideos = $galleryVideos;
    }
    
    /**
     * Get gallery statistics
     * @return array Gallery statistics
     */
    public function getStatistics() {
        return [
            'total_pictures' => $this->galleryPictures->getCount(),
            'total_videos' => $this->galleryVideos->getCount()
        ];
    }
    
    /**
     * Get all pictures
     * @return array Array of pictures
     */
    public function getAllPictures() {
        return $this->galleryPictures->getAll();
    }
    
    /**
     * Get all videos
     * @return array Array of videos
     */
    public function getAllVideos() {
        return $this->galleryVideos->getAll();
    }
    
    /**
     * Get active pictures
     * @return array Array of active pictures
     */
    public function getActivePictures() {
        return $this->galleryPictures->getActive();
    }
    
    /**
     * Get active videos
     * @return array Array of active videos
     */
    public function getActiveVideos() {
        return $this->galleryVideos->getActive();
    }
    
    /**
     * Get picture by ID
     * @param int $id Picture ID
     * @return array Picture data
     */
    public function getPictureById($id) {
        return $this->galleryPictures->getById($id);
    }
    
    /**
     * Get video by ID
     * @param int $id Video ID
     * @return array Video data
     */
    public function getVideoById($id) {
        return $this->galleryVideos->getById($id);
    }
    
    /**
     * Add picture
     * @param array $data Picture data
     * @return bool Success status
     */
    public function addPicture($data) {
        if (empty($data['picture_title']) || empty($data['picture_file'])) {
            $this->error_message = "Picture title and file are required.";
            return false;
        }
        
        if ($this->galleryPictures->save($data)) {
            $this->success_message = "Picture added successfully.";
            return true;
        } else {
            $this->error_message = "Failed to add picture. Please try again.";
            return false;
        }
    }
    
    /**
     * Update picture
     * @param array $data Picture data
     * @return bool Success status
     */
    public function updatePicture($data) {
        if (empty($data['id']) || empty($data['picture_title']) || empty($data['picture_file'])) {
            $this->error_message = "Invalid picture data.";
            return false;
        }
        
        if ($this->galleryPictures->save($data)) {
            $this->success_message = "Picture updated successfully.";
            return true;
        } else {
            $this->error_message = "Failed to update picture. Please try again.";
            return false;
        }
    }
    
    /**
     * Delete picture
     * @param int $id Picture ID
     * @return bool Success status
     */
    public function deletePicture($id) {
        if ($this->galleryPictures->delete($id)) {
            $this->success_message = "Picture deleted successfully.";
            return true;
        } else {
            $this->error_message = "Failed to delete picture. Please try again.";
            return false;
        }
    }
    
    /**
     * Add video
     * @param array $data Video data
     * @return bool Success status
     */
    public function addVideo($data) {
        if (empty($data['video_title']) || empty($data['video_url'])) {
            $this->error_message = "Video title and URL are required.";
            return false;
        }
        
        if ($this->galleryVideos->save($data)) {
            $this->success_message = "Video added successfully.";
            return true;
        } else {
            $this->error_message = "Failed to add video. Please try again.";
            return false;
        }
    }
    
    /**
     * Update video
     * @param array $data Video data
     * @return bool Success status
     */
    public function updateVideo($data) {
        if (empty($data['id']) || empty($data['video_title']) || empty($data['video_url'])) {
            $this->error_message = "Invalid video data.";
            return false;
        }
        
        if ($this->galleryVideos->save($data)) {
            $this->success_message = "Video updated successfully.";
            return true;
        } else {
            $this->error_message = "Failed to update video. Please try again.";
            return false;
        }
    }
    
    /**
     * Delete video
     * @param int $id Video ID
     * @return bool Success status
     */
    public function deleteVideo($id) {
        if ($this->galleryVideos->delete($id)) {
            $this->success_message = "Video deleted successfully.";
            return true;
        } else {
            $this->error_message = "Failed to delete video. Please try again.";
            return false;
        }
    }
    
    /**
     * Toggle picture status
     * @param int $id Picture ID
     * @return bool Success status
     */
    public function togglePictureStatus($id) {
        return $this->galleryPictures->toggleStatus($id);
    }
    
    /**
     * Toggle video status
     * @param int $id Video ID
     * @return bool Success status
     */
    public function toggleVideoStatus($id) {
        return $this->galleryVideos->toggleStatus($id);
    }
    
    /**
     * Get success message
     * @return string Success message
     */
    public function getSuccessMessage() {
        return $this->success_message;
    }
    
    /**
     * Get error message
     * @return string Error message
     */
    public function getErrorMessage() {
        return $this->error_message;
    }
}
