// Permet de fermer les flashbags après 10 s
setTimeout(function () {
    $(".btn-close").trigger('click');
}, 10000);

// $("#cp").autocomplete({
//     source: function (request, response) {
//         $.ajax({
//             url: "https://api-adresse.data.gouv.fr/search/?q=" + $("input[name='cp']").val() + '&type=municipality&autocomplete=1',
//             data: { q: request.term },
//             dataType: "json",
//             success: function (data) {
//                 response($.map(data.features, function (item) {
//                     console.log(item);
//                     console.log(data.features);

//                     return {
//                         label: item.properties.postcode + " – " + item.properties.city,
//                         city: item.properties.city,
//                         value: item.properties.postcode
//                     };
//                 }));
//             }
//         });
//     },
//     // On remplit aussi la ville
//     select: function (event, ui) {
//         $('#ville').val(ui.item.city);
//     }
// });
// $("#ville").autocomplete({
//     source: function (request, response) {
//         $.ajax({
//             url: "https://api-adresse.data.gouv.fr/search/?city=" + $("input[name='ville']").val(),
//             data: { q: request.term },
//             dataType: "json",
//             success: function (data) {
//                 let cities = [];
//                 response($.map(data.features, function (item) {
//                     // Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
//                     if ($.inArray(item.properties.postcode, cities) == -1) {
//                         cities.push(item.properties.postcode);
//                         return {
//                             label: item.properties.postcode + " - " + item.properties.city,
//                             postcode: item.properties.postcode,
//                             value: item.properties.city
//                         };
//                     }
//                 }));
//             }
//         });
//     },
//     // On remplit aussi le CP
//     select: function (event, ui) {
//         $('#cp').val(ui.item.postcode);
//     }
// });
// $("#adresse").autocomplete({
//     source: function (request, response) {
//         $.ajax({
//             url: "https://api-adresse.data.gouv.fr/search/?postcode=" + $("input[name='cp']").val(),
//             data: { q: request.term },
//             dataType: "json",
//             success: function (data) {
//                 response($.map(data.features, function (item) {
//                     return { label: item.properties.name, value: item.properties.name };
//                 }));
//             }
//         });
//     }
// });


// Permet de gérer le toggle du burger (small)
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

