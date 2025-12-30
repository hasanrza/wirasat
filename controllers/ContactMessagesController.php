<?php

/**
 * ContactMessagesController Class
 * Handles all contact form submissions and messages
 */
class ContactMessagesController {
    
    private $contactMessages;
    private $success_message = '';
    private $error_message = '';
    
    /**
     * Constructor
     * @param ContactMessages $contactMessages Contact messages object
     */
    public function __construct($contactMessages) {
        $this->contactMessages = $contactMessages;
    }
    
    /**
     * Get all contact messages
     * @return array Array of all messages
     */
    public function getAllMessages() {
        return $this->contactMessages->getAll();
    }
    
    /**
     * Get unread messages
     * @return array Array of unread messages
     */
    public function getUnreadMessages() {
        return $this->contactMessages->getUnread();
    }
    
    /**
     * Get message by ID
     * @param int $id Message ID
     * @return array Message data
     */
    public function getMessageById($id) {
        return $this->contactMessages->getById($id);
    }
    
    /**
     * Get total message count
     * @return int Total count
     */
    public function getMessageCount() {
        return $this->contactMessages->getCount();
    }
    
    /**
     * Get unread message count
     * @return int Unread count
     */
    public function getUnreadMessageCount() {
        return $this->contactMessages->getUnreadCount();
    }
    
    /**
     * Submit contact form
     * @param array $postData Form data
     * @return bool Success status
     */
    public function submitContactForm($postData) {
        // Validate required fields
        if (empty($postData['full_name'])) {
            $this->error_message = "Full name is required.";
            return false;
        }
        
        if (empty($postData['email_address'])) {
            $this->error_message = "Email address is required.";
            return false;
        }
        
        if (!$this->contactMessages->validateEmail($postData['email_address'])) {
            $this->error_message = "Please enter a valid email address.";
            return false;
        }
        
        if (empty($postData['message_subject'])) {
            $this->error_message = "Message subject is required.";
            return false;
        }
        
        if (empty($postData['message_body'])) {
            $this->error_message = "Message body is required.";
            return false;
        }
        
        // Sanitize data
        $sanitizedData = $this->contactMessages->sanitize($postData);
        
        // Save to database
        if ($this->contactMessages->save($sanitizedData)) {
            $this->success_message = "Your message has been sent successfully! We will get back to you soon.";
            return true;
        } else {
            $this->error_message = "Failed to submit your message. Please try again.";
            return false;
        }
    }
    
    /**
     * Mark message as read
     * @param int $id Message ID
     * @return bool Success status
     */
    public function markAsRead($id) {
        return $this->contactMessages->markAsRead($id);
    }
    
    /**
     * Mark message as unread
     * @param int $id Message ID
     * @return bool Success status
     */
    public function markAsUnread($id) {
        return $this->contactMessages->markAsUnread($id);
    }
    
    /**
     * Delete message
     * @param int $id Message ID
     * @return bool Success status
     */
    public function deleteMessage($id) {
        if ($this->contactMessages->delete($id)) {
            $this->success_message = "Message deleted successfully.";
            return true;
        } else {
            $this->error_message = "Failed to delete message. Please try again.";
            return false;
        }
    }
    
    /**
     * Handle form submission
     * @param array $postData POST data from form
     * @return bool Success status
     */
    public function handleFormSubmission($postData) {
        if (isset($postData['submit_contact'])) {
            return $this->submitContactForm($postData);
        } elseif (isset($postData['delete_message'])) {
            return $this->deleteMessage((int)$postData['delete_message']);
        } elseif (isset($postData['mark_read'])) {
            $this->markAsRead((int)$postData['mark_read']);
            return true;
        } elseif (isset($postData['mark_unread'])) {
            $this->markAsUnread((int)$postData['mark_unread']);
            return true;
        }
        return false;
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
