<?php
/**
 * GenzNewz — Admin Footer Layout
 */
?>
        </main> <!-- End admin-content-body -->

        <footer class="bg-white border-top py-3 px-4 text-muted small d-flex justify-content-between align-items-center">
            <div>&copy; <?= date('Y') ?> GenzNewz Media & Publishing System. Core PHP & MySQL.</div>
            <div>ভার্সন 2.5 (প্রডাকশন রেডি)</div>
        </footer>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS (Includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery 3.7 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Admin Core JS -->
<script>
    // Toggle Mobile Sidebar
    document.getElementById('btn-sidebar-toggle')?.addEventListener('click', () => {
        document.getElementById('admin-sidebar').classList.toggle('show');
    });

    // Confirmation on Delete using SweetAlert2
    $(document).on('submit', '.confirm-delete-form', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: "এই তথ্যটি স্থায়ীভাবে মুছে যাবে!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'হ্যাঁ, মুছে ফেলুন!',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<?php if (isset($extraAdminScripts)) echo $extraAdminScripts; ?>

</body>
</html>
