/***************************************************STYLE***********************************************/
var options = {
  name: 'grid',
  rows: 1,
};
var cy = cytoscape({
  container: document.getElementById('dividedSimulator1'),
  boxSelectionEnabled: false,
  autounselectify: true,
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'width': '1px',
        'height': '1px',
        'content': 'data(name)',
        'color': 'white',
        'font-size': '30px',
      })
    .selector('edge')
      .css({
        'width': '10',
        'color': 'white',
      })
    .selector('.highlightedEdge')
      .css({
        'line-color': '#38CE4D',
        'transition-property': 'line-color',
        'transition-duration': '0.5s'
      })
    .selector('.highlightedNode')
     .css({
       'color': '#C70039',
       'transition-property': 'color',
       'transition-duration': '0.5s'
      })
  ,
  elements: {
    nodes: [],
    edges: []
  },
});

var cy2 = cytoscape({
  container: document.getElementById('dividedSimulator2'),
  boxSelectionEnabled: false,
  autounselectify: true,
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'width': '1px',
        'height': '1px',
        'content': 'data(name)',
        'color': 'white',
        'font-size': '30px',
      })
    .selector('edge')
      .css({
        'width': '10',
        'color': 'white',
      })
    .selector('.highlightedEdge')
      .css({
        'line-color': '#38CE4D',
        'transition-property': 'line-color',
        'transition-duration': '0.5s'
      })
    .selector('.highlightedNode')
     .css({
       'color': '#C70039',
       'transition-property': 'color',
       'transition-duration': '0.5s'
      })
    .selector('.invisibleNode')
     .css({
       'font-size': '1px',
      })
  ,
  elements: {
    nodes: [],
    edges: []
  },
});
cy.layout(options);
cy2.layout(options);
/***************************************************END OF STYLE***********************************************/
let T = "AABAACAADAABAABA";
let P = "AABA";
let phi = [];
let steps = [];

function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function createEdge(from, to, instance){
  let structure = {};
  structure.group          = "edges";
  structure.data           = {};
  structure.data.id        = "f:" + from + "to:" + to;
  structure.data.source    = from;
  structure.data.target    = to;
  instance.add([structure]);
}


function createNode(i, name, instance){
  let structure = {};
  structure.group      = "nodes";
  structure.data       = {};
  structure.data.id    = i;
  structure.data.name  = name;
  instance.add([structure]);
}

async function simulate(){
  console.log(steps);
  for(let i = 0; i < steps.length; i++){
    let textIndex  = steps[i].i;
    let patternIndex = steps[i].j;
    cy.remove(cy.elements("edge"));
    cy2.remove(cy2.elements("edge"));
    let patternWindowLength = patternIndex + 1; 
    if(patternWindowLength > 1){
      for(let j = textIndex - patternIndex; j < textIndex; j++){
        createEdge(j, j + 1 , cy);  
      }
      for(let k = 0; k < patternIndex; k++){
        createEdge(k, k + 1, cy2);   
      } 
      if(patternWindowLength == P.length){
        for(let j = textIndex - patternIndex; j < textIndex; j++){
          cy.$("edge[target = '"+ (j + 1) +"']").addClass('highlightedEdge');
        }
        for(let k = 0; k < patternIndex; k++){
          cy2.$("edge[target = '"+ (k + 1) +"']").addClass('highlightedEdge');
        }  
      }
      await sleep(1000);
    }else{
      cy.$("node[id = '"+textIndex+"']").addClass('highlightedNode');
      cy2.$("node[id = '"+patternIndex+"']").addClass('highlightedNode');
      await sleep(1000);
      cy.$("node[id = '"+textIndex+"']").removeClass('highlightedNode');
      cy2.$("node[id = '"+patternIndex+"']").removeClass('highlightedNode');
    }
  }
}

function createNodeCollection(A){
  let structure = {};
  let ans = [];
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

function init(T, P){
  cy.remove(cy.elements("edge"));
  cy2.remove(cy2.elements("edge"));
  cy.remove(cy.elements("node"));
  cy2.remove(cy2.elements("node"));
  for(let i = 0; i < T.length; i++)
    createNode(i, T[i], cy);
  for(let j = 0; j < P.length; j++)
    createNode(j, P[j], cy2);
  for(let k = 0; k < T.length - P.length; k++){
    createNode(k + T.length, '.', cy2);
    cy2.$("node[id = '"+ (k + T.length) +"']").addClass('invisibleNode');
  }
  cy2.layout(options);
  cy.layout(options);
}
function computePhi(){
  var len = 0;
  var i = 1;
  var M = P.length;
  phi[0] = 0;
  while(i < M){
    if(P[i] == P[len]){
      phi[i++] = ++len;
    }else{
      if(len != 0){
        len = phi[len - 1];
      }else{
        phi[i++] = 0;
      }
    }
  }
}

function kmpSearch(){
  let i = 0;
  let j = 0;
  let N = T.length;
  let M = P.length;
  while (i < N){
    let step = {};
    step.i = i;
    step.j = j;
    if (P[j] == T[i]){
        j++, i++;
    }
    if (j == M){  
        console.log(i - j);
        j = phi[j-1];
    }
    else{
      if (i < N && P[j] != T[i]){
        if (j != 0)
            j = phi[j-1];
        else
            i = i+1;
      }
    }
    steps.push(step);
  }
  simulate();
}
init(T, P);
async function runKMP(){
  T = document.getElementById("stringT").value;
  P = document.getElementById("stringP").value;
  init(T, P);
  computePhi();
  await sleep(1000);
  kmpSearch();  
}