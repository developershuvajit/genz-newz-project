<?php
/**
 * GenzNewz — Admin Reporter Management Controller
 */

class AdminReporterController extends Controller {
    public function index(): void {
        Auth::requireAdmin();

        $status = $_GET['status'] ?? 'all';
        $reporters = User::getReporters($status);

        $this->view('admin/views/reporters/index', [
            'pageTitle' => 'রিপোর্টার ও সাংবাদিক ব্যবস্থাপনা',
            'reporters' => $reporters,
            'currentStatus' => $status
        ]);
    }

    public function create(): void {
        Auth::requireAdmin();
        $nextReporterId = User::generateReporterId();

        $this->view('admin/views/reporters/create', [
            'pageTitle' => 'নতুন সাংবাদিক / রিপোর্টার নিবন্ধন',
            'nextReporterId' => $nextReporterId
        ]);
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? 'reporter123';
        $fatherName = trim($_POST['father_name'] ?? '');
        $dob = $_POST['date_of_birth'] ?? null;
        $bloodGroup = $_POST['blood_group'] ?? 'B+';
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? 'Kolkata');
        $state = trim($_POST['state'] ?? 'West Bengal');
        $pinCode = trim($_POST['pin_code'] ?? '');
        $designation = trim($_POST['designation'] ?? 'Staff Reporter');
        $joiningDate = $_POST['joining_date'] ?? date('Y-m-d');
        $validUntil = $_POST['valid_until'] ?? date('Y-m-d', strtotime('+2 years'));
        $assignedArea = trim($_POST['assigned_area'] ?? 'Kolkata Bureau');
        $emergencyContact = trim($_POST['emergency_contact'] ?? '');
        $status = $_POST['status'] ?? 'active';

        // Check if email already exists
        if (User::findBy('email', $email)) {
            Session::setFlash('error', 'এই ইমেইল দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে।');
            $this->redirect('/admin/reporters/create');
            return;
        }

        $photoPath = '/storage/uploads/reporters/default_reporter.jpg';
        if (!empty($_FILES['profile_photo']['name'])) {
            $res = Upload::file($_FILES['profile_photo'], 'uploads/reporters', 5);
            if ($res['success']) {
                $photoPath = $res['file_path'];
            }
        }

        $reporterId = User::generateReporterId();
        $empCode = 'EMP-' . date('Y') . '-' . rand(10, 99);

        // 1. Create user account
        $userId = User::create([
            'role' => ROLE_REPORTER,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'profile_image' => $photoPath,
            'status' => $status
        ]);

        // 2. Create reporter profile
        ReporterProfile::create([
            'user_id' => $userId,
            'reporter_id' => $reporterId,
            'employee_code' => $empCode,
            'full_name' => $name,
            'father_name' => $fatherName,
            'date_of_birth' => $dob,
            'blood_group' => $bloodGroup,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'pin_code' => $pinCode,
            'profile_photo' => $photoPath,
            'designation' => $designation,
            'joining_date' => $joiningDate,
            'valid_until' => $validUntil,
            'assigned_area' => $assignedArea,
            'emergency_contact' => $emergencyContact,
            'id_card_status' => 'active',
            'authorized_signature' => '/storage/uploads/signatures/editor_sign.png'
        ]);

        Auth::logActivity('REPORTER_CREATED', "Created reporter: {$name} ({$reporterId})");
        Session::setFlash('success', "সাংবাদিক {$name} ({$reporterId}) সফলভাবে নিবন্ধিত হয়েছেন।");
        $this->redirect("/admin/reporters/id-card/{$userId}");
    }

    public function show(int|string $id): void {
        Auth::requireAdmin();
        $user = User::find($id);

        if (!$user || $user['role'] !== ROLE_REPORTER) {
            $this->error404('রিপোর্টার খুঁজে পাওয়া যায়নি।');
            return;
        }

        $profile = ReporterProfile::findByUserId($id);
        $articles = Article::where('reporter_id', $id, 'id DESC', 10);

        $this->view('admin/views/reporters/view', [
            'pageTitle' => "রিপোর্টার প্রোফাইল: {$user['name']} ({$profile['reporter_id']})",
            'user' => $user,
            'profile' => $profile,
            'articles' => $articles
        ]);
    }

    public function edit(int|string $id): void {
        Auth::requireAdmin();
        $user = User::find($id);
        if (!$user) {
            $this->error404('রিপোর্টার খুঁজে পাওয়া যায়নি।');
            return;
        }

        $profile = ReporterProfile::findByUserId($id);

        $this->view('admin/views/reporters/edit', [
            'pageTitle' => "রিপোর্টার সম্পাদনা: {$user['name']}",
            'user' => $user,
            'profile' => $profile
        ]);
    }

    public function update(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $user = User::find($id);
        $profile = ReporterProfile::findByUserId($id);
        if (!$user || !$profile) {
            $this->error404('রিপোর্টার পাওয়া যায়নি।');
            return;
        }

        $name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $designation = trim($_POST['designation'] ?? 'Staff Reporter');
        $assignedArea = trim($_POST['assigned_area'] ?? 'Kolkata Bureau');
        $validUntil = $_POST['valid_until'] ?? $profile['valid_until'];
        $status = $_POST['status'] ?? $user['status'];
        $idCardStatus = $_POST['id_card_status'] ?? $profile['id_card_status'];

        $userData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        if (!empty($_POST['new_password'])) {
            $userData['password'] = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        }

        if (!empty($_FILES['profile_photo']['name'])) {
            $res = Upload::file($_FILES['profile_photo'], 'uploads/reporters', 5);
            if ($res['success']) {
                $userData['profile_image'] = $res['file_path'];
                ReporterProfile::update($profile['id'], ['profile_photo' => $res['file_path']]);
            }
        }

        User::update($id, $userData);

        ReporterProfile::update($profile['id'], [
            'full_name' => $name,
            'email' => $email,
            'phone' => $phone,
            'designation' => $designation,
            'assigned_area' => $assignedArea,
            'valid_until' => $validUntil,
            'id_card_status' => $idCardStatus
        ]);

        Auth::logActivity('REPORTER_UPDATED', "Updated reporter info: {$name} (ID: {$id})");
        Session::setFlash('success', 'রিপোর্টার তথ্য সফলভাবে আপডেট করা হয়েছে।');
        $this->redirect('/admin/reporters');
    }

    public function toggleStatus(int|string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $user = User::find($id);
        if ($user) {
            $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
            User::update($id, ['status' => $newStatus]);
            Auth::logActivity('REPORTER_STATUS_TOGGLED', "Reporter {$user['name']} status changed to {$newStatus}");
            Session::setFlash('success', "রিপোর্টার অ্যাকাউন্ট স্ট্যাটাস পরিবর্তিত হয়েছে: {$newStatus}");
        }

        $this->redirect('/admin/reporters');
    }

    public function idCard(int|string $id): void {
        Auth::requireAdmin();
        $user = User::find($id);

        if (!$user) {
            $this->error404('ব্যবহারকারী পাওয়া যায়নি।');
            return;
        }

        $profile = ReporterProfile::findByUserId($id);
        if (!$profile) {
            $this->error404('রিপোর্টার প্রোফাইল পাওয়া যায়নি।');
            return;
        }

        $this->view('admin/views/reporters/id_card', [
            'pageTitle' => "প্রেস আইডি কার্ড — {$profile['full_name']} ({$profile['reporter_id']})",
            'user' => $user,
            'profile' => $profile
        ]);
    }
}
