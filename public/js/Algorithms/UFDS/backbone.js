/* Backbone to simulate union - find disjoint set */
/* changeNodeColor(i, background, color)*/
var cy = null;
var elesJson = null;
var edges = null;
var parent = new Array();
var rank = new Array();
var nodesToExplore = new Array();
var edgesToExplore = new Array();
var my_options = {
  name: 'cola',
  fit: true,
  boxSelectionEnabled: false,
  autounselectify: true,
  zoomingEnabled: true,
  maxZoom: 0.8,
};
$(function(){ 
  var elesJson = {
    nodes: [], 
    edges: [],
  };
  cy = cytoscape({
    container: document.getElementById('simulator'),
    option:  my_options,
    layout: {
      name: 'cola',
      fit: true,
    },
    boxSelectionEnabled: false,
    autounselectify: true,
    zoomingEnabled: true,
    maxZoom: 0.8,
    elements: {
            nodes: [
              { data: { id: '0', name: '0' } },
              { data: { id: '1', name: '1' } },
              { data: { id: '2', name: '2' } },
              { data: { id: '3', name: '3' } },
              { data: { id: '4', name: '4' } },
              { data: { id: '5', name: '5' } },
              { data: { id: '6', name: '6' } },
              { data: { id: '7', name: '7' } },
            ],
            edges: [
              { data:{ id: '0' + 'to' + '1', source: '0', target: '1'} },
              { data:{ id: '2' + 'to' + '1', source: '2', target: '1'} },
              { data:{ id: '5' + 'to' + '4', source: '5', target: '4'} },
              { data:{ id: '4' + 'to' + '3', source: '4', target: '3'} },
              { data:{ id: '6' + 'to' + '3', source: '6', target: '3'} },
            ]
          },
         style:[  {
              selector: 'node',
                style: {
                  'background-color': 'rgb(253, 68, 78)',
                  'shape': 'circle',
                  'width':'80', 
                  'border-width': 4,
                  'border-color': 'none',
                  'height':'80',
                  'content': 'data(id)',
                  'color':'white',
                  'text-valign' : 'center',
                  'text-halign' : 'center',
                  'font-size':'20',
                }
              },
          ],
    userPanningEnabled: false,
    ungrabify: false,
  });
  for(var i = 0; i < 11; i++){
    parent[i] = i;
    rank[i] = 0;
    changeNodeColor(i, "white", "black");
    changeNodeBorder(i, 0, "white");
    assignNodeSize(i);
  }
  parent[0] = 1, parent[2] = 1, parent[1] = 1, parent[5] = 4, parent[4] = 3, parent[3] = 3, parent[6] = 3, parent[7] = 7;
  rank[0] = 0, rank[1] = 1, rank[2] = 0, rank[3] = 2, rank[7] = 3, rank[4] = 0, rank[5] = 1, rank[6] = 0, rank[8] = 0, rank[10] = 0, rank[9] = 1;
  changeNodeColor(1, "black", "white");
  changeNodeColor(3, "black", "white");
  changeNodeColor(7, "black", "white");
  cy.layout(my_options);
  cy.on('mouseup', 'node', function (evt) {
    cy.animate({
        zoom: undefined,
        center: {
          eles: cy.filter('')
        }
    }, {
      duration: 500
    });
  });
});
var x = 0;
function agregarVertice(){
  var X = document.getElementById("newNode").value;
  cy.add([{
    data: { id: X }  
  }]);
  cy.layout(my_options);
  $("#simulator").fadeOut(0);
  $("#simulator").fadeIn(1000);
}

function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function resetGraph(){
  for(var i = 0 ; i < 11; i++){
    if(parent[i] == i){
      resetSize(i);
    }else{
      resetColor(i);
      resetSize(i);
    }
  }
}

function resetColor(i){
  changeNodeColor(i, "white", "black");
}

function resetSize(i){
  assignNodeSize(i);
}

