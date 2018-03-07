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
    .selector('.highlightedNode')
     .css({
       'background-color': '#b3b3ff',
       'transition-property': 'background-color',
       'transition-duration': '0.5s'
      })
    .selector('.hiddenEdge')
      .css({
        'visibility': 'hidden',
        'transition-property': 'visibility',
        'transition-duration': '0.5s'
      })
    .selector('.edgeInsideConvex')
      .css({
        'line-color': '#45B39D',
        'transition-property': 'line-color',
        'transition-duration': '0.5s'
      })
    .selector('.edgeOutsideConvex')
      .css({
        'line-color': '#C0392B',
        'transition-property': 'line-color',
        'transition-duration': '0.5s'
      })
  ,
});
//cy.layout(options);
/***************************************************END OF STYLE***********************************************/
let points = new Array();
let orderedPoints = new Array();
let pivot  = {x: 0, y: 0};
let steps = [];
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
    points.push({x: structure.position.x, y: structure.position.y});
    ans.push(structure)
  }
  return ans;
}

function createNode(X, Y){
  let structure = {};
  structure.group      = "nodes";
  structure.data       = {};
  structure.data.id    = points.length + 1;
  structure.data.name  = points.length + 1;
  structure.position   = {};
  structure.position.x = X;
  structure.position.y = Y;
  points.push({x: X, y: Y, id: structure.data.id});
  cy.add([structure]);
}

function loadSample(){
  var nodes = createNodeCollection(0);
  cy.add(nodes);
  var simulator = document.getElementById('simulator'); 
  simulator.onclick  = function(e) { 
    var x = e.pageX - this.offsetLeft; 
    var y = e.pageY - this.offsetTop; 
    createNode(x, y);
  }
}

function sleep(time){
  return new Promise((resolve) => setTimeout(resolve, time));
}

function distance(P, Q){
    return (P.x - Q.x) * (P.x - Q.x) + (P.y - Q.y) * (P.y - Q.y);
}
 

function orientation(P, Q, R){
    let ans = (Q.y - P.y) * (R.x - Q.x) - (Q.x - P.x) * (R.y - Q.y);
    if (ans == 0) return 0; 
    return (ans > 0) ? 1 : 2; 
}

function compare(P1, P2){
   let sign = orientation(pivot, P1, P2);
   if (sign == 0)
     return (distance(pivot, P2) >= distance(pivot, P1)) ? -1 : 1;
   return (sign == 2) ? -1: 1;
}

function getPivot(P){
  // we compare the coordinates this way because the axes are swapped. 
  let index = 0;
  for (i = 1; i < points.length; i++)
    if (P[i].y < P[index].y || (P[i].y == P[index].y && P[i].x > P[index].x))
      index = i;
  let backup = points[0];
  points[0] = points[index];
  points[index] = backup;
  return points[0];
}

function cross(a, b) { 
  return a.x * b.y - a.y * b.x; 
}

function ccw(p, q, r) {
  return cross(toVec(p, q), toVec(p, r)) > 0; 
}

function toVec(a, b) {
  return {x: b.x - a.x, y: b.y - a.y};
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
  cy.$("node[id = '"+pivot.id+"']").addClass('highlightedNode');
  await sleep(1000);
  for(let i = 1; i < orderedPoints.length; i++){
    createEdge(pivot.id, orderedPoints[i].id);
    await sleep(500);
    cy.$("edge[target = '"+ orderedPoints[i].id +"']").addClass('hiddenEdge');
    var collection = cy.elements("edge");
    cy.remove(collection);
  }
  cy.$("node[id = '"+pivot.id+"']").removeClass('highlightedNode');
  await sleep(500);
  createEdge(orderedPoints[orderedPoints.length - 1].id, orderedPoints[0].id);
  cy.$("edge[target = '"+ orderedPoints[0].id +"']").addClass('edgeInsideConvex');
  await sleep(500);
  createEdge(orderedPoints[0].id, orderedPoints[1].id);
  cy.$("edge[target = '"+ orderedPoints[1].id +"']").addClass('edgeInsideConvex');
  await sleep(500);
  for(let i = 0; i < steps.length; i++){
    let N = steps[i].stack.length;
    let u = steps[i].stack[N - 1].id;
    let v = steps[i].to.id;
    let status = steps[i].status;
    createEdge(u, v);
    if(status){
      await sleep(500);
      cy.$("edge[target = '"+ v +"']").addClass('edgeInsideConvex');
    }else{
      let vv;
      await sleep(500);
      cy.$("edge[target = '"+ v +"']").addClass('edgeOutsideConvex');
      vv = v;
      v = u;
      u = steps[i].stack[N - 2].id;
      await sleep(500);
      cy.$("edge[target = '"+ v +"']").addClass('edgeOutsideConvex');
      var collection = cy.elements("edge[target = '"+ vv +"']");
      cy.remove(collection);
      await sleep(500);
      var collection = cy.elements("edge[target = '"+ v +"']");
      cy.remove(collection);

    } 
  }
}


function getConvexHull(){
  var simulatorHeight = document.getElementById('simulator').clientHeight; 
  var elements = cy.elements("edge");
  cy.remove(elements);
  for(let i = 0; i < points.length; i++){
    points[i].y -= simulatorHeight
    if(points[i].y < 0){
      points[i].y *= -1;
    }
  }
  pivot = getPivot(points);
  orderedPoints = new Array();
  orderedPoints = points.slice(1);
  orderedPoints.sort(compare);
  orderedPoints.unshift(pivot);
  let N = orderedPoints.length;
  let i = 2;
  let stack = [];
  stack.push(orderedPoints[N - 1], orderedPoints[0], orderedPoints[1]);
  while(i < N){
    let j = stack.length - 1;
    let step = {};
    step.to = orderedPoints[i];
    step.stack = stack.slice(0);
    if(ccw(stack[j - 1], stack[j], orderedPoints[i])){
      stack.push(orderedPoints[i++]);
      step.status = true;
    }else{
      stack.pop();
      step.status = false;
    }
    steps.push(step);
  }
  console.log(steps);
  simulate();
}

loadSample();
