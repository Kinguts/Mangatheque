// On utilise un call ajax pour l'api 

function getSeries() {
    $.ajax({
        url: '/api/serie/liste',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            for (let i = 0; i < response.length; i++) {
                var serie = createSerie(response[i]);
                $('#allCollections').append(serie)
            }
        }
    })
}

// Fonction qui permet de créer le DOM d'une serie

function createSerie(data) {
    var content = '<li>';
    content += '<div class="col-4 bloc_book" id="blocBook" style="margin: 5px; height: 88%;" >';
    content += '<div class="row mb-2 bloc_interieur">';
    content += '<div class="row no-gutters rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative article">';
    content += '<div class="col p-4 d-flex flex-column position-static">';
    content += '<h2 class="title_manga">' + data.title + '</h2>';
    content += '<ul class="align">';
    content += '<li><figure class="book">';
    content += '<ul class="hardcover_front">';
    content += '<li>';
    content += '<img src="/uploads/images/featured/' + data.featured_image + '" alt="" id="image_new">';
    content += '</li>';
    content += '<li></li>';
    content += '</ul>';
    content += '<ul class="page_book">';
    content += '<li></li>';
    content += '<li>';
    content += '<a href="/serie/' + data.id + '" class="btn btn-primary mangaCollect" style="margin-top: 60%;">La collection</a>';
    content += '</li>';
    content += '<li></li>';
    content += '<li></li>';
    content += '<li></li>';
    content += '</ul>';
    content += '<ul class="hardcover_back">';
    content += '<li></li>';
    content += '<li></li>';
    content += '</ul>';
    content += '<ul class="book_spine">';
    content += '<li></li>';
    content += '<li></li>';
    content += '</ul>';
    content += '<figcaption>';
    content += '<h3 class="mb-2 chapitre"></h3>';
    content += '<div></div>';
    content += '</figcaption>';
    content += '</figure></li>';
    content += '</ul>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    content += '</div>';
    content += '</li>';

    return content;
}

$(document).ready(function () {
    $("#selectCollection").on("change", function () {
        var value = this.value;
        if (value !== "") {
            $('#selectionSubmit').attr('href', '/serie/' + value + '/update').removeClass("btn-danger").addClass("btn-success");
        } else {
            $("#selectionSubmit").removeClass("btn-success").addClass("btn-danger");
        }
    })
    $("#selectionSubmit").on("click", function (e) {
        if ($(this).hasClass("btn-danger")) {
            e.preventDefault()
            return false
        }
    })
    getSeries();
});