function changeNodeColor(i, color, color2){
  cy.$("node[id = '"+i+"']").css({
    'transition-property': 'background-color',
    'transition-duration': '5s',
    'background-color': color,
    'color': color2,
    'border-color': 'transparent',
    'border-width': '0px',    
  });
}

function changeNodeBorder(i, width, color){
  cy.$("node[id = '"+i+"']").css({
    'border-color': color,
    'border-width': width + 'px',    
  });
}

function changeNodeSize(i, plus){
  cy.$("node[id = '"+i+"']").css({
    'width' : cy.$("node[id = '"+i+"']").width() + plus + 'px',
    'height' : cy.$("node[id = '"+i+"']").height() + plus + 'px',
  });
}

function assignNodeSize(i){
  cy.$("node[id = '"+i+"']").css({
    'width' : '55px',
    'height' : '55px',
  });
}

function inicializar(){
  var X = document.getElementById("numberOfNodes").value;
  var collection = cy.elements("node");
  cy.remove(collection);
  cy.layout(my_options);
  $("#simulator").fadeOut(0);
  for(var i = 0; i < X; i++){
    parent[i] = i;
    rank[i] = 0;
    cy.add([{
      data: { id: i , name: i }  
    }]);
    changeNodeColor(i, "black", "white");
  }
  $("#simulator").fadeIn(1000);
  sleep(500).then(() => {
      cy.layout(my_options);
  });
}

async function uneVertices(){
  var X = document.getElementById("from").value;
  var Y = document.getElementById("to").value; 
  nodesToExplore = new Array();
  edgesToExplore = new Array();
  exploreFirstSet = new Array();
  exploreSecondSet = new Array();
  var RX = representante(X);
  for(var i = 0; i < nodesToExplore.length; i++){
    if(i == nodesToExplore.length - 1){
      changeNodeColor(nodesToExplore[i], "orange", "white");
      await sleep(200);
      changeNodeSize(nodesToExplore[i], 30);
    }
    else{
      changeNodeColor(nodesToExplore[i], "white", "orange");
      await sleep(200);
      changeEdgeColor(nodesToExplore[i], "rgb(227,82,75)");
    }
    await sleep(1000);
  }
  for(var i = 0; i < nodesToExplore.length; i++){
    if(nodesToExplore[i] != RX){
      changeNodeColor(nodesToExplore[i], "white", "black");
    }
  }
  for(var i = edgesToExplore.length - 1; i >= 0; i--){
    if(edgesToExplore[i].includes("to" + RX)){
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      changeEdgeColor(nodesToExplore[i], "rgb(150,150,150)");
      await sleep(1000);
    }else{
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      cy.remove(edge);
      await sleep(1000);
    }
  }
  for(var i = nodesToExplore.length - 1; i >= 0; i--){
    if(nodesToExplore[i] != RX){
      agregaArista(nodesToExplore[i], RX);
      await sleep(500);
    }
  }
  await sleep(1000);
  exploreFirstSet = nodesToExplore;
  nodesToExplore = new Array();
  edgesToExplore = new Array();
  var RY = representante(Y);
  for(var i = 0; i < nodesToExplore.length; i++){
    if(i == nodesToExplore.length - 1){
      changeNodeColor(nodesToExplore[i], "orange", "white");
      await sleep(200);
      changeNodeSize(nodesToExplore[i], 30);
    }
    else{
      changeNodeColor(nodesToExplore[i], "white", "orange");
      await sleep(200);
      changeEdgeColor(nodesToExplore[i], "rgb(227,82,75)");
    }
    await sleep(1000);
  }
  for(var i = 0; i < nodesToExplore.length; i++){
    if(nodesToExplore[i] != RY){
      changeNodeColor(nodesToExplore[i], "white", "black");
    }
  }
   for(var i = edgesToExplore.length - 1; i >= 0; i--){
    if(edgesToExplore[i].includes("to" + RY)){
      changeEdgeColor(nodesToExplore[i], "rgb(150,150,150)");
      await sleep(1000);
    }else{
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      cy.remove(edge);
      await sleep(1000);
    }
  }
  for(var i = nodesToExplore.length - 1; i >= 0; i--){
    if(nodesToExplore[i] != RY){
      agregaArista(nodesToExplore[i], RY);
      await sleep(500);
    }
  }
  exploreSecondSet = nodesToExplore;
  nodesToExplore = new Array();
  if(RX != RY){
    for(var i = 0; i < exploreFirstSet.length; i++){
        if(exploreFirstSet[i] != RX){
          changeNodeColor(exploreFirstSet[i], "white", "black");
        }
    }
    for(var i = 0; i < exploreSecondSet.length; i++){
      if(exploreSecondSet[i] != RY){
          changeNodeColor(exploreSecondSet[i], "white", "black");
      }
    }
    if(rank[RX] > rank[RY]){
      parent[RY] = RX;
      agregaArista(RY,RX);
      changeEdgeColor(RY + 'to' + RX);
      await sleep(1000);
      changeNodeColor(RY, "white", "black");
      changeNodeColor(RX, "black", "white");
    }
    else{
      parent[RX] = RY;
      agregaArista(RX, RY);
      changeEdgeColor(RX + 'to' + RY);
      await sleep(1000);      
      if(rank[RX] == rank[RY])
        rank[RY]++;
      await sleep(1000);
      changeNodeColor(RY, "black", "white");
      changeNodeColor(RX, "white", "black");
    }
  }else{
    alert("MISMO CONJUNTO");
  }
  assignNodeSize(RX);
  assignNodeSize(RY);
  //cy.layout(my_options);
  //resetGraph();
  //cy.zoom(1000);
}

