<?php
/**
 * GenzNewz — Admin Bulk Page Uploader View
 */
require_once ROOT_PATH . '/admin/views/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="fa-solid fa-cloud-arrow-up text-success me-2"></i> বাল্ক পাতা আপলোড: <?= Helper::e($edition['title']) ?>
                </h5>
                <a href="/admin/editions/<?= $edition['id'] ?>/pages" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left"></i> পাতার তালিকায় ফিরুন
                </a>
            </div>
            <div class="card-body p-4">
                <form action="/admin/pages/upload?edition_id=<?= $edition['id'] ?>" method="POST" enctype="multipart/form-data" id="bulk-upload-form">
                    <?= CSRF::field() ?>

                    <div class="alert alert-info small mb-4">
                        <i class="fa-solid fa-circle-info me-1"></i> <strong>নির্দেশনা:</strong> আপনি একসাথে ১ থেকে ৩০টি পাতা (JPG, PNG, WEBP, SVG) নির্বাচন করতে পারেন। ফাইলগুলি নামের ক্রমানুসারে (যেমন: <code>01.jpg</code>, <code>02.jpg</code>...) সাজানো হলে স্বয়ংক্রিয়ভাবে পাতার নম্বর যুক্ত হবে এবং থাম্বনেইল প্রস্তুত হবে।
                    </div>

                    <!-- Dropzone style file input -->
                    <div class="mb-4 text-center p-5 border border-2 border-dashed rounded bg-light" id="dropzone-area" style="cursor: pointer;">
                        <i class="fa-solid fa-images fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold mb-1">এখানে ফাইলের পাতাগুলো টেনে আনুন (Drag & Drop)</h5>
                        <p class="text-muted small mb-3">অথবা আপনার কম্পিউটার থেকে ব্রাউজ করতে এখানে ক্লিক করুন</p>
                        <input type="file" name="pages[]" id="file-input" class="d-none" multiple accept="image/jpeg,image/png,image/webp,image/svg+xml" required>
                        <button type="button" class="btn btn-outline-success px-4" onclick="document.getElementById('file-input').click()">
                            <i class="fa-solid fa-folder-open me-2"></i> ফাইল নির্বাচন করুন
                        </button>
                    </div>

                    <!-- Selected Files List Preview -->
                    <div id="file-list-preview" class="mb-4" style="display: none;">
                        <h6 class="fw-bold text-dark mb-2">নির্বাচিত পাতাগুলোর তালিকা (<span id="selected-count">0</span>টি ফাইল):</h6>
                        <ul class="list-group small" id="selected-files-ul"></ul>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/admin/editions/<?= $edition['id'] ?>/pages" class="btn btn-light border px-4">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4" id="btn-submit-upload" style="background: #0B6B3A; border-color: #0B6B3A;" disabled>
                            <i class="fa-solid fa-upload"></i> আপলোড ও প্রসেস শুরু করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.getElementById('file-input');
        const dropzone = document.getElementById('dropzone-area');
        const previewArea = document.getElementById('file-list-preview');
        const filesUl = document.getElementById('selected-files-ul');
        const countSpan = document.getElementById('selected-count');
        const submitBtn = document.getElementById('btn-submit-upload');

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('bg-success-subtle');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('bg-success-subtle');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('bg-success-subtle');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                renderFileList();
            }
        });

        fileInput.addEventListener('change', renderFileList);

        function renderFileList() {
            filesUl.innerHTML = '';
            const files = Array.from(fileInput.files);
            
            // Sort by filename naturally
            files.sort((a, b) => a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' }));

            if (files.length > 0) {
                previewArea.style.display = 'block';
                countSpan.textContent = files.length;
                submitBtn.disabled = false;

                files.forEach((file, idx) => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                        <div>
                            <span class="badge bg-success me-2">পাতা ${idx + 1}</span>
                            <strong>${file.name}</strong>
                        </div>
                        <span class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    `;
                    filesUl.appendChild(li);
                });
            } else {
                previewArea.style.display = 'none';
                submitBtn.disabled = true;
            }
        }
    });
</script>

<?php require_once ROOT_PATH . '/admin/views/layouts/footer.php'; ?>
