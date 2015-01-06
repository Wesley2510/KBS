<!--
Sander de Wilde
-->

<script type="text/javascript" src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "content",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste moxiemanager"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
</script>

<form method="post" action="somepage">
    <textarea id="content" style="width:100%"></textarea>
</form>