// admin / update.html.twig new.html.twig creation
$(document).ready(function () {
    let uploadPreview = document.getElementById("uploadPreview");
    formUpdateProduct = $("#update_creation_img");
    formNewProduct = $("#add_creation_img");
    form = "";
    // console.log(form);
    if (formUpdateProduct[0]) {
        form = formUpdateProduct[0];
    }
    else if (formNewProduct[0]) {
        form = formNewProduct[0];
    }
    // console.log(form);
    // console.log(uploadPreview);
    if (form) {
        $(form).on('change', function () {
            //Get count of selected files
            let countFiles = $(this)[0].files.length;
            // console.log(countFiles);
            let imgPath = $(this)[0].value;
            // console.log(imgPath);
            let extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            // console.log(extn);
            let image_holder = $("#image-holder");
            // console.log(image_holder);
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof (FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (let i = 0; i < countFiles; i++) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            image_holder.className = "imgProduct p-4";
                            $("<img />", {
                                "src": e.target.result,
                                "class": "card-img"
                            }).appendTo(image_holder);
                        }
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }
                    uploadPreview.className = "col-12 col-md-4 text-center imgProduct p-4 mt-4";
                } else {
                    alert("Ce navigateur ne supporte pas la lecture de fichiers.");
                }
            } else {
                alert("Merci de sélectionner une image.");
            }
        });
    }

    // Permet de scroller jusqu'au produit modifié
    // cible le flash bag contenant le nom du produit modifié
    m = document.getElementById("flash-alert-success");
    // console.log(m);
    if (m) {
        // définition de la hauteur de la nav
        const nav = document.querySelector(".fixed-top");
        navHeight = nav.clientHeight;
        // console.log(navHeight);
        // définition de l'attribut du flashbag et de l'id de la card du produit modifié 
        const modifiedProduct = m.querySelector('b');
        if (modifiedProduct) {
            const modifiedProductId = modifiedProduct.getAttribute('data-product-id');
            const productCard = document.getElementById("product-" + modifiedProductId);
            if (modifiedProduct) {
                const rect = productCard.getBoundingClientRect();
                window.scrollTo(0, rect.y - navHeight);
                // console.log(rect);
            }
        }
        // définition de l'attribut du flashbag et du formulaire vérifié ciblé 
        const verifiedForm = m.querySelector('span');
        if (verifiedForm) {
            const verifiedFormAttr = verifiedForm.getAttribute('data-verified-form');
            // console.log(verifiedFormAttr);
            // console.log(modifiedProductId);
            const targetedForm = document.getElementById(verifiedFormAttr);
            if (verifiedForm && verifiedFormAttr) {
                const rect = targetedForm.getBoundingClientRect();
                window.scrollTo(0, rect.y - navHeight);
                // console.log(rect.y - navHeight);
                const matches = document.querySelectorAll("button.h-captcha").forEach(function (el) {
                    // console.log(el.innerHTML);
                    el.innerHTML = "<div class=\"spinner-grow  text-success\" style=\"width: 1rem; height: 1rem;\" role=\"status\"></div>&nbsp;" + el.innerHTML;
                });
            }
        }
    }

    // Permet de scroller jusqu'au produit modifié
    n = document.getElementById("flash-alert-danger");
    // console.log(m);
    if (n) {
        // définition de la hauteur de la nav
        const nav = document.querySelector(".fixed-top");
        navHeight = nav.clientHeight;
        // console.log(navHeight);        
        // définition de l'attribut du flashbag et du formulaire vérifié ciblé 
        const verifiedForm = n.querySelector('span');
        if (verifiedForm) {
            const verifiedFormAttr = verifiedForm.getAttribute('data-verified-form');
            // console.log(modifiedProduct);
            // console.log(modifiedProductId);
            const targetedForm = document.getElementById(verifiedFormAttr);
            if (verifiedForm && verifiedFormAttr) {
                const rect = targetedForm.getBoundingClientRect();
                window.scrollTo(0, rect.y - navHeight);
                // console.log(rect.y - navHeight);
                // const matches = document.querySelectorAll("button.h-captcha").forEach(function (el) {
                //     // console.log(el.innerHTML);
                //     el.innerHTML = "<div class=\"spinner-grow  text-success\" style=\"width: 1rem; height: 1rem;\" role=\"status\"></div>&nbsp;" + el.innerHTML;
                // });
            }
        }
    }


    $("body").on('click', '.toggle-password', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        let input = $("#inputPassword");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });

    $("body").on('click', '.toggle-password-register', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        let input = $("#registration_form_plainPassword");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }

    });




    // A REVOIR POUR GERER LE FAVICON DU REGISTER_FORM !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // const formRegister = document.getElementById("#registration_form");
    // const registerForm = document.querySelector("#registration_form");
    // // console.log(registerForm);
    // error = document.getElementsByClassName("invalid-feedback");
    // // console.log(error);
    // // inputEmail = document.getElementById("registration_form_email");
    // if (error) {
    //     const elMailId = document.getElementById("#errorMail");
    //     errorMailMessage = elMailId.querySelector(".form-error-message");
    //     if (errorMailMessage) {
    //         errorMailMessage.innerText = "Ce compte existe déjà";
    //     }
    //     const elPasswordId = document.getElementById("#errorPassword");
    //     // console.log(elPasswordId);

    //     errorPasswordMessage = elPasswordId.querySelector(".form-error-message");
    //     if (errorPasswordMessage) {
    //         const iconEl = document.getElementById("eyeSvg");
    //         // console.log(iconEl);

    //         if (iconEl.classList.contains('field-icon')) {
    //             // iconEl.classList.remove('field-icon');
    //             iconEl.classList.toggle('field-icon-reg');
    //         }
    //     }
    // }


});