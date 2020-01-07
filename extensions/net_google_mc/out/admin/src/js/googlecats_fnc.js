var $netQuery = $.noConflict();
$netQuery(document).ready(function () {
    $netQuery("[type=checkbox]").click(function () {
        var trg = $netQuery(this).parent().children("span").html();
        trg = trg.replace(/&amp;/g, "&");
        trg = trg.replace(/&gt;/g, ">");
        trg = trg.replace(/&Auml;/g, "Ä");
        trg = trg.replace(/:x!&Ouml;/g, "O");
        trg = trg.replace(/&Uuml;/g, "Ü");
        trg = trg.replace(/&auml;/g, "ä");
        trg = trg.replace(/&ouml;/g, "ö");
        trg = trg.replace(/&uuml;/g, "ü");
        trg = trg.replace(/&szlig;/g, "ß");
        if($netQuery(this).is(':checked')) {
            $netQuery("input[id^='" +trg+ "']").attr('checked', true);
        }
        else {
            $netQuery("input[id^='" +trg+ "']").attr('checked', false);
        }
    });
});