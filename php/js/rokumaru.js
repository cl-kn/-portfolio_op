const target = document.getElementById('target');
target.addEventListener('change', function(e) {
    const file = e.target.files[0]
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById("myImage")
        img.src = e.target.result;
    }
    reader.readAsDataURL(file);
}, false);

// (function ($) {
//     function formSetDay() {
//         var lastday = formSetLastDay($('.js-changeYear').val(), $('.js-changeMonth').val());
//         var option = '';
//         for (var i = 1; i <= lastday; i++) {
//             if (i === $('js-changeDay').val()) {
//                 option += '<option value="' + i + '"selected="selected">' + i + '</option>\n';
//             } else {
//                 option += '<option value="' + i + '">' + i + '</option>\n';
//             }
//         }
//         $('js-changeDay').html(option);
//     }

//     function formSetLastDay(year, month) {
//         var lastday = new Array("", 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
//         if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
//             //うるう年？
//             lastday[2] = 29;
//         }
//         return lastday[month];
//     }
//     $('.js-changeYear,.js-changeMonth').change(function () {
//         formSetDay();
//     });
// })(jQuery);

(function($) {
    function formSetDay() {
        var lastday = formSetLastDay($('.js-changeYear').val(), $('.js-changeMonth').val());
        var option = '';
        for (var i = 1; i <= lastday; i++) {
            if (i === $('.js-changeDay').val()) {
                option += '<option value="' + i + '" selected="selected">' + i + '</option>\n';
            } else {
                option += '<option value="' + i + '">' + i + '</option>\n';
            }
        }
        $('.js-changeDay').html(option);
    }

    function formSetLastDay(year, month) {
        var lastday = new Array('', 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
            lastday[2] = 29;
        }
        return lastday[month];
    }

    $('.js-changeYear, .js-changeMonth').change(function() {
        formSetDay();
    });
})(jQuery);