// Conflicts if more than 2 Jquery

var $netQuery = $.noConflict();
$netQuery(document).ready(function () {
    if ($netQuery("#iStart").val() != 0 && $netQuery("#iStart").val() != "finished") {
        $netQuery("#myedit").delay(1000).submit();
    }
});


// No JQUERY Version:

var counter = 0;
var interval = setInterval(function() {
    counter++;
    // Display 'counter' wherever you want to display it.
    if (counter == 5) {
        // Do it

        if (document.getElementById("#iStart").value != 0 && document.getElementById("#iStart").value != "finished") {
            // Do it
            document.getElementById("#myedit").submit();
            clearInterval(interval);
        }
    }
}, 1000);