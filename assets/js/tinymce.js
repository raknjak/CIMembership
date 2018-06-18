tinyMCE.init({

    selector: "#message",
    theme: "modern",

     plugins: [
     "advlist autolink link image lists charmap print preview hr anchor pagebreak",
     "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker",
     "table contextmenu directionality emoticons paste textcolor responsivefilemanager code colorpicker"
     ],

    relative_urls: true,
    browser_spellcheck: true,
    //filemanager_title: "Responsive Filemanager",
    //external_filemanager_path: CI.base_url + "assets/vendor/responsive_filemanager/filemanager/",
    //external_plugins: { "filemanager" : CI.base_url + "assets/vendor/responsive_filemanager/filemanager/plugin.min.js"},

    image_advtab: true,
    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    toolbar2: "| image | media | link unlink anchor | print preview code | forecolor backcolor"
    //toolbar2: "| responsivefilemanager | image | media | link unlink anchor | print preview code"
});