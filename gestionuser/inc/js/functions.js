$(document).ready(function(){
    $('.confirm').on('click',function(){
        return(confirm('Êtes vous certain de vouloir supprimer cet utilisateur ?'));
    });

    if( $('#maModale').length == 1 ){
        $('#maModale').modal('show');
    }

    // message de confirmation => on ne peut pas supprimer l'admin de la plateforme de la bdd externe
    $('.confirmadmin').on('click',function(){
        return(confirm('On ne peut pas supprimer l`\'administrateur'));
    });

    if( $('#maModale').length == 1 ){
        $('#maModale').modal('show');
    }
});


