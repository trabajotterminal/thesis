/***************************************************STYLE***********************************************/
var cy = cytoscape({
  container: document.getElementById('simulator'),
  layout: {
    name: 'dagre'
  },
  boxSelectionEnabled: false,
  autounselectify: true,
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'content': 'data(name)',
        'text-valign': 'center',
        'background-color': '#11479e'
      })
    .selector('edge')
      .css({
        'curve-style': 'bezier',
        'width': 2,
        'line-color': '#9dbaea',
        'target-arrow-color': '#9dbaea'
      })
    .selector('.highlightedNode')
     .css({
       'background-color': '#b3b3ff',
       'transition-property': 'background-color',
       'transition-duration': '0.5s'
      })
    .selector('.wrongNode')
     .css({
       'background-color': '#C0392B',
       'transition-property': 'background-color',
       'transition-duration': '0.5s'
      })
    .selector('.rightNode')
     .css({
       'background-color': '#45B39D',
       'transition-property': 'background-color',
       'transition-duration': '0.5s'
      })
    .selector('.highlightedEdge')
      .css({
        'line-color': '#45B39D',
        'transition-property': 'line-color',
        'transition-duration': '0.75s',
      })
  ,
  elements: {
    nodes: [],
    edges: []
  },
});
/***************************************************END OF STYLE***********************************************/
let A = [1,2,4,5,6,7,8];
let G = {}; // Grafica
let S = []; // Segmento asociado a cada nodo p.
let st = new Array();
let visited = [];
let solved  = [];
let pending = [];
let Xi = {l: 3, r: 4};
let visitedNodes = [];
async function build(p, l, r, k){
  visited[p] = 0;
  solved[p]  = 0;
  G[p] = [];
  if(l == r){
    st[p] = A[l];
    S[p] = {l: l, r: l};
    createNode(p, 0);
  }else{
    build(p * 2, l, Math.floor((l + r) / 2));
    build(p * 2 + 1 , Math.floor((l + r) / 2) + 1, r);
    let p1 = st[p * 2];
    let p2 = st[p * 2 + 1];
    st[p] = 0;
    S[p] = {l: l, r: r};
    G[p].push(p * 2);
    G[p].push(p * 2 + 1);
    createNode(p, 0);
    createEdge(p, 2 * p);
    createEdge(p, 2 * p + 1);
  }
}

function createNode(i, value){
  let structure = {};
  structure.group      = "nodes";
  structure.data       = {};
  structure.data.id    = i;
  structure.data.name  = value;
  cy.add([structure]);
}

async function shouldOperandBeApplied(){
  let N = pending.length;
  for(let i = N - 1; i >= 0; i--){
    let leftChild = pending[i] * 2;
    let rightChild = pending[i] * 2 + 1;
    if(solved[leftChild] && solved[rightChild]){
      solved[pending[i]] = 1;
      st[pending[i]] = st[leftChild] + st[rightChild];
      cy.$("node[id = '"+pending[i]+"']").data('name', st[pending[i]]);
      pending.pop();
      N--;
    }
  }
}

async function plotBuilding(){
  let stack = new Array();
  visitedNodes = new Array();
  stack.push(1);
  while(stack.length != 0){
    let u = stack.pop();
    shouldOperandBeApplied();
    visited[u] = 1;
    cy.$("edge[target = '"+ u +"']").addClass('highlightedEdge');
    visitedNodes.push(u);
    await sleep(1000);
    if(G[u].length == 0){
      solved[u] = 1;
      st[u] = A[S[u].l];
      cy.$("node[id = '"+u+"']").data('name', st[u]);
      await sleep(200);
    }else{
      pending.push(u);
      for(let i = G[u].length - 1; i >= 0; i--){
        let v = G[u][i];
        if(visited[v] == 0){
          stack.push(v);
        }
      }
    }
  }
  shouldOperandBeApplied();
  for(let i = 0; i < visitedNodes.length; i++){
    cy.$("edge[target = '"+ visitedNodes[i] +"']").removeClass('highlightedEdge');
  }
}

function loadSample(){
  cy.remove(cy.elements('node'));
  cy.remove(cy.elements('edge'));
  build(1, 0, A.length - 1);
  cy.layout({ name: 'dagre'}).run();
  plotBuilding();
}

function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function createEdge(from, to){
  let structure = {};
  structure.group          = "edges";
  structure.data           = {};
  structure.data.id        = "f:" + from + "to:" + to;
  structure.data.source    = from;
  structure.data.target    = to;
  cy.add([structure]);
}

async function simulate(){
  
}

async function getSumOfSegment(){
  stack = new Array();
  visitedNodes = new Array();
  let answer = 0;
  stack.push(1);
  pending = new Array();
  for(let i = 0; i < 4 * A.length; i++){
    visited[i] = 0;
    solved[i]  = 0;
  }
  while(stack.length != 0){
    let u = stack.pop();
    visited[u] = 1;
    visitedNodes.push(u);
    await sleep(1000);
    if(S[u].l > Xi.r || S[u].r < Xi.l){
      solved[u] = 1;
      answer += 0;
    }else{
      if(S[u].l >= Xi.l && S[u].r <= Xi.r){
        cy.$("edge[target = '"+ u +"']").addClass('highlightedEdge');
        cy.$("node[id = '"+ u +"']").addClass('rightNode');
        solved[u] = 1;
        answer += st[u];
      }else{
        cy.$("edge[target = '"+ u +"']").addClass('highlightedEdge');
        for(let i = G[u].length - 1; i >= 0; i--){
          let v = G[u][i];
          if(visited[v] == 0){
            stack.push(v);
          }
        }
      }
    }
  }
  await sleep(1000);
  for(let i = 0; i < visitedNodes.length; i++){
    cy.$("edge[target = '"+ visitedNodes[i] +"']").removeClass('highlightedEdge');
    cy.$("node[id = '"+ visitedNodes[i] +"']").removeClass('rightNode');
  }
  console.warn("Xi value " + answer);
}

async function updateElement(){
  Xi = {l: 2, r: 2};
  V  = 1;
  stack = new Array();
  visitedNodes = new Array();
  stack.push(1);
  pending = new Array();
  for(let i = 0; i < 4 * A.length; i++){
    visited[i] = 0;
    solved[i]  = 0;
  }
  while(stack.length != 0){
    let u = stack.pop();
    visited[u] = 1;
    visitedNodes.push(u);
    shouldOperandBeApplied();
    await sleep(1000);
    if(S[u].l > Xi.r || S[u].r < Xi.l){
      solved[u] = 1;
    }else{
      if(S[u].l >= Xi.l && S[u].r <= Xi.r){
        cy.$("edge[target = '"+ u +"']").addClass('highlightedEdge');
        solved[u] = 1;
        st[u] = V;
        cy.$("node[id = '"+ u +"']").addClass('rightNode');
        cy.$("node[id = '"+u+"']").data('name', st[u]);
      }else{
        cy.$("edge[target = '"+ u +"']").addClass('highlightedEdge');
        pending.push(u);
        for(let i = G[u].length - 1; i >= 0; i--){
          let v = G[u][i];
          if(visited[v] == 0){
            stack.push(v);
          }
        }
      }
    }
  }
  shouldOperandBeApplied();
  for(let i = 0; i < visitedNodes.length; i++){
    cy.$("edge[target = '"+ visitedNodes[i] +"']").removeClass('highlightedEdge');
    cy.$("node[id = '"+ visitedNodes[i] +"']").removeClass('rightNode');
  }
}
loadSample();
