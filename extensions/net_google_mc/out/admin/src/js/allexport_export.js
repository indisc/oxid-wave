var $netQuery = $.noConflict();
$netQuery(document).ready(function () {
    if ($netQuery("#iStart").val() != 0 && $netQuery("#iStart").val() != "finished") {
        $netQuery("#myedit").delay(1000).submit();
    }
});