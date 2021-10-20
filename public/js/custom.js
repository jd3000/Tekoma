// Permet de fermer les flashbags après 3 s
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

// admin / update.html.twig creation
$(document).ready(function () {
    let uploadPreview = document.getElementById("uploadPreview");
    // console.log(uploadPreview);
    $("#update_creation_img").on('change', function () {
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
                        image_holder.className === "imgProduct p-4";
                        $("<img />", {
                            "src": e.target.result,
                            "class": "card-img"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }
                uploadPreview.className = "col-12 col-md-4 text-center imgProduct p-4";
            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Pls select only images");
        }
    });
});


// admin / new.html.twig creation
$(document).ready(function () {
    let uploadPreview = document.getElementById("uploadPreview");
    // console.log(uploadPreview);
    $("#add_creation_img").on('change', function () {
        //Get count of selected files
        let countFiles = $(this)[0].files.length;
        // console.log(countFiles);
        let imgPath = $(this)[0].value;
        // console.log(imgPath);
        let extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        // console.log(extn);
        let image_holder = $("#image-holder-new");
        // console.log(image_holder);
        image_holder.empty();
        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof (FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (let i = 0; i < countFiles; i++) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        image_holder.className === "imgProduct p-4";
                        $("<img />", {
                            "src": e.target.result,
                            "class": "card-img"
                        }).appendTo(image_holder);
                    }
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }
                uploadPreview.className = "col-12 col-md-4 text-center imgProduct p-4";
            } else {
                alert("This browser does not support FileReader.");
            }
        } else {
            alert("Pls select only images");
        }
    });
});