async function obtenerR(){
  var X = document.getElementById("representanteX").value;
  nodesToExplore = new Array();
  edgesToExplore = new Array();
  exploreFirstSet = new Array();
  exploreSecondSet = new Array();
  var RX = representante(X);
  for(var i = 0; i < nodesToExplore.length; i++){
    if(i == nodesToExplore.length - 1){
      changeNodeColor(nodesToExplore[i], "orange", "white");
      await sleep(200);
      changeNodeSize(nodesToExplore[i], 30);
    }
    else{
      changeNodeColor(nodesToExplore[i], "white", "orange");
      await sleep(200);
      changeEdgeColor(nodesToExplore[i], "rgb(227,82,75)");
    }
    await sleep(1000);
  }
  for(var i = 0; i < nodesToExplore.length; i++){
    if(nodesToExplore[i] != RX){
      changeNodeColor(nodesToExplore[i], "white", "black");
    }
  }
  for(var i = edgesToExplore.length - 1; i >= 0; i--){
    if(edgesToExplore[i].includes("to" + RX)){
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      changeEdgeColor(nodesToExplore[i], "rgb(150,150,150)");
      await sleep(1000);
    }else{
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      cy.remove(edge);
      await sleep(1000);
    }
  }
  for(var i = nodesToExplore.length - 1; i >= 0; i--){
    if(nodesToExplore[i] != RX){
      agregaArista(nodesToExplore[i], RX);
      await sleep(500);
    }
  }
  await sleep(1000);
  changeNodeColor(RX, "black", "white");
  assignNodeSize(RX);
}

