<?php
require_once '../app/models/Resource.php';
// Resource Controller to handle resource-related actions for students
class ResourceController extends Controller {
    public function index() {
        $resourceModel = new Resource();
        $searchTerm = $_GET['q'] ?? null;
        $subjects = $resourceModel->getSubjectsWithCounts($searchTerm);
        
        $this->view('resources/index', [
            'subjects' => $subjects,
            'searchTerm' => $searchTerm,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }

    public function subject() {
        $subject = $_GET['subject'] ?? '';
        if (empty($subject)) {
            $this->redirect('resources');
        }

        $this->view('resources/subject', [
            'subject' => $subject,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }

    public function list() {
        $subject = $_GET['subject'] ?? '';
        $term = $_GET['term'] ?? 'mid';
        
        if (empty($subject)) {
            $this->redirect('resources');
        }

        $resourceModel = new Resource();
        $resources = $resourceModel->getResourcesBySubjectAndTerm($subject, $term);

        $this->view('resources/list', [
            'subject' => $subject,
            'term' => $term,
            'resources' => $resources,
            'role' => $_SESSION['role'] ?? 'student'
        ]);
    }
}
