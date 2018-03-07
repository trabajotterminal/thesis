/***************************************************STYLE***********************************************/
var options = {
  name: 'grid',
  col: 1,
  row: 2,
};
var cy = cytoscape({
  container: document.getElementById('simulator'),
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
    .selector('edge')
      .css({
        "label": "data(label)",
        "width": 3,
        "line-color": "#ccc"
      })
    .selector('.highlightedNode')
     .css({
       'background-color': '#b3b3ff',
       'transition-property': 'background-color',
       'transition-duration': '0.5s'
      })
    .selector('.highlightedEdge')
      .css({
        'line-color': '#45B39D',
        'transition-property': 'line-color',
        'transition-duration': '0.75s',
      })
    .selector('.redEdge')
      .css({
        'line-color': '#C0392B',
        'transition-property': 'line-color',
        'transition-duration': '0.75s',
      })
  ,
});
/***************************************************END OF STYLE***********************************************/
let edges  = [];
let vertex = []
let parent = [];

function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max)) + 1;
}

function createNodeCollection(N){
  let structure = {};
  let ans = [];
  for(let i = 0; i < N; i++){
    structure = {};
    structure.group      = "nodes";
    structure.data       = {};
    structure.data.id    = i;
    structure.data.name  = i + 1;
    structure.position   = {};
    structure.position.x = getRandomInt(800) - getRandomInt(100) + 100;
    structure.position.y = getRandomInt(300) - getRandomInt(100) + 100;
    vertex.push(i);
    ans.push(structure)
  }
  return ans;
}

function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max)) + 1;
}

function sortEdges(e1, e2){
  return e1.w >= e2.w;
}

function getRepresentant(u){
  if(u === parent[u]){
    return u;
  }
  return getRepresentant(parent[u]);
}

function unite(u, v){
  let pu = getRepresentant(u);
  let pv = getRepresentant(v);
  parent[pu] = pv;
}

function isSameSet(u, v){
  return getRepresentant(u) == getRepresentant(v);
}

async function loadSample(){
  let N = 7;
  var nodes = createNodeCollection(N);
  cy.add(nodes);
  for(let i = 0; i < N; i++)
    parent[i] = i;
  for(let i = 0; i < N; i++){
    for(let j = i * i * i + 1; j < N; j++){
      let weight = getRandomInt(30);
      let edge = {w: weight, u: i, v: j};
      createEdge(i, j, weight);
      edges.push(edge);
    }
  }
  //getMST();  
}

function createNode(X, Y){
  let structure = {};
  structure.group      = "nodes";
  structure.data       = {};
  structure.data.id    = vertex.length;
  structure.data.name  = vertex.length;
  structure.position   = {};
  structure.position.x = X;
  structure.position.y = Y;
  vertex.push(structure.data.id)
  cy.add([structure]);
}


function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function createEdge(from, to, w){
  let structure = {};
  structure.group          = "edges";
  structure.data           = {};
  structure.data.id        = "from:" + from + "to:" + to;
  structure.data.source    = from;
  structure.data.target    = to;
  structure.data.label     = w;
  structure.classes        = 'autorotate';
  cy.add([structure]);
}

async function simulate(){
  
}


function getMST(){
  
}

var simulator = document.getElementById('simulator'); 
  simulator.onclick  = function(e) { 
  var x = e.pageX - this.offsetLeft; 
  var y = e.pageY - this.offsetTop; 
  createNode(x, y);
}

function addEdge(){
  let u = document.getElementById('u').value;
  let v = document.getElementById('v').value;
  let w = document.getElementById('weight').value;
  let edge = {w:w, u:u, v:v};
  edges.push(edge);
  createEdge(u, v, w);
}

async function getMST(){
  let N = vertex.length;
  for(let i = 0; i < N; i++)
    parent[i] = i;
  edges.sort(sortEdges);
  for(let i = 0; i < edges.length; i++){  
    let u = edges[i].u;
    let v = edges[i].v;
    let id = "from:" + u + "to:" + v;
    if(!isSameSet(u,v)){
      unite(u,v);
      console.log(id);
      cy.$("edge[id = '"+ id +"']").addClass('highlightedEdge');
      await sleep(1000);
    }else{
      await sleep(1000);
      cy.$("edge[id = '"+ id +"']").addClass('redEdge');
    }
  }
}

loadSample();
