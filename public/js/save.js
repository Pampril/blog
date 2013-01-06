function save() 
{
    if($('#publication').attr('checked'))
    {
        var publie = 1;
    }
    else
    {
        var publie = 0;
    }

  jQuery.ajax({
    type: 'post', // Le type de ma requete
    url: '../articles/ajoutarticle', // L'url vers laquelle la requete sera envoyee
    data: {
    titre: $('#titre').text(), // Les donnees que l'on souhaite envoyer au serveur au format JSON
    corps: $('#corps').text(),    
    date :$('#date').val(),
    publication: publie
    }, 
    success: function(data, textStatus, jqXHR) {

    },
    error: function(jqXHR, textStatus, errorThrown) {

  }
  });
  
}

function saveTime(){
    if(timer){
        clearInterval(timer);
    }
    var timer = window.setInterval("save();", 30000);
}

function redirect ()
{
    alert('Article ajouté');
    document.location.href = '';
}