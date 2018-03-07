/***************************************************STYLE***********************************************/
var options = {
  name: 'grid',
  rows: 1, 
  padding: 50,
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
    .selector(':selected')
      .css({
        'background-color': 'black',
        'line-color': 'black',
        'target-arrow-color': 'black',
        'source-arrow-color': 'black',
        'text-outline-color': 'black'
      })
    .selector('.highlightedEdge')
      .css({
        'line-color': '#61bffc',
        'target-arrow-color': '#61bffc',
        'transition-property': 'background-color, line-color, target-arrow-color',
        'transition-duration': '0.5s'
      })
    .selector('.highlightedNode')
      .css({
        'background-color': '#b3b3ff',
        'transition-property': 'background-color',
        'transition-duration': '0.5s'
      })
    .selector('.highlightedNodeFound')
      .css({
        'background-color': '#4d4dff',
        'transition-property': 'background-color',
        'transition-duration': '0.5s'
      })
  ,
  elements: {
    nodes: [
      { data: { id: '0', name: '1'}},
      { data: { id: '1', name: '2'}},
      { data: { id: '2', name: '3'}},
      { data: { id: '3', name: '4'}},
      { data: { id: '4', name: '5'}},
      { data: { id: '5', name: '6'}},
      { data: { id: '6', name: '7'}},
      { data: { id: '7', name: '8'}},
      { data: { id: '8', name: '9'} }

    ],
    edges: [
      { data: { source: '0', target: '1' } },
      { data: { source: '1', target: '2' } },
      { data: { source: '2', target: '3' } },
      { data: { source: '3', target: '4' } },
      { data: { source: '4', target: '5' } },
      { data: { source: '5', target: '6' } },
      { data: { source: '6', target: '7' } },
      { data: { source: '7', target: '8' } },

    ]
  },
});
cy.layout(options);
/***************************************************END OF STYLE***********************************************/
let elements = new Array();
let segments = new Array();
let midPoints = new Array();
let found = 0;
var x_k = 3;
elements.push(1);
elements.push(2);
elements.push(3);
elements.push(4);
elements.push(5);
elements.push(6);
elements.push(7);
elements.push(8);
elements.push(9);

function binarySearch(){
  let l = 0;
  let r = elements.length - 1;
  midPoints = new Array();
  while(l < r){
    const segment = {};
    segment.l = l;
    segment.r = r;
    const m = Math.floor(l + (r - l) / 2);
    midPoints.push(m);
    segments.push(segment);
    if(elements[m] >= x_k)
      r = m;
    else
      l = m + 1;
  }
  found = (elements[l] == x_k ? l : -1);
}

function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

async function simulate(){
  let i = 0;
  await sleep(1000);
  for(let i = 0; i < midPoints.length; i++){
    cy.$("node[id = '"+midPoints[i]+"']").addClass('highlightedNode');
    for(let j = segments[i].l; j < segments[i].r; j++){
      cy.$("edge[source = '"+ j +"']").addClass('highlightedEdge');
    } 
    await sleep(3000);
    cy.$("node[id = '"+midPoints[i]+"']").removeClass('highlightedNode');
    for(let j = segments[i].l; j < segments[i].r; j++){
      cy.$("edge[source = '"+ j +"']").removeClass('highlightedEdge');
    } 
  }
  if(found != -1){
    cy.$("node[id = '"+ found +"']").addClass('highlightedNodeFound'); 
    await sleep(1000);
    cy.$("node[id = '"+ found +"']").removeClass('highlightedNodeFound'); 
  }
}

function createNodeCollection(A){
  let structure = {};
  let ans = [];
  console.log(A);
  for(let i = 0; i < A.length; i++){
    structure = {};
    structure.group     = "nodes";
    structure.data      = {};
    structure.data.id   = i;
    structure.data.name = A[i];
    ans.push(structure);
  }
  return ans;
}

function createEdgeCollection(A){
  let structure = {};
  let ans = [];
  for(let i = 0; i < A.length - 1; i++){
    structure = {};
    structure.group     = "edges";
    structure.data      = {};
    structure.data.id   = "e" + i;
    structure.data.source =  i;
    structure.data.target = i + 1;
    ans.push(structure);
  }
  return ans;
}

function init(){
  elements = document.getElementById('getArray').value.split(',').map(function(item) {
    return parseInt(item, 10);
  });
  elements.sort();
  var collection = cy.elements("node");
  cy.remove(collection);
  var collection = cy.elements("edge");
  cy.remove(collection);
  let nodes = createNodeCollection(elements);
  let edges = createEdgeCollection(elements);
  cy.add(nodes);
  cy.add(edges);
  $("#simulator").fadeIn(1000);
  sleep(500).then(() => {
      cy.layout(options);
  });
}

function runBinarySearch(){
  var value = document.getElementById("elementFound").value;
  x_k = value;
  binarySearch();
  simulate();
}
