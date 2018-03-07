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
    .selector('.invisibleEdge')
      .css({
        'display': 'none',
        'transition-property': 'display',
        'transition-duration': '0.1s',
      })
    .selector('.redEdge')
      .css({
        'line-color': '#C0392B',
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
let A  = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32];
let XI = {l: 14, r: 23};
let update = {vFactor: 30, index: 4};
let singlePath = [];
let N = A.length;
let ft = [];
function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function createNode(i, value){
  let structure = {};
  structure.group      = "nodes";
  structure.data       = {};
  structure.data.id    = i;
  structure.data.name  = value;
  cy.add([structure]);
}

function paintUp(i){
  cy.$("node[id = '"+i+"']").data('name', '?');
  while(i <= N){
    let temp = i;
    i += (i & -i);
    if(i <= N){
      createEdge(i, temp);
      console.log("from:: " + i + " to:: "+temp);
    }
  }
}

function paintDown(i){
  while(i){
    let temp = i;
    i -= (i & -i);
    if(i){
      createEdge(i, temp);
      let id = "from:" + i + "to:" +temp;
      console.log(id);
      cy.$("edge[id = '"+id+"']").addClass('invisibleEdge');
    }
  }
}

async function getSumOfSegment(){
  let l = XI.l - 1;
  let r = XI.r;
  let ans = 0;
  singlePath = [];
  while(r){ 
    ans += ft[r];
    let u = r;
    r -= (r & -r);
    let id = "from:" + r + "to:" + u;
    singlePath.push(id);
    cy.$("edge[id = '"+id+"']").removeClass('invisibleEdge');
    cy.$("edge[id = '"+id+"']").addClass('redEdge');
  }
  while(l){ 
    ans += ft[l];
    let u = l;
    l -= (l & -l);
    let id = "from:" + l + "to:" + u;
    singlePath.push(id);
    cy.$("edge[id = '"+id+"']").removeClass('invisibleEdge');
    cy.$("edge[id = '"+id+"']").addClass('highlightedEdge');
  } 
  await sleep(2000);
  console.warn(ans);
  for(let i = 0; i < singlePath.length; i++){
    cy.$("edge[id = '"+singlePath[i]+"']").addClass('invisibleEdge');
    cy.$("edge[id = '"+singlePath[i]+"']").removeClass('highlightedEdge');
  }
}

async function updateElement(){
  let u = update.index;
  let vFactor = update.vFactor;
  singlePath = [];
  while(u <= N){
    singlePath.push(u);
    let aux = u + (u & -u);
    let id = "from:" + aux + "to:" +u;
    cy.$("edge[id = '"+ id +"']").addClass('highlightedEdge');
    ft[u] += vFactor;
    cy.$("node[id = '"+ u +"']").data('name', ft[u].toString());
    u += (u & -u);
    await sleep(10);
  }
  await sleep(1000);
  for(let i = 0; i < singlePath.length; i++){
    cy.$("edge[target = '"+ singlePath[i] +"']").removeClass('highlightedEdge');
  }
}

async function build(){
  for(let i = 1; i <= N; i++){
    paintUp(i);
    ft[i] = 0;
  }
  for(let i = N; i >= 0; i--){
    paintDown(i);
  }
  cy.layout({ name: 'dagre'}).run();
  for(let i = 1; i <= N; i++){
    let u = i;
    let propagatedValue = A[u - 1];
    singlePath = [];
    while(u <= N){
      singlePath.push(u);
      let aux = u + (u & -u);
      let id = "from:" + aux + "to:" +u;
      cy.$("edge[id = '"+ id +"']").addClass('highlightedEdge');
      ft[u] += propagatedValue;
      cy.$("node[id = '"+ u +"']").data('name', ft[u].toString());
      u += (u & -u);
      await sleep(10);
    }
    for(let i = 0; i < singlePath.length; i++){
      await sleep(20);
      cy.$("edge[target = '"+ singlePath[i] +"']").removeClass('highlightedEdge');
    }
  }
}

function loadSample(){
  cy.remove(cy.elements('node'));
  cy.remove(cy.elements('edge'));
  for(let i = 0; i < A.length; i++){
    createNode(i + 1, A[i]);
  }
  build();
}

function createEdge(from, to){
  let structure = {};
  structure.group          = "edges";
  structure.data           = {};
  structure.data.id        = "from:" + from + "to:" + to;
  structure.data.source    = from;
  structure.data.target    = to;
  cy.add([structure]);
}

loadSample();

