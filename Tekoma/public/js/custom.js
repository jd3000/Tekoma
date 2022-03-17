// Permet de fermer les flashbags après 10 s
setTimeout(function () {
    $(".btn-close").trigger('click');
}, 10000);

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

    // permet d'utiliser l'atribut data-href
    $(".table-row").click(function () {
        window.document.location = $(this).data("href");
    });

    $('#sortTable').DataTable({
        "order": [[5, "asc"]],
        searching: false,
        paging: false,
        info: false
    });

    // permet de rechercher des valeurs dans la gestion des commandes partie admin, d'afficher l'icone d'effacer l'input et d'afficher le message si aucun résultât 
    $("#myInput").on("keyup blur", function () {
        let value = $(this).val().toLowerCase();
        let compare = 0;
        $("#myTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        let tableRows = $("#myTable tr");
        let compareNumber = tableRows.length;
        for (let index = 0; index < tableRows.length; index++) {
            // console.log(tableRows[index].style.display);
            if (tableRows[index].style.display == "none") {
                compare += 1;
            }
        }
        // console.log(compare);
        // console.log(compareNumber);
        let noResult = document.getElementById("noResult");
        let iconResult = document.getElementById("icon-result");
        let iconNoResult = document.getElementById("icon-no-result");
        let messageResult = document.getElementById("message-result");
        let messRes = compareNumber - compare;
        let input = $("#myInput");

        if (messRes > 1) {
            stringMessRes = 'résultats';
        } else {
            stringMessRes = 'résultat';
        }
        if (compare == 0 && input.val() == "") {
            // console.log(compareNumber + " commandes");
            messageResult.innerText = compareNumber + " commandes au total"
            messageResult.className = "text-end";
            noResult.innerHTML = "";
            iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-result text-success";
            iconNoResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-no-result text-danger";

        } else if (compare == 0 && input.val() != "") {
            // console.log(compareNumber + " commandes");
            messageResult.innerText = compareNumber + " commandes au total"
            messageResult.className = "text-end text-success";
            noResult.innerHTML = "";
            iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 field-icon-result text-success";
            iconNoResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-no-result text-danger";
            $("body").on('click', '#icon-result', function () {
                if (input.val() != "") {
                    input.val('');
                    input.blur();
                    iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-result text-success";
                }
            });

        } else if (compare > 0 && compare < compareNumber) {
            // console.log(compareNumber - compare + " " + stringMessRes);
            messageResult.innerText = compareNumber - compare + " " + stringMessRes;
            messageResult.className = "text-end text-success";
            iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 field-icon-result text-success";
            iconNoResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-no-result text-danger";
            noResult.innerHTML = "";
            $("body").on('click', '#icon-result', function () {
                // let input = $("#myInput");
                if (input.val() != "") {
                    input.val('');
                    input.blur();
                    iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-result text-success";
                }
            });

        } else if (compare == compareNumber) {
            // console.log("Auccun résultat");
            messageResult.innerText = "Auccun résultat";
            messageResult.className = "text-end text-danger";
            noResult.innerHTML = "<div class=\"no-result p-4\">Aucune commande ne correspond à la recherche</div>";
            // console.log(iconNoResult.className.baseVal);
            iconNoResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 field-icon-no-result text-danger";
            iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-result text-success";
            $("body").on('click', '#icon-no-result', function () {
                // let input = $("#myInput");
                if (input.val() != "") {
                    input.val('');
                    input.blur();
                    noResult.innerHTML = "";
                    iconNoResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-no-result text-danger";
                    iconResult.className.baseVal = "svg-inline--fa fa-times fa-w-11 d-none field-icon-result text-success";

                }
            });
        }

    });

    function copyToClipboard(text, el) {

        let elOriginalText = el.attr('data-original-title');


        let copyTextArea = document.createElement("textarea");
        copyTextArea.value = text;
        document.body.appendChild(copyTextArea);
        copyTextArea.select();
        try {
            let successful = document.execCommand('copy');
            let msg = successful ? 'Copied!' : 'Whoops, not copied!';
            el.attr('data-original-title', msg).tooltip('show');
        } catch (err) {
            console.log('Oops, unable to copy');
        }
        document.body.removeChild(copyTextArea);
        el.attr('data-original-title', elOriginalText);

    }

    $(document).ready(function () {
        // Initialize
        // ---------------------------------------------------------------------

        // Tooltips
        // Requires Bootstrap 3 for functionality
        $('.js-tooltip').tooltip();

        // Copy to clipboard
        // Grab any text in the attribute 'data-copy' and pass it to the 
        // copy function
        $('.js-copy').click(function () {
            var text = $(this).attr('data-copy');
            var el = $(this);
            copyToClipboard(text, el);
        });
    });



    // A REVOIR POUR GERER LE FAVICON DU REGISTER_FORM !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // const formRegister = document.getElementById("#registration_form");
    // const registerForm = document.querySelector("#registration_form");
    // console.log(registerForm);
    error = document.getElementsByClassName("invalid-feedback");
    // console.log(error);
    // inputEmail = document.getElementById("registration_form_email");
    if (error) {
        // const elMailId = document.getElementById("#errorMail");
        // if (elMailId) {
        //     errorMailMessage = elMailId.querySelector(".form-error-message");
        //     if (errorMailMessage) {
        //         errorMailMessage.innerText = "Ce compte existe déjà";
        //     }

        // }

        const elPasswordId = document.getElementById("#errorPassword");
        // console.log(elPasswordId);

        if (elPasswordId) {
            errorPasswordMessage = elPasswordId.querySelector(".form-error-message");
            if (errorPasswordMessage) {
                const iconEl = document.getElementById("eyeSvg");
                // console.log(iconEl);

                if (iconEl.classList.contains('field-icon')) {
                    // iconEl.classList.remove('field-icon');
                    iconEl.classList.toggle('field-icon-reg');
                }
            }

        }

    }

    let divs = document.getElementsByClassName("grid-item");
    // console.log(divs);
    if (divs) {
        for (const key in divs) {
            if (Object.hasOwnProperty.call(divs, key)) {
                divs[key].style.webkitTransform = 'rotate(' + key * 65 + 'deg)';
                divs[key].style.mozTransform = 'rotate(' + key * 65 + 'deg)';
                divs[key].style.msTransform = 'rotate(' + key * 65 + 'deg)';
                divs[key].style.oTransform = 'rotate(' + key * 65 + 'deg)';
                divs[key].style.transform = 'rotate(' + key * 65 + 'deg)';
            }

        }

    }


});