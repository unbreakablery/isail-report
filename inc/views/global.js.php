<script>

    // Get specific time from local storage by form name
    function setSpecificTimeFromLocalStorage(form_name) {
        var session = window.localStorage.getItem(form_name);
        session = session ? JSON.parse(session) : [];
        for (let i = 0; i < session.length; i += 1) {
            if (session[i].name == 'specific_time') {
                $('#specific_time').val(session[i].value);
            }
        }
    }

    // Generate file name for PDF export using jsPDF and Html2Canvas
    function getFileNameForExport(report, file_type) {
        return "<?php echo $common->getPrefixProjectName(); ?>" + "_" + report + "_" + Math.round(new Date() / 1000) + "." + file_type;
    }

    function getBasicForm() {
        var post = <?php echo json_encode($_POST); ?>;
        var exportForm = [];
        exportForm.push('<form method="post">');
        for (key in post) {
            exportForm.push("<input type='hidden' name='", key, "' value='", post[key], "' />");
        }
        return exportForm;
    }

</script>