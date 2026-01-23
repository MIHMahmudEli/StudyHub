<?php

require_once '../app/models/Note.php';
require_once '../app/models/User.php';

class NoteController extends Controller {

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
        
        $data = [];
        if (isset($_SESSION['upload_error'])) {
            $data['error'] = $_SESSION['upload_error'];
            unset($_SESSION['upload_error']);
        }
        if (isset($_SESSION['upload_success'])) {
            $data['success'] = $_SESSION['upload_success'];
            unset($_SESSION['upload_success']);
        }

        $this->view('notes/create', $data);
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
            $course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';
            
            // Validation
            if (empty($title) || empty($subject) || !isset($_FILES['file'])) {
                $_SESSION['upload_error'] = 'Please fill in all required fields and select a file.';
                $this->redirect('upload');
            }

            // File Upload Logic
            $file = $_FILES['file'];
            $fileError = $file['error'];
            
            if ($fileError !== UPLOAD_ERR_OK) {
                $msg = "File upload error code: " . $fileError;
                if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
                    $msg = "File is too large! Maximum upload size is 40 MB.";
                }
                $_SESSION['upload_error'] = $msg;
                $this->redirect('upload');
            }
            
            if ($file['size'] > 40 * 1024 * 1024) {
                 $_SESSION['upload_error'] = "File exceeds 40 MB limit!";
                 $this->redirect('upload');
            }

            $fileName = $file['name'];
            $tmpName  = $file['tmp_name'];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            
            // Use public/uploads directory
            // Ensure unique filename to prevent overwrite? Legacy didn't seems to enforce uniqueness strictly but let's be safe or just use name
            // Legacy: $file_path = $target_dir . basename($file_name);
            // We should stick to legacy pathing if we want existing file links to work, but new files can go to new place.
            // Let's put them in 'uploads/' inside public.
            
            $targetDir = ROOT_PATH . "/public/assets/uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            // Clean filename
            $cleanName = basename($fileName);
            // Avoid duplicates
            $targetFilePath = $targetDir . $cleanName;
            $dbFilePath = "assets/uploads/" . $cleanName; // Path stored in DB relative to public
            
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $noteModel = new Note();
                
                $data = [
                    'uploader_id' => $_SESSION['user_id'],
                    'title' => $title,
                    'description' => $description,
                    'subject' => $subject,
                    'course_code' => $course_code,
                    'file_path' => $dbFilePath,
                    'file_type' => $fileType
                ];
                
                if ($noteModel->create($data)) {
                    // Log event
                    $userModel = new User();
                    $userModel->logEvent($_SESSION['user_id'], 'upload');
                    
                    $_SESSION['upload_success'] = "Note uploaded successfully! Awaiting admin approval.";
                    $this->redirect('upload');
                } else {
                    $_SESSION['upload_error'] = "Database error occurred.";
                    $this->redirect('upload');
                }
            } else {
                $_SESSION['upload_error'] = "Failed to move uploaded file.";
                $this->redirect('upload');
            }

        } else {
            $this->redirect('upload');
        }
    }

    public function show() {
        if (!isset($_GET['id'])) {
             $this->redirect('home/dashboard');
        }
        $id = intval($_GET['id']);
        $type = $_GET['type'] ?? 'note';
        $homeLink = isset($_GET['track']);

        if ($type === 'resource') {
             require_once '../app/models/Resource.php';
             $model = new Resource();
             $item = $model->find($id);
        } else {
             $noteModel = new Note();
             $item = $noteModel->find($id);
        }

        if (!$item) {
            echo ucfirst($type) . " not found."; 
            return;
        }
        
        $role = $_SESSION['role'] ?? 'student';
        $userRating = 0;
        if (isset($_SESSION['user_id']) && $type === 'note') {
            $userRating = $noteModel->getRating($id, $_SESSION['user_id']);
        }
        
        $this->view('notes/show', [
            'note' => $item,
            'role' => $role,
            'track' => $_GET['track'] ?? null,
            'userRating' => $userRating,
            'type' => $type
        ]);
    }

    public function download() {
        if (!isset($_GET['id'])) return;
        $id = intval($_GET['id']);
        $type = $_GET['type'] ?? 'note';
        
        if ($type === 'resource') {
             require_once '../app/models/Resource.php';
             $model = new Resource();
             $item = $model->find($id);
        } else {
             $model = new Note();
             $item = $model->find($id);
        }
        
        if ($item) {
            if ($type === 'note') {
                $model->incrementDownloads($id);
                if (isset($_SESSION['user_id'])) {
                     $userModel = new User();
                     $userModel->logEvent($_SESSION['user_id'], 'download');
                }
            } elseif ($type === 'resource') {
                $model->incrementDownloads($id);
                // Resources also give points to uploader if they exist
                if ($item['uploader_id']) {
                    $userModel = new User();
                    $userModel->logEvent($item['uploader_id'], 'someone_download_your_note');
                }
                if (isset($_SESSION['user_id'])) {
                    $userModel = new User();
                    $userModel->logEvent($_SESSION['user_id'], 'download');
               }
            }
            
            $file = ROOT_PATH . '/public/' . $item['file_path'];

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            } else {
                echo "File not found on server.";
            }
        }
    }

    public function myNotes() {
        if (!isset($_SESSION['user_id'])) $this->redirect('login');
        
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        $noteModel = new Note();
        
        // Handle Delete
        if (isset($_GET['delete'])) {
            $noteId = intval($_GET['delete']);
            // Legacy path logic was messy, let's assume delete only removes from DB for now as legacy code did not unlink file. 
            // Wait, legacy logic: DELETE FROM notes ...
            // If we want to delete file too, we need path. 
            // Let's stick to DB delete for safety first or just leave file (garbage collection later).
            
            if ($noteModel->delete($noteId, $userId)) {
                 // success message
            }
            $this->redirect('note/my_notes');
        }

        $notes = $noteModel->getUserNotes($userId);
        
        $this->view('notes/my_notes', ['notes' => $notes, 'role' => $role]);
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
             $this->redirect('note/my_notes');
        }

        $noteId = intval($_POST['note_id']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $subject = trim($_POST['subject']);
        $courseCode = trim($_POST['course_code']);
        
        $noteModel = new Note();
        $noteModel->update($noteId, $_SESSION['user_id'], $title, $description, $subject, $courseCode);
        
        $this->redirect('note/my_notes');
    }

    public function rate() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in to rate notes.']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noteId = intval($_POST['note_id']);
            $rating = intval($_POST['rating']);
            $userId = $_SESSION['user_id'];

            if ($noteId <= 0 || $rating < 1 || $rating > 5) {
                echo json_encode(['success' => false, 'message' => 'Invalid request.']);
                return;
            }

            $noteModel = new Note();
            $newAvg = $noteModel->saveRating($noteId, $userId, $rating);
            
            if ($newAvg !== false) {
                echo json_encode(['success' => true, 'message' => 'Rating submitted!', 'new_avg' => $newAvg]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save rating.']);
            }
        }
    }

    public function toggleBookmark() {
        if (!isset($_SESSION['user_id'])) {
             echo "login_required";
             return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $noteId = intval($_POST['note_id']);
             $userId = $_SESSION['user_id'];
             
             $noteModel = new Note();
             if ($noteModel->isBookmarked($noteId, $userId)) {
                 if ($noteModel->removeBookmark($noteId, $userId)) {
                     echo "removed";
                 } else {
                     echo "error";
                 }
             } else {
                 if ($noteModel->addBookmark($noteId, $userId)) {
                     echo "added";
                 } else {
                     echo "error";
                 }
             }
        }
    }
}
