<?php
/**
 * GenzNewz — Reporter Footer Layout
 */
?>
        </main> <!-- End reporter-content-body -->

        <footer class="bg-white border-top py-3 px-4 text-muted small d-flex justify-content-between align-items-center">
            <div>&copy; <?= date('Y') ?> GenzNewz Reporter Portal. Core PHP & MySQL.</div>
            <div>প্রেস অ্যান্ড জার্নালিস্ট ডেস্ক</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('btn-reporter-sidebar-toggle')?.addEventListener('click', () => {
        document.getElementById('reporter-sidebar').classList.toggle('show');
    });

    $(document).on('submit', '.confirm-delete-form', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: "এই খসড়াটি মুছে যাবে!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'হ্যাঁ, মুছুন!',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<?php if (isset($extraReporterScripts)) echo $extraReporterScripts; ?>

</body>
</html>
