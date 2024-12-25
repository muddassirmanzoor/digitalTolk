<!-- build:js assetsvendor/js/core.js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>

<!-- third party js -->
<script src="{{ asset('assets/js/vendor/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/dataTables.select.min.js') }}"></script>
<!-- third party js ends -->

<!-- demo app -->
<script src="{{ asset('assets/js/pages/demo.datatable-init.js') }}"></script>
<!-- end demo js-->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });
</script>
<script>
    function toggleSection(sectionId) {
        // Get the checkbox by its ID
        var checkbox = document.getElementById('toggle' + sectionId.charAt(0).toUpperCase() + sectionId.slice(1));

        // Ensure the checkbox exists
        if (checkbox) {
            var section = document.querySelector('.' + sectionId);
            if (checkbox.checked) {
                section.style.display = 'flex'; // Show section
            } else {
                section.style.display = 'none'; // Hide section
            }
        } else {
            console.error('Checkbox with id ' + 'toggle' + sectionId.charAt(0).toUpperCase() + sectionId.slice(1) + ' not found!');
        }
    }

    $(document).ready(function () {
        $('.table-striped').DataTable({
            dom: 'Bfrtip', // Placement for export buttons
            buttons: [
                'copy',       // Copy to clipboard
                'csv',        // Export as CSV
                'excel',      // Export as Excel
                'pdf',        // Export as PDF
                'print'       // Print the table
            ],
            paging: true,      // Enable pagination
            searching: true,   // Enable search
            ordering: true,    // Enable column sorting
            lengthChange: true, // Enable entries per page dropdown
            order: [[3, 'asc']]
        });
    });

</script>

