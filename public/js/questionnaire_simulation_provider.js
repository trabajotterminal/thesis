/***************************************************STYLE***********************************************/
var cy = cytoscape({
    container: document.getElementById('questionnaire_1'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_1').value){
        json = document.getElementById('json_1').value;
    }
    json = json.replace(/'/g, '"');
    cy.add(JSON.parse(json));
    cy.layout({ name: 'grid'}).run();
}, 400);

var cy2 = cytoscape({
    container: document.getElementById('questionnaire_2'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_2').value){
        json = document.getElementById('json_2').value;
    }
    json = json.replace(/'/g, '"');
    cy2.add(JSON.parse(json));
    cy2.layout({ name: 'grid'}).run();
}, 400);

var cy3 = cytoscape({
    container: document.getElementById('questionnaire_3'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_3').value){
        json = document.getElementById('json_3').value;
    }
    json = json.replace(/'/g, '"');
    cy3.add(JSON.parse(json));
    cy3.layout({ name: 'grid'}).run();
}, 400);


var cy4 = cytoscape({
    container: document.getElementById('questionnaire_4'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_4').value){
        json = document.getElementById('json_4').value;
    }
    json = json.replace(/'/g, '"');
    cy4.add(JSON.parse(json));
    cy4.layout({ name: 'grid'}).run();
}, 400);

var cy5 = cytoscape({
    container: document.getElementById('questionnaire_5'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_5').value){
        json = document.getElementById('json_5').value;
    }
    json = json.replace(/'/g, '"');
    cy5.add(JSON.parse(json));
    cy5.layout({ name: 'grid'}).run();
}, 400);

var cy6 = cytoscape({
    container: document.getElementById('questionnaire_6'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_6').value){
        json = document.getElementById('json_6').value;
    }
    json = json.replace(/'/g, '"');
    cy6.add(JSON.parse(json));
    cy6.layout({ name: 'grid'}).run();
}, 400);

var cy7 = cytoscape({
    container: document.getElementById('questionnaire_7'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_7').value){
        json = document.getElementById('json_7').value;
    }
    json = json.replace(/'/g, '"');
    cy7.add(JSON.parse(json));
    cy7.layout({ name: 'grid'}).run();
}, 400);

var cy8 = cytoscape({
    container: document.getElementById('questionnaire_8'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_8').value){
        json = document.getElementById('json_8').value;
    }
    json = json.replace(/'/g, '"');
    cy8.add(JSON.parse(json));
    cy8.layout({ name: 'grid'}).run();
}, 400);


var cy9 = cytoscape({
    container: document.getElementById('questionnaire_9'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_9').value){
        json = document.getElementById('json_9').value;
    }
    json = json.replace(/'/g, '"');
    cy9.add(JSON.parse(json));
    cy9.layout({ name: 'grid'}).run();
}, 400);


var cy10 = cytoscape({
    container: document.getElementById('questionnaire_10'),
    boxSelectionEnabled: false,
    autounselectify: true,
    style: cytoscape.stylesheet()
        .selector('node')
        .css({
            'content': 'data(name)',
            'text-valign': 'center',
            'color': 'white',
            'text-outline-width': 2,
            'text-outline-color': '#888',
            'background-color': '#888'
        })
        .selector(':selected')
        .css({
            'background-color': 'black',
            'line-color': 'black',
            'target-arrow-color': 'black',
            'source-arrow-color': 'black',
            'text-outline-color': 'black'
        })
    ,
});

setTimeout(function(){
    var json = "";
    if(document.getElementById('json_10').value){
        json = document.getElementById('json_10').value;
    }
    json = json.replace(/'/g, '"');
    cy10.add(JSON.parse(json));
    cy10.layout({ name: 'grid'}).run();
}, 400);