$(document).ready(function() {
    var availableTags = [{value:"teoriaConvex.html", label:"Cerco Convexo"}, {value:"busquedaBinaria.html", label:"Búsqueda Binaria"}, {value:"teoriaKMP.html", label:"Knuth Morris y Pratt (KMP)"}, {value:"teoriaFenwickTree.html", label:"Árbol binario indexado"}, {value:"teoriaTrie.html", label:"Trie"} ];
    
  $("#dsearch").autocomplete({
    source: availableTags,
    select: function (event, ui) {
        event.preventDefault();
        $("#dsearch").val(ui.item.label);
        var elem = $(event.originalEvent.toElement);
        $('#dsearch').val(ui.item.label);
        if (elem.hasClass('ac-item-a')) {
            var url = elem.attr('data-url');
            event.preventDefault();
            window.location.replace(url);
        }
    }, focus: function(event, ui){
        event.preventDefault();
        $("#dsearch").val(ui.item.label);
    }
  }).data("ui-autocomplete")._renderItem = function (ul, item) {
    return $("<li></li>")
        .data("item.autocomplete", item)
        .append('<a>' + item.label + '<span class="ac-item-a" data-url='+item.value+'>(Ver Teoria)</span><span class="ac-item-a" data-url="Simulacion.html">(Simulación)</span></a>')
        .appendTo(ul);
  };
    $(".textoTop").css({"position":"relative","opacity": 0, "top":"+=100"});
    $(".right-move").css({"position":"relative","opacity": 0, "right":"-=100"});
    $(".left-move").css({"position":"relative","opacity": 0, "left":"-=100"});
    $(".textoTop").animate({top:0, opacity:1}, { duration: 1300, queue: false});
    $(".left-move").animate({left:0, opacity:1}, { duration: 1300, queue: false});
    $(".right-move").animate({right:0, opacity:1}, { duration: 1300, queue: false });
   
  });