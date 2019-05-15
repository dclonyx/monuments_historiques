$(document).ready(function() {
    $('#francemap').vectorMap({
        map: 'france_fr',
        hoverOpacity: 0.5,
        hoverColor: false,
        backgroundColor: "#A1887F",
        colors: couleurs,
        borderColor: "#000000",
        selectedColor: "#EC0000",
        enableZoom: true,
        showTooltip: true,
        onRegionClick: function(element, code, region){
            window.location.href = './Source/php/departement.php?dep=' + code;
        }
    });
});