async function mismoConjunto(){
  var X = document.getElementById("sameSetX").value;
  var Y = document.getElementById("sameSetY").value; 
  nodesToExplore = new Array();
  edgesToExplore = new Array();
  exploreFirstSet = new Array();
  exploreSecondSet = new Array();
  var RX = representante(X);
  for(var i = 0; i < nodesToExplore.length; i++){
    if(i == nodesToExplore.length - 1){
      changeNodeColor(nodesToExplore[i], "orange", "white");
      await sleep(200);
      changeNodeSize(nodesToExplore[i], 30);
    }
    else{
      changeNodeColor(nodesToExplore[i], "white", "orange");
      await sleep(200);
      changeEdgeColor(nodesToExplore[i], "rgb(227,82,75)");
    }
    await sleep(1000);
  }
  for(var i = 0; i < nodesToExplore.length; i++){
    if(nodesToExplore[i] != RX){
      changeNodeColor(nodesToExplore[i], "white", "black");
    }
  }
  for(var i = edgesToExplore.length - 1; i >= 0; i--){
    if(edgesToExplore[i].includes("to" + RX)){
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      changeEdgeColor(nodesToExplore[i], "rgb(150,150,150)");
      await sleep(1000);
    }else{
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      cy.remove(edge);
      await sleep(1000);
    }
  }
  for(var i = nodesToExplore.length - 1; i >= 0; i--){
    if(nodesToExplore[i] != RX){
      agregaArista(nodesToExplore[i], RX);
      await sleep(500);
    }
  }
  await sleep(1000);
  exploreFirstSet = nodesToExplore;
  nodesToExplore = new Array();
  edgesToExplore = new Array();
  var RY = representante(Y);
  for(var i = 0; i < nodesToExplore.length; i++){
    if(i == nodesToExplore.length - 1){
      changeNodeColor(nodesToExplore[i], "orange", "white");
      await sleep(200);
      changeNodeSize(nodesToExplore[i], 30);
    }
    else{
      changeNodeColor(nodesToExplore[i], "white", "orange");
      await sleep(200);
      changeEdgeColor(nodesToExplore[i], "rgb(227,82,75)");
    }
    await sleep(1000);
  }
  for(var i = 0; i < nodesToExplore.length; i++){
    if(nodesToExplore[i] != RY){
      changeNodeColor(nodesToExplore[i], "white", "black");
    }
  }
   for(var i = edgesToExplore.length - 1; i >= 0; i--){
    if(edgesToExplore[i].includes("to" + RY)){
      changeEdgeColor(nodesToExplore[i], "rgb(150,150,150)");
      await sleep(1000);
    }else{
      var edge = cy.elements("edge[id = '"+ edgesToExplore[i] +"']");
      cy.remove(edge);
      await sleep(1000);
    }
  }
  for(var i = nodesToExplore.length - 1; i >= 0; i--){
    if(nodesToExplore[i] != RY){
      agregaArista(nodesToExplore[i], RY);
      await sleep(500);
    }
  }
  exploreSecondSet = nodesToExplore;
  nodesToExplore = new Array();
  for(var i = 0; i < exploreFirstSet.length; i++){
    if(exploreFirstSet[i] != RX){
      changeNodeColor(exploreFirstSet[i], "white", "black");
    }
  }
  for(var i = 0; i < exploreSecondSet.length; i++){
    if(exploreSecondSet[i] != RY){
      changeNodeColor(exploreSecondSet[i], "white", "black");
    }
  }
  await sleep(1000);
  changeNodeColor(RY, "black", "white");
  changeNodeColor(RX, "black", "white");
  if(RX != RY){
    alert("DIFERENTE");
  }else{
    alert("MISMO CONJUNTO");
  }
  cy.layout(my_options);
  resetGraph();
  cy.zoom(1000);
}

function representante(X){
  nodesToExplore.push(X);
  edgesToExplore.push(X + "to" + parent[X]);
  if(X == parent[X]){
    return X;
  }
  else{
    parent[X] = representante(parent[X]);
    return parent[X];
  }
}

function agregaArista(from, to){
  cy.add([{
    data: { id: from + 'to' + to,
      source: from,
      target: to
    }
  }]);
}

function removeEdge(from){
  var edge = cy.elements("edge[source = '"+from+"']");
  cy.remove(edge);
}

function changeEdgeColor(id, color){
  cy.$("edge[source = '"+id+"']").style({
    'line-color': color,
  });
}