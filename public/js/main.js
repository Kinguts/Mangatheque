// On utilise un call ajax pour l'api 

function getCollections() {
    $.ajax({
        url:'/api/article/liste',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $.each(response, function(data){
                console.log(data)
                var collection = createCollection(data);
                $('#allCollections').append(collection)                
            })
        }
    })
}

// Fonction qui permet de créer le DOM d'une collection

function createCollection(data) {
    var content = '<li>';
    content += '<div class="col-4 bloc_book" id="blocBook">';
    content += '<div class="row mb-2 bloc_interieur">';          
    content += '<div class="row no-gutters rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative article">';
    content += '<div class="col p-4 d-flex flex-column position-static">';
    content += '<h2 class="title_manga">'+data.title+'</h2>';
    content += '<ul class="align">';
    content += ' dans la catégorie '+data.category.title;
    content += '<li><figure class="book">';   
    content += '<ul class="hardcover_front">';
    content += '<li>';
    content += '<img src="/upload/images'+data.featured_image+'" alt="" id="image_new">';
    content += '</li>';
    content += '<li></li>';
    content += '</ul>';       
    content += '<ul class="page_book">';
    content += '<li></li>';
    content += '<li>';								    
    content += '<a href="/collection/'+data.id+'" class="btn btn-primary mangaCollect">Ma collection</a>';
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

$( document ).ready(function() {
    console.log( "ready!" );
    $("#selectCollection").on("change", function(){
        var value = this.value; 
        console.log(value);
    })
    getCollections();
});

