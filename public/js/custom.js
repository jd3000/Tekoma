// (function () {
//     'use strict';
//     window.addEventListener('load', function () {
//         // Fetch all the forms we want to apply custom Bootstrap validation styles to
//         let forms = document.getElementsByClassName('needs-validation');
//         // Loop over them and prevent submission
//         let validation = Array.prototype.filter.call(forms, function (form) {
//             form.addEventListener('submit', function (event) {
//                 if (form.checkValidity() === false) {
//                     event.preventDefault();
//                     event.stopPropagation();
//                 }
//                 form.classList.add('was-validated');
//             }, false);
//         });
//     }, false);
// })();




function burgerToggle() {
    let x = document.getElementById("myTopnav");
    let y = document.getElementById("burger");

    if (x.className === "row coverBlack d-flex-block d-md-none mx-1") {
        x.className = "row coverBlack d-none d-md-none mx-1";
        y.src = "/img/burger.png";
    } else {
        x.className = "row coverBlack d-flex-block d-md-none mx-1";
        y.src = "/img/cross.png";
    }
}
// recaptcha V3
// $('#prospectForm').submit(function (e) {
//     e.preventDefault();
//     grecaptcha.ready(function () {
//         grecaptcha.execute('6LcqcWocAAAAAKnafYT5t7dP9gtuc2dyxucHvmp9', { action: 'submit' }).then(function (token) {
//             $('#prospectType_recaptcha').val(token);
//             $('#prospectForm').off().submit();
//         });
//     });
// });

// function loginToggle() {
//     let x = document.getElementById("myTopnav");
//     let y = document.getElementById("login");

//     if (x.className === "row coverBlack d-flex-block d-md-none mx-1") {
//         x.className = "row coverBlack d-none d-md-none mx-1";
//         y.src = "/img/burger.png";
//     } else {
//         // x.className = "row coverBlack d-flex-block d-md-none mx-1";
//         // y.src = "/img/cross.png";
//     }
// }


