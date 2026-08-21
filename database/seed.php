<?php
/**
 * GenzNewz — Database Seeder
 * Seeds realistic initial data for development and demonstration.
 */

function seedDatabase(PDO $pdo): void {
    // 1. Seed Settings
    $settings = [
        'site_name' => 'GenzNewz',
        'site_title' => 'GenzNewz — Latest News & ePaper',
        'site_tagline' => 'Your News. Your Voice.',
        'site_logo' => '/public/assets/images/logo.png',
        'site_favicon' => '/public/assets/images/favicon.png',
        'contact_email' => 'editor@genznewz.com',
        'contact_phone' => '+91 33 2489 7700',
        'contact_address' => '7A, Central Avenue, Esplanade, Kolkata 700069, West Bengal',
        'social_facebook' => 'https://facebook.com/genznewz',
        'social_twitter' => 'https://x.com/genznewz',
        'social_instagram' => 'https://instagram.com/genznewz',
        'social_youtube' => 'https://youtube.com/@genznewz',
        'seo_title' => 'GenzNewz — Trusted Bengali & National Digital Newspaper and ePaper',
        'seo_description' => 'Read today’s digital newspaper, latest breaking news, Kolkata updates, business, sports, and comprehensive ePaper editions on GenzNewz.',
        'footer_text' => '© 2026 GenzNewz Media Network Pvt. Ltd. All rights reserved. Registered under RNI Act.',
        'breaking_news_enabled' => '1',
        'epaper_download_enabled' => '1',
        'maintenance_mode' => '0'
    ];

    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, key_value, created_at, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
    // Fallback for MySQL INSERT ... ON DUPLICATE KEY UPDATE
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'mysql') {
        $stmt = $pdo->prepare("INSERT INTO settings (key_name, key_value, created_at, updated_at) VALUES (?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE key_value = VALUES(key_value)");
    }

    foreach ($settings as $key => $val) {
        $stmt->execute([$key, $val]);
    }

    // 2. Seed Users
    $passwordAdmin = password_hash('admin123', PASSWORD_BCRYPT);
    $passwordReporter = password_hash('reporter123', PASSWORD_BCRYPT);

    $users = [
        [
            'role' => 'admin',
            'name' => 'Sourav Mukherjee',
            'email' => 'admin@genznewz.com',
            'phone' => '+91 98300 12345',
            'password' => $passwordAdmin,
            'profile_image' => '/storage/uploads/avatars/admin.jpg',
            'status' => 'active'
        ],
        [
            'role' => 'reporter',
            'name' => 'Rahul Sen',
            'email' => 'rahul@genznewz.com',
            'phone' => '+91 98311 54321',
            'password' => $passwordReporter,
            'profile_image' => '/storage/uploads/reporters/rahul.jpg',
            'status' => 'active'
        ],
        [
            'role' => 'reporter',
            'name' => 'Priya Banerjee',
            'email' => 'priya@genznewz.com',
            'phone' => '+91 98322 98765',
            'password' => $passwordReporter,
            'profile_image' => '/storage/uploads/reporters/priya.jpg',
            'status' => 'active'
        ]
    ];

    $userStmt = $pdo->prepare("INSERT INTO users (role, name, email, phone, password, profile_image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $userIds = [];

    foreach ($users as $u) {
        $userStmt->execute([$u['role'], $u['name'], $u['email'], $u['phone'], $u['password'], $u['profile_image'], $u['status']]);
        $userIds[$u['email']] = (int)$pdo->lastInsertId();
    }

    // 3. Seed Reporter Profiles
    $reporters = [
        [
            'user_id' => $userIds['rahul@genznewz.com'],
            'reporter_id' => 'GNZ-RPT-0001',
            'employee_code' => 'EMP-2025-01',
            'full_name' => 'Rahul Sen',
            'father_name' => 'Animesh Sen',
            'date_of_birth' => '1995-04-12',
            'blood_group' => 'B+',
            'phone' => '+91 98311 54321',
            'email' => 'rahul@genznewz.com',
            'address' => '24/1, Rashbehari Avenue, Gariahat',
            'city' => 'Kolkata',
            'state' => 'West Bengal',
            'pin_code' => '700019',
            'profile_photo' => '/storage/uploads/reporters/rahul.jpg',
            'designation' => 'Senior Crime & Civic Reporter',
            'joining_date' => '2025-01-10',
            'valid_until' => '2027-12-31',
            'assigned_area' => 'South Kolkata & Howrah',
            'emergency_contact' => '+91 98300 00001',
            'id_card_status' => 'active',
            'authorized_signature' => '/storage/uploads/signatures/editor_sign.png'
        ],
        [
            'user_id' => $userIds['priya@genznewz.com'],
            'reporter_id' => 'GNZ-RPT-0002',
            'employee_code' => 'EMP-2025-02',
            'full_name' => 'Priya Banerjee',
            'father_name' => 'Debabrata Banerjee',
            'date_of_birth' => '1998-09-21',
            'blood_group' => 'O+',
            'phone' => '+91 98322 98765',
            'email' => 'priya@genznewz.com',
            'address' => '108, Lake Town Block B',
            'city' => 'Kolkata',
            'state' => 'West Bengal',
            'pin_code' => '700089',
            'profile_photo' => '/storage/uploads/reporters/priya.jpg',
            'designation' => 'Special Political & State Correspondent',
            'joining_date' => '2025-03-01',
            'valid_until' => '2027-12-31',
            'assigned_area' => 'Nabanna & North 24 Parganas',
            'emergency_contact' => '+91 98300 00002',
            'id_card_status' => 'active',
            'authorized_signature' => '/storage/uploads/signatures/editor_sign.png'
        ]
    ];

    $repStmt = $pdo->prepare("INSERT INTO reporter_profiles (user_id, reporter_id, employee_code, full_name, father_name, date_of_birth, blood_group, phone, email, address, city, state, pin_code, profile_photo, designation, joining_date, valid_until, assigned_area, emergency_contact, id_card_status, authorized_signature, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    foreach ($reporters as $r) {
        $repStmt->execute([
            $r['user_id'], $r['reporter_id'], $r['employee_code'], $r['full_name'], $r['father_name'],
            $r['date_of_birth'], $r['blood_group'], $r['phone'], $r['email'], $r['address'],
            $r['city'], $r['state'], $r['pin_code'], $r['profile_photo'], $r['designation'],
            $r['joining_date'], $r['valid_until'], $r['assigned_area'], $r['emergency_contact'],
            $r['id_card_status'], $r['authorized_signature']
        ]);
    }

    // 4. Seed Edition Types
    $editionTypes = [
        ['name' => 'কলকাতা সংস্করণ', 'slug' => 'kolkata', 'sort_order' => 1],
        ['name' => 'উত্তরবঙ্গ সংস্করণ', 'slug' => 'north-bengal', 'sort_order' => 2],
        ['name' => 'দক্ষিণবঙ্গ সংস্করণ', 'slug' => 'south-bengal', 'sort_order' => 3],
        ['name' => 'বর্ধমান ও আসানসোল', 'slug' => 'bardhaman-asansol', 'sort_order' => 4],
        ['name' => 'মেদিনীপুর সংস্করণ', 'slug' => 'medinipur', 'sort_order' => 5],
        ['name' => 'শিলিগুড়ি বিশেষ', 'slug' => 'siliguri-special', 'sort_order' => 6]
    ];

    $etStmt = $pdo->prepare("INSERT INTO edition_types (name, slug, sort_order, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    $editionTypeIds = [];
    foreach ($editionTypes as $et) {
        $etStmt->execute([$et['name'], $et['slug'], $et['sort_order']]);
        $editionTypeIds[$et['slug']] = (int)$pdo->lastInsertId();
    }

    // 5. Seed Categories
    $categories = [
        ['name' => 'প্রথম পাতা', 'name_en' => 'Front Page', 'slug' => 'front-page', 'sort_order' => 1],
        ['name' => 'কলকাতা', 'name_en' => 'Kolkata', 'slug' => 'kolkata', 'sort_order' => 2],
        ['name' => 'রাজ্য', 'name_en' => 'State', 'slug' => 'state', 'sort_order' => 3],
        ['name' => 'দেশ', 'name_en' => 'National', 'slug' => 'india', 'sort_order' => 4],
        ['name' => 'বিদেশ', 'name_en' => 'World', 'slug' => 'world', 'sort_order' => 5],
        ['name' => 'খেলা', 'name_en' => 'Sports', 'slug' => 'sports', 'sort_order' => 6],
        ['name' => 'বিনোদন', 'name_en' => 'Entertainment', 'slug' => 'entertainment', 'sort_order' => 7],
        ['name' => 'ব্যবসা ও অর্থনীতি', 'name_en' => 'Business', 'slug' => 'business', 'sort_order' => 8],
        ['name' => 'প্রযুক্তি ও গ্যাজেট', 'name_en' => 'Tech', 'slug' => 'tech', 'sort_order' => 9],
        ['name' => 'লাইফস্টাইল ও স্বাস্থ্য', 'name_en' => 'Lifestyle', 'slug' => 'lifestyle', 'sort_order' => 10],
        ['name' => 'চাকরি ও শিক্ষা', 'name_en' => 'Career', 'slug' => 'career', 'sort_order' => 11],
        ['name' => 'সম্পাদকীয়', 'name_en' => 'Editorial', 'slug' => 'editorial', 'sort_order' => 12]
    ];

    $catStmt = $pdo->prepare("INSERT INTO categories (name, name_en, slug, sort_order, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $categoryIds = [];
    foreach ($categories as $c) {
        $catStmt->execute([$c['name'], $c['name_en'], $c['slug'], $c['sort_order']]);
        $categoryIds[$c['slug']] = (int)$pdo->lastInsertId();
    }

    // 6. Seed Editions (Today's, Yesterday's, and Archive editions)
    $todayDate = date('Y-m-d');
    $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
    $twoDaysAgo = date('Y-m-d', strtotime('-2 days'));
    $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

    $editions = [
        [
            'title' => 'GenzNewz কলকাতা — ' . date('d F Y'),
            'slug' => 'edition-' . date('d-m-Y'),
            'edition_date' => $todayDate,
            'edition_type_id' => $editionTypeIds['kolkata'],
            'description' => 'আজকের দৈনিক ডিজিটাল সংস্করণ — সম্পূর্ণ ১২ পাতার রঙিন ই-পেপার।',
            'cover_image' => '/storage/pages/thumb/page_1_today.jpg',
            'pdf_file' => '/storage/pdf/genznewz_today.pdf',
            'status' => 'published',
            'is_featured' => 1,
            'created_by' => $userIds['admin@genznewz.com']
        ],
        [
            'title' => 'GenzNewz উত্তরবঙ্গ — ' . date('d F Y'),
            'slug' => 'edition-north-bengal-' . date('d-m-Y'),
            'edition_date' => $todayDate,
            'edition_type_id' => $editionTypeIds['north-bengal'],
            'description' => 'উত্তরবঙ্গ বিশেষ ডিজিটাল সংস্করণ। শিলিগুড়ি, জলপাইগুড়ি ও ডুয়ার্স সংবাদ।',
            'cover_image' => '/storage/pages/thumb/page_1_nb.jpg',
            'pdf_file' => '/storage/pdf/genznewz_nb.pdf',
            'status' => 'published',
            'is_featured' => 0,
            'created_by' => $userIds['admin@genznewz.com']
        ],
        [
            'title' => 'GenzNewz কলকাতা — ' . date('d F Y', strtotime('-1 day')),
            'slug' => 'edition-' . date('d-m-Y', strtotime('-1 day')),
            'edition_date' => $yesterdayDate,
            'edition_type_id' => $editionTypeIds['kolkata'],
            'description' => 'গতকালকের কলকাতা সংস্করণ সংরক্ষণাগার।',
            'cover_image' => '/storage/pages/thumb/page_1_yesterday.jpg',
            'pdf_file' => '/storage/pdf/genznewz_yesterday.pdf',
            'status' => 'published',
            'is_featured' => 0,
            'created_by' => $userIds['admin@genznewz.com']
        ],
        [
            'title' => 'GenzNewz কলকাতা — ' . date('d F Y', strtotime('-2 days')),
            'slug' => 'edition-' . date('d-m-Y', strtotime('-2 days')),
            'edition_date' => $twoDaysAgo,
            'edition_type_id' => $editionTypeIds['kolkata'],
            'description' => 'কলকাতা সংস্করণ আর্কাইভ।',
            'cover_image' => '/storage/pages/thumb/page_1_two_days.jpg',
            'pdf_file' => '/storage/pdf/genznewz_2days.pdf',
            'status' => 'archived',
            'is_featured' => 0,
            'created_by' => $userIds['admin@genznewz.com']
        ],
        [
            'title' => 'GenzNewz দক্ষিণবঙ্গ — ' . date('d F Y', strtotime('-3 days')),
            'slug' => 'edition-south-bengal-' . date('d-m-Y', strtotime('-3 days')),
            'edition_date' => $threeDaysAgo,
            'edition_type_id' => $editionTypeIds['south-bengal'],
            'description' => 'দক্ষিণবঙ্গ বিশেষ সংস্করণ আর্কাইভ।',
            'cover_image' => '/storage/pages/thumb/page_1_sb.jpg',
            'pdf_file' => '/storage/pdf/genznewz_sb.pdf',
            'status' => 'archived',
            'is_featured' => 0,
            'created_by' => $userIds['admin@genznewz.com']
        ]
    ];

    $edStmt = $pdo->prepare("INSERT INTO editions (title, slug, edition_date, edition_type_id, description, cover_image, pdf_file, status, is_featured, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $editionIds = [];
    foreach ($editions as $ed) {
        $edStmt->execute([
            $ed['title'], $ed['slug'], $ed['edition_date'], $ed['edition_type_id'],
            $ed['description'], $ed['cover_image'], $ed['pdf_file'], $ed['status'],
            $ed['is_featured'], $ed['created_by']
        ]);
        $editionIds[$ed['slug']] = (int)$pdo->lastInsertId();
    }

    // 7. Seed Edition Pages for Main Today's Edition (8 Pages)
    $todayEditionId = $editionIds['edition-' . date('d-m-Y')];
    $pageTitles = [
        1 => 'প্রথম পাতা — প্রধান সংবাদ ও জাতীয় রাজনীতি',
        2 => 'কলকাতা মহানগরী ও মেট্রো করিডোর',
        3 => 'রাজ্য ও প্রশাসনিক খবরাখবর',
        4 => 'জাতীয় প্রেক্ষাপট ও সংসদ ডায়েরি',
        5 => 'আন্তর্জাতিক কূটনীতি ও বিশ্ব সংবাদ',
        6 => 'ব্যবসা-বাণিজ্য, বাজার ও স্টার্টআপ',
        7 => 'ক্রীড়াঙ্গন — ক্রিকেট, ফুটবল ও অলিম্পিক',
        8 => 'বিনোদন, সাহিত্য ও সমকালীন সম্পাদকীয়'
    ];

    $pageStmt = $pdo->prepare("INSERT INTO edition_pages (edition_id, page_number, page_title, page_image, thumbnail, medium_image, pdf_page, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', CURRENT_TIMESTAMP)");
    for ($i = 1; $i <= 8; $i++) {
        $pageImg = "/storage/pages/original/page_{$i}.svg";
        $pageThumb = "/storage/pages/thumb/page_{$i}.svg";
        $pageMed = "/storage/pages/medium/page_{$i}.svg";
        $pageStmt->execute([$todayEditionId, $i, $pageTitles[$i], $pageImg, $pageThumb, $pageMed, "/storage/pdf/page_{$i}.pdf"]);
    }

    // Seed yesterday's edition with 4 pages
    $yesterdayId = $editionIds['edition-' . date('d-m-Y', strtotime('-1 day'))];
    for ($i = 1; $i <= 4; $i++) {
        $pageImg = "/storage/pages/original/page_{$i}.svg";
        $pageThumb = "/storage/pages/thumb/page_{$i}.svg";
        $pageMed = "/storage/pages/medium/page_{$i}.svg";
        $pageStmt->execute([$yesterdayId, $i, "পৃষ্ঠা {$i} — সংরক্ষিত সংস্করণ", $pageImg, $pageThumb, $pageMed, null]);
    }

    // 8. Seed Articles (Breaking, Featured, Top Stories, Category Articles)
    $articles = [
        [
            'reporter_id' => $userIds['priya@genznewz.com'],
            'category_id' => $categoryIds['front-page'],
            'edition_id' => $todayEditionId,
            'title' => 'রাজ্য বাজেটে স্বাস্থ্য ও গ্রামীণ পরিকাঠামোয় ঐতিহাসিক বরাদ্দ বৃদ্ধি, ছাড় মিলবে স্ট্যাম্প ডিউটিতে',
            'subheadline' => 'বিধানসভায় পেশ হল নতুন অর্থবর্ষের বাজেট — জোর দেওয়া হয়েছে নতুন কর্মসংস্থান ও তরুণ উদ্যোক্তা তহবিলে',
            'slug' => 'state-budget-record-allocation-health-rural-infrastructure',
            'short_description' => 'পশ্চিমবঙ্গ বিধানসভায় পেশ করা হল রাজ্য বাজেট। স্বাস্থ্য, শিক্ষা, পানীয় জল ও রাস্তা সংস্কারের খাতে বরাদ্দ বৃদ্ধির পাশাপাশি মাঝারি শিল্পে বিরাট আর্থিক উৎসাহ ঘোষণা।',
            'content' => '<p><strong>কলকাতা:</strong> রাজ্য বিধানসভায় আজ পেশ করা হল ২০২৬-২৭ অর্থবর্ষের সার্বিক বাজেট। অর্থমন্ত্রী বাজেট বক্তৃতায় স্পষ্ট করেছেন যে রাজ্যের অর্থনৈতিক প্রবৃদ্ধির গতি বজায় রাখতে গ্রামীণ পরিকাঠামো, সড়ক যোগাযোগ এবং ডিজিটাল স্বাস্থ্যসেবাকে সর্বোচ্চ অগ্রাধিকার দেওয়া হয়েছে।</p><p>বাজেটে স্বাস্থ্য সাথী প্রকল্পের আওতায় অতিরিক্ত পরিষেবা সংযোজনের পাশাপাশি প্রতিটি মহকুমা হাসপাতালে ক্রিটিক্যাল কেয়ার ইউনিট (CCU) স্থাপনের জন্য বিশেষ অর্থ বরাদ্দ করা হয়েছে। এছাড়া আবাসন ক্ষেত্রে গতি ফেরাতে স্ট্যাম্প ডিউটিতে অতিরিক্ত ছাড় অব্যাহত রাখার কথা ঘোষণা করা হয়েছে।</p><h3>বাজেটের প্রধান আকর্ষণসমূহ:</h3><ul><li>গ্রামীণ সড়ক যোজনা ও সেতু নির্মাণে রেকর্ড ₹১২,৫০০ কোটি বরাদ্দ</li><li>তরুণ উদ্যোক্তাদের জন্য সুদহীন সহজ স্টার্টআপ ঋণ প্রকল্প</li><li>মেগা শিল্প করিডোর সম্প্রসারণ ও ডানকুনী-তাজপুর লজিস্টিক কানেক্টিভিটি</li><li>মেগা সোলার এনার্জি পার্ক স্থাপনে নতুন সবুজ বিদ্যুৎ নীতি</li></ul><p>অর্থনৈতিক বিশেষজ্ঞদের মতে, এই বাজেট গ্রামীণ অর্থনীতিতে অর্থপ্রবাহ বাড়িয়ে সার্বিক কর্মসংস্থানে গতি আনবে। বিরোধী দলগুলির তরফে বাজেট নিয়ে মিশ্র প্রতিক্রিয়া পাওয়া গেলেও শিল্প মহল এই ঘোষণাকে স্বাগত জানিয়েছে।</p>',
            'featured_image' => '/storage/uploads/articles/budget_assembly.jpg',
            'author_name' => 'প্রিয়া ব্যানার্জি',
            'location' => 'কলকাতা',
            'status' => 'published',
            'is_breaking' => 1,
            'is_featured' => 1,
            'is_top_story' => 1,
            'views_count' => 18450,
            'published_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ],
        [
            'reporter_id' => $userIds['rahul@genznewz.com'],
            'category_id' => $categoryIds['kolkata'],
            'edition_id' => $todayEditionId,
            'title' => 'কলকাতা মেট্রোর অরেঞ্জ লাইনে চালু হচ্ছে সম্পূর্ণ স্বয়ংক্রিয় সিগন্যালিং, বিমানবন্দর পৌঁছানো হবে আরও দ্রুত',
            'subheadline' => 'নিউ গড়িয়া থেকে বিমানবন্দর মেট্রো করিডোরে সফল মহড়া সম্পন্ন, আগামী মাসেই ট্রায়াল রান',
            'slug' => 'kolkata-metro-orange-line-automatic-signaling-airport',
            'short_description' => 'কলকাতাবাসীর বহু প্রতীক্ষিত বিমানবন্দর মেট্রো সংযোগে বড় সাফল্য। আধুনিক CBTC সিগন্যালিং ব্যবস্থা বসানোর কাজ প্রায় চূড়ান্ত পর্যায়ে।',
            'content' => '<p><strong>কলকাতা:</strong> শহরবাসীর যাতায়াত আরও মসৃণ ও গতিময় করতে কলকাতা মেট্রো রেল কর্পোরেশন অরেঞ্জ লাইনের গুরুত্বপূর্ণ অংশে উন্নত কম্পিউটার-নিয়ন্ত্রিত কমিউনিকেশন-বেসড ট্রেন কন্ট্রোল (CBTC) সিগন্যালিং সফলভাবে পরীক্ষা করল।</p><p>এই আধুনিক সিগন্যালিং ব্যবস্থার ফলে ট্রেনের ব্যবধান কমে প্রতি ৫ মিনিটে নামিয়ে আনা সম্ভব হবে। যাত্রীদের নিরাপত্তা বৃদ্ধির পাশাপাশি ট্রেনের গতি প্রতি ঘণ্টায় ৮০ কিমি পর্যন্ত তোলা যাবে। মেট্রো কর্তৃপক্ষের সূত্রে খবর, রুবি থেকে সেক্টর ফাইভ পর্যন্ত অংশে খুব শীঘ্রই নিয়মিত বাণিজ্যিক পরিষেবা শুরু হতে চলেছে।</p><p>যাত্রী সাধারণের সুবিধার জন্য প্রতিটি স্টেশনে ডিজিটাল কিয়স্ক, স্মার্ট কিউআর টিকিটিং এবং স্বয়ংক্রিয় এস্কেলেটর স্থাপন করা হয়েছে।</p>',
            'featured_image' => '/storage/uploads/articles/metro_kolkata.jpg',
            'author_name' => 'রাহুল সেন',
            'location' => 'কলকাতা',
            'status' => 'published',
            'is_breaking' => 0,
            'is_featured' => 1,
            'is_top_story' => 1,
            'views_count' => 12390,
            'published_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
        ],
        [
            'reporter_id' => $userIds['priya@genznewz.com'],
            'category_id' => $categoryIds['sports'],
            'edition_id' => $todayEditionId,
            'title' => 'ইডেনে রুদ্ধশ্বাস ডার্বি লড়াইয়ে শেষ মুহূর্তের গোলে জয় ছিনিয়ে নিল সবুজ-মেরুন ব্রিগেড',
            'subheadline' => 'যুবভারতীতে ৯০ মিনিটের রোমাঞ্চকর ম্যাচে লাল-হলুদ ডিফেন্স ভেঙে জয়সূচক গোল দিমিত্রিয়সের',
            'slug' => 'derby-thriller-at-yuva-bharati-green-maroon-victory',
            'short_description' => 'আইএসএলের হাইভোল্টেজ কলকাতা ডার্বিতে ৬২ হাজার দর্শকের সামনে টানটান লড়াই। অতিরিক্ত সময়ে বাঁ পায়ের নিখুঁত ভলিতে বাজিমাত।',
            'content' => '<p><strong>কলকাতা:</strong> চিরপ্রতিদ্বন্দ্বী দুই দলের লড়াই মানেই আবেগের বিস্ফোরণ। যুবভারতী ক্রীড়াঙ্গনে অনুষ্ঠিত এই মরশুমের দ্বিতীয় কলকাতা ডার্বিতে দর্শকদের শ্বাসরুদ্ধকর মুহূর্ত উপহার দিল দুই দলই। প্রথমার্ধে কোনো দল গোলমুখ খুলতে না পারলেও দ্বিতীয়ার্ধে আক্রমণের ঝাঁঝ বাড়িয়ে দেয় দুই পক্ষই।</p><p>৮৮ মিনিটের মাথায় বক্সের বাইরে থেকে জোরালো শট গোলকিপার ফিস্ট করলেও রিবাউন্ডে বল পেয়ে জালে জড়ান অজি ফরোয়ার্ড। এই জয়ের ফলে লিগ টেবিলের শীর্ষস্থান আরও মজবুত করল দল। ম্যাচ শেষে কোচ জানান, "ছেলেরা শেষ সেকেন্ড পর্যন্ত হাল ছাড়েনি, এই জয় সমর্থকদের প্রাপ্য।"</p>',
            'featured_image' => '/storage/uploads/articles/derby_football.jpg',
            'author_name' => 'প্রিয়া ব্যানার্জি',
            'location' => 'যুবভারতী ক্রীড়াঙ্গন',
            'status' => 'published',
            'is_breaking' => 0,
            'is_featured' => 1,
            'is_top_story' => 0,
            'views_count' => 9420,
            'published_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))
        ],
        [
            'reporter_id' => $userIds['rahul@genznewz.com'],
            'category_id' => $categoryIds['business'],
            'edition_id' => $todayEditionId,
            'title' => 'শেয়ার বাজারে সর্বকালের নয়া রেকর্ড, সেনসেক্স পেরোল ৮৫,০০০-এর ঐতিহাসিক মাইলফলক',
            'subheadline' => 'তথ্যপ্রযুক্তি, ব্যাংকিং ও গাড়ি নির্মাণ শিল্পে বিপুল বিদেশি বিনিয়োগের জোয়ারে চাঙ্গা স্টক মার্কেট',
            'slug' => 'stock-market-record-sensex-crosses-85000-milestone',
            'short_description' => 'বিশ্ববাজারে মূল্যস্ফীতি নিয়ন্ত্রণের আশ্বাসের পর ভারতীয় শেয়ার সূচকে অভূতপূর্ব উচ্ছ্বাস। নিফটি স্পর্শ করল ২৬,০০০ পয়েন্ট।',
            'content' => '<p><strong>মুম্বই:</strong> সপ্তাহের শেষ লেনদেনের দিনে দেশের শেয়ার বাজারে সৃষ্টি হল এক নতুন ইতিহাস। বাজার খোলার সাথে সাথেই বিনিয়োগকারীদের ব্যাপক ক্রয়ের চাপে বোম্বে স্টক এক্সচেঞ্জের সেনসেক্স এক লাফে ৭৫০ পয়েন্ট বৃদ্ধি পেয়ে ৮৫,০০০ এর ঐতিহাসিক শিখর অতিক্রম করে।</p><p>বিশেষ করে আইটি জায়ান্ট, রাষ্ট্রায়ত্ত ব্যাংক ও পুনর্নবীকরণযোগ্য শক্তি খাতের শেয়ারগুলিতে রেকর্ড ট্রেডিং লক্ষ্য করা গেছে। বাজার বিশ্লেষকদের মতে, ভারতের মজবুত জিডিপি প্রবৃদ্ধি ও বিদেশি প্রাতিষ্ঠানিক বিনিয়োগকারীদের (FII) ধারাবাহিক বিনিয়োগের ফলেই এই অভাবনীয় উত্থান সম্ভব হয়েছে।</p>',
            'featured_image' => '/storage/uploads/articles/stock_market.jpg',
            'author_name' => 'রাহুল সেন',
            'location' => 'মুম্বই ব্যুরো',
            'status' => 'published',
            'is_breaking' => 1,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 7620,
            'published_at' => date('Y-m-d H:i:s', strtotime('-8 hours'))
        ],
        [
            'reporter_id' => $userIds['priya@genznewz.com'],
            'category_id' => $categoryIds['tech'],
            'edition_id' => $todayEditionId,
            'title' => 'কৃত্রিম বুদ্ধিমত্তা ও কোয়ান্টাম কম্পিউটিং গবেষণায় ভারতের প্রথম জাতীয় হাব স্থাপিত হচ্ছে কলকাতায়',
            'subheadline' => 'আইআইটি ও প্রেসিডেন্সি বিশ্ববিদ্যালয়ের যৌথ উদ্যোগে তৈরি হচ্ছে अत्याधुनिक রিসার্চ সেন্টার',
            'slug' => 'ai-quantum-computing-national-research-hub-kolkata',
            'short_description' => 'ভবিষ্যতের প্রযুক্তি গবেষণায় দেশকে নেতৃত্ব দিতে কলকাতায় আত্মপ্রকাশ করতে চলেছে জাতীয় কৃত্রিম বুদ্ধিমত্তা ও কোয়ান্টাম কম্পিউটিং হাব।',
            'content' => '<p><strong>কলকাতা:</strong> প্রযুক্তিগত উদ্ভাবনের নতুন দিশা দেখাতে কলকাতায় তৈরি হচ্ছে ভারতের অন্যতম বৃহৎ AI ও কোয়ান্টাম কম্পিউটিং সুপার ল্যাব। কেন্দ্রীয় বিজ্ঞান ও প্রযুক্তি মন্ত্রক এবং রাজ্য সরকারের যৌথ সহায়তায় সল্টলেকের সিলিকন ভ্যালিতে এই রিসার্চ পার্ক গড়ে উঠছে।</p><p>এই সেন্টারের মাধ্যমে জলবায়ু মডেলিং, স্বাস্থ্য বিজ্ঞানে ড্রাগ ডিসকভারি এবং উন্নত সাইবার সুরক্ষার ক্ষেত্রে যুগান্তকারী গবেষণা চালানো সম্ভব হবে। দেশ-বিদেশের শীর্ষ বিজ্ঞানী ও গবেষকরা এই প্রকল্পের সঙ্গে যুক্ত থাকবেন।</p>',
            'featured_image' => '/storage/uploads/articles/ai_tech.jpg',
            'author_name' => 'প্রিয়া ব্যানার্জি',
            'location' => 'সল্টলেক',
            'status' => 'published',
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 5410,
            'published_at' => date('Y-m-d H:i:s', strtotime('-12 hours'))
        ],
        [
            'reporter_id' => $userIds['rahul@genznewz.com'],
            'category_id' => $categoryIds['entertainment'],
            'edition_id' => $todayEditionId,
            'title' => 'কলকাতা আন্তর্জাতিক চলচ্চিত্র উৎসবে সেরা ছবির সম্মান পেল বাংলার স্বাধীন চলচ্চিত্র',
            'subheadline' => 'নন্দন ও রবীন্দ্রসদনে জমকালো সমাপ্তি অনুষ্ঠানে উপস্থিত ছিলেন দেশ-বিদেশের খ্যাতনামা পরিচালকরা',
            'slug' => 'kiff-kolkata-international-film-festival-awards-bengali-cinema',
            'short_description' => 'সপ্তাহব্যাপী কলকাতা আন্তর্জাতিক চলচ্চিত্র উৎসবের পর্দা নামল। দেশ-বিদেশের ছবির ভিড়ে বিশ্বমানের স্বীকৃতি পেল বাংলার তরুণ পরিচালকের মৌলিক সিনেমা।',
            'content' => '<p><strong>কলকাতা:</strong> চলচ্চিত্রপ্রেমীদের উৎসবের সফল সমাপ্তি ঘটল। নন্দন চত্বরে আয়োজিত বর্ণাঢ্য অনুষ্ঠানে গোল্ডেন রয়্যাল বেঙ্গল টাইগার ট্রফি প্রদান করা হয়। আন্তর্জাতিক জুরির প্রশংসায় সিক্ত হয়েছে সমকালীন সামাজিক প্রেক্ষাপটে নির্মিত বাংলা ছবিটি।</p><p>উৎসব কমিটির চেয়ারম্যান জানান, এই বছর রেকর্ড সংখ্যক দর্শক প্রেক্ষাগৃহে ছবি দেখেছেন এবং মাস্টারক্লাসগুলিতে তরুণ চিত্রপরিচালকদের স্বতঃস্ফূর্ত অংশগ্রহণ লক্ষ্য করা গেছে।</p>',
            'featured_image' => '/storage/uploads/articles/nandan_kiff.jpg',
            'author_name' => 'রাহুল সেন',
            'location' => 'কলকাতা',
            'status' => 'published',
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 6180,
            'published_at' => date('Y-m-d H:i:s', strtotime('-16 hours'))
        ],
        // Draft and Pending articles for testing the Reporter workflow
        [
            'reporter_id' => $userIds['rahul@genznewz.com'],
            'category_id' => $categoryIds['kolkata'],
            'edition_id' => null,
            'title' => 'হাওড়া ব্রিজে লাইট অ্যান্ড সাউন্ড শোয়ের নতুন সংস্করণ উদ্বোধনের পথে',
            'subheadline' => 'পর্যটনকে আকর্ষণ করতে গঙ্গার ঘাটে নতুন গ্যাজেট ও লেজার প্রজেকশন ব্যবস্থা',
            'slug' => 'howrah-bridge-light-and-sound-show-upgrade',
            'short_description' => 'হাওড়া ব্রিজ ও বাবুঘাট এলাকায় সান্ধ্যকালীন সৌন্দর্য বৃদ্ধিতে নতুন প্রযুক্তি বসানো হচ্ছে।',
            'content' => '<p>হাওড়া ব্রিজকে ঘিরে পর্যটন শিল্পের বিকাশে নতুন লাইট অ্যান্ড সাউন্ড শো চালুর উদ্যোগ নিয়েছে কলকাতা বন্দর কর্তৃপক্ষ।</p>',
            'featured_image' => '/storage/uploads/articles/howrah_bridge.jpg',
            'author_name' => 'রাহুল সেন',
            'location' => 'হাওড়া',
            'status' => 'submitted', // Submitted for Admin Review
            'rejection_reason' => null,
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 0,
            'published_at' => null
        ],
        [
            'reporter_id' => $userIds['priya@genznewz.com'],
            'category_id' => $categoryIds['state'],
            'edition_id' => null,
            'title' => 'সুন্দরবনে সৌরচালিত জল পরিস্রুতকরণ প্ল্যান্ট বসানোর খসড়া প্রস্তাব',
            'subheadline' => 'দ্বীপবাসীর পানীয় জলের সমস্যা মেটাতে গ্রিন এনার্জি প্রকল্পের উদ্যোগ',
            'slug' => 'sundarban-solar-water-purification-plant-draft',
            'short_description' => 'নোনা জলের এলাকায় মিষ্টি জলের জোগান দিতে পরিবেশবান্ধব ডিস্যালিনেশন প্রকল্প।',
            'content' => '<p>সুন্দরবনের উপকূলবর্তী গ্রামগুলিতে নোনা জলের সংকট দূর করতে আধুনিক রিভার্স অসমোসিস প্ল্যান্ট বসানো হবে।</p>',
            'featured_image' => '/storage/uploads/articles/sundarban.jpg',
            'author_name' => 'প্রিয়া ব্যানার্জি',
            'location' => 'সুন্দরবন',
            'status' => 'draft', // Draft by reporter
            'rejection_reason' => null,
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 0,
            'published_at' => null
        ],
        [
            'reporter_id' => $userIds['rahul@genznewz.com'],
            'category_id' => $categoryIds['sports'],
            'edition_id' => null,
            'title' => 'সিএবি সুপার লিগে শতক হাঁকালেন অনূর্ধ্ব-১৯ তারকা ব্যাটার',
            'subheadline' => 'ইডেনের ২ নম্বর মাঠে অসাধারণ ইনিংস খেলে নির্বাচকদের নজরে তরুণ প্রতিভা',
            'slug' => 'cab-super-league-century-u19-cricket',
            'short_description' => 'ঘরোয়া ক্রিকেটে দুরন্ত ফর্মে বাংলার উদীয়মান ক্রিকেটার।',
            'content' => '<p>সিএবি সুপার ডিভিশন লিগে বল হাতে যেমন নজর কাড়লেন বোলাররা, তেমনই ব্যাটে ঝোড়ো ইনিংস উপহার দিলেন তরুণ ওপেনার।</p>',
            'featured_image' => '/storage/uploads/articles/cricket_eden.jpg',
            'author_name' => 'রাহুল সেন',
            'location' => 'ইডেন গার্ডেন্স',
            'status' => 'rejected', // Rejected with reason
            'rejection_reason' => 'খেলোয়াড়ের পরিসংখ্যান ও স্কোরকার্ডের সম্পূর্ণ বিবরণ যুক্ত করে পুনরায় জমা দিন।',
            'is_breaking' => 0,
            'is_featured' => 0,
            'is_top_story' => 0,
            'views_count' => 0,
            'published_at' => null
        ]
    ];

    $artStmt = $pdo->prepare("INSERT INTO articles (reporter_id, category_id, edition_id, title, subheadline, slug, short_description, content, featured_image, author_name, location, status, rejection_reason, is_breaking, is_featured, is_top_story, views_count, published_at, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    foreach ($articles as $art) {
        $artStmt->execute([
            $art['reporter_id'], $art['category_id'], $art['edition_id'], $art['title'],
            $art['subheadline'], $art['slug'], $art['short_description'], $art['content'],
            $art['featured_image'], $art['author_name'], $art['location'], $art['status'],
            $art['rejection_reason'] ?? null, $art['is_breaking'], $art['is_featured'], $art['is_top_story'],
            $art['views_count'], $art['published_at']
        ]);
    }

    // 9. Seed Activity Logs
    $logs = [
        ['user_id' => $userIds['admin@genznewz.com'], 'user_name' => 'Sourav Mukherjee (Admin)', 'action' => 'EDITION_PUBLISHED', 'details' => 'Published today Kolkata edition with 8 newspaper pages.', 'ip_address' => '127.0.0.1'],
        ['user_id' => $userIds['admin@genznewz.com'], 'user_name' => 'Sourav Mukherjee (Admin)', 'action' => 'REPORTER_CREATED', 'details' => 'Created reporter account for Rahul Sen (GNZ-RPT-0001).', 'ip_address' => '127.0.0.1'],
        ['user_id' => $userIds['admin@genznewz.com'], 'user_name' => 'Sourav Mukherjee (Admin)', 'action' => 'ID_CARD_GENERATED', 'details' => 'Generated & authorized press accreditation ID card for Priya Banerjee (GNZ-RPT-0002).', 'ip_address' => '127.0.0.1'],
        ['user_id' => $userIds['rahul@genznewz.com'], 'user_name' => 'Rahul Sen (Reporter)', 'action' => 'ARTICLE_SUBMITTED', 'details' => 'Submitted article: হাওড়া ব্রিজে লাইট অ্যান্ড সাউন্ড শো...', 'ip_address' => '127.0.0.1']
    ];

    $logStmt = $pdo->prepare("INSERT INTO activity_logs (user_id, user_name, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    foreach ($logs as $l) {
        $logStmt->execute([$l['user_id'], $l['user_name'], $l['action'], $l['details'], $l['ip_address']]);
    }

    // 10. Seed Notifications
    $notifications = [
        ['user_id' => $userIds['admin@genznewz.com'], 'title' => 'নতুন সংবাদ জমা পড়েছে', 'message' => 'রিপোর্টার রাহুল সেন "হাওড়া ব্রিজে লাইট অ্যান্ড সাউন্ড..." শীর্ষক একটি নতুন প্রতিবেদন পর্যালোচনার জন্য জমা দিয়েছেন।', 'type' => 'review', 'link' => '/admin/articles/pending', 'is_read' => 0],
        ['user_id' => $userIds['rahul@genznewz.com'], 'title' => 'সংবাদ প্রকাশিত হয়েছে', 'message' => 'আপনার জমা দেওয়া "কলকাতা মেট্রোর অরেঞ্জ লাইন..." প্রতিবেদনটি সফলভাবে প্রকাশিত হয়েছে।', 'type' => 'success', 'link' => '/reporter/articles', 'is_read' => 1],
        ['user_id' => $userIds['priya@genznewz.com'], 'title' => 'প্রেস আইডি কার্ড সক্রিয় হয়েছে', 'message' => 'আপনার GNZ-RPT-0002 প্রেস আইডি কার্ডটি ২০২৭ সালের জন্য অনুমোদিত হয়েছে। আইডি কার্ড বিভাগ থেকে কার্ডটি ডাউনলোড করুন।', 'type' => 'id_card', 'link' => '/reporter/id-card', 'is_read' => 0]
    ];

    $notifStmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, link, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    foreach ($notifications as $n) {
        $notifStmt->execute([$n['user_id'], $n['title'], $n['message'], $n['type'], $n['link'], $n['is_read']]);
    }
}
