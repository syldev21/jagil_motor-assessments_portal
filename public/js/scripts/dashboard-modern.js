var a=new Chartist.Pie("#current-balance-donut-chart",{labels:[1,2],series:[{meta:"Completed",value:80},{meta:"Remaining",value:20}]},{donut:!0,donutWidth:8,showLabel:!1,plugins:[Chartist.plugins.tooltip({class:"current-balance-tooltip",appendToBody:!0}),Chartist.plugins.fillDonut({items:[{content:'<p class="small">Balance</p><h5 class="mt-0 mb-0">$ 10k</h5>'}]})]}),r=new Chartist.Line("#total-transaction-line-chart",{series:[[3,10,4,20,7,45,5,35,20,48,30,50]]},{chartPadding:0,axisX:{showLabel:!0,showGrid:!1},axisY:{showLabel:!0,showGrid:!0,low:0,high:50,scaleMinSpace:40},lineSmooth:Chartist.Interpolation.simple({divisor:2}),plugins:[Chartist.plugins.tooltip({class:"total-transaction-tooltip",appendToBody:!0})],fullWidth:!0});r.on("created",function(e){var t=e.svg.querySelector("defs")||e.svg.elem("defs");return t.elem("linearGradient",{id:"lineLinearStats",x1:0,y1:0,x2:1,y2:0}).elem("stop",{offset:"0%","stop-color":"rgba(255, 82, 249, 0.1)"}).parent().elem("stop",{offset:"10%","stop-color":"rgba(255, 82, 249, 1)"}).parent().elem("stop",{offset:"30%","stop-color":"rgba(255, 82, 249, 1)"}).parent().elem("stop",{offset:"95%","stop-color":"rgba(133, 3, 168, 1)"}).parent().elem("stop",{offset:"100%","stop-color":"rgba(133, 3, 168, 0.1)"}),t}).on("draw",function(e){if("point"===e.type){var t=new Chartist.Svg("circle",{cx:e.x,cy:e.y,"ct:value":e.value.y,r:5,class:35===e.value.y?"ct-point ct-point-circle":"ct-point ct-point-circle-transperent"});e.element.replace(t)}});var s=new Chartist.Bar("#user-statistics-bar-chart",{labels:["B1","B2","B3","B4","B5","B6"],series:[[4e3,8e3,12e3,14e3,12e3,18e3],[5e3,1e4,13e3,12e3,11e3,15e3]]},{stackBars:!0,chartPadding:0,axisX:{showGrid:!1},axisY:{showGrid:!1,labelInterpolationFnc:function(e){return e/1e3+"k"},scaleMinSpace:50},plugins:[Chartist.plugins.tooltip({class:"user-statistics-tooltip",appendToBody:!0})]},[["screen and (min-width: 800px)",{stackBars:!1,seriesBarDistance:10}],["screen and (min-width: 1000px)",{reverseData:!1,horizontalBars:!1,seriesBarDistance:15}]]);s.on("draw",function(e){"bar"===e.type&&(e.element.attr({style:"stroke-width: 12px",x1:e.x1+.001}),e.group.append(new Chartist.Svg("circle",{cx:e.x2,cy:e.y2,r:6},"ct-slice-pie")))}),s.on("created",function(e){var t=e.svg.querySelector("defs")||e.svg.elem("defs");return t.elem("linearGradient",{id:"barGradient1",x1:0,y1:0,x2:0,y2:1}).elem("stop",{offset:0,"stop-color":"rgba(255,75,172,1)"}).parent().elem("stop",{offset:1,"stop-color":"rgba(255,75,172, 0.6)"}),t.elem("linearGradient",{id:"barGradient2",x1:0,y1:0,x2:0,y2:1}).elem("stop",{offset:0,"stop-color":"rgba(129,51,255,1)"}).parent().elem("stop",{offset:1,"stop-color":"rgba(129,51,255, 0.6)"}),t});var i=new Chartist.Bar("#conversion-ration-bar-chart",{labels:["Q1"],series:[[55e3],[35e3],[1e4]]},{stackBars:!0,chartPadding:{top:0,right:50,bottom:0,left:0},axisX:{showLabel:!1,showGrid:!1},axisY:{showGrid:!1,labelInterpolationFnc:function(e){return e/1e3+"k"}},plugins:[Chartist.plugins.tooltip({class:"user-statistics-tooltip",appendToBody:!0})]});i.on("draw",function(e){"bar"===e.type&&(e.element.attr({style:"stroke-width: 40px",x1:e.x1+.001}),e.group.append(new Chartist.Svg("circle",{cx:e.x2,cy:e.y2})))}),i.on("created",function(e){var t=e.svg.querySelector("defs")||e.svg.elem("defs");return t.elem("linearGradient",{id:"barGradient1",x1:0,y1:0,x2:0,y2:1}).elem("stop",{offset:0,"stop-color":"rgba(129,51,255,1)"}).parent().elem("stop",{offset:1,"stop-color":"rgba(129,51,255, 0.6)"}),t.elem("linearGradient",{id:"barGradient2",x1:0,y1:0,x2:0,y2:1}).elem("stop",{offset:0,"stop-color":"rgba(255,75,172,1)"}).parent().elem("stop",{offset:1,"stop-color":"rgba(255,75,172, 0.6)"}),t});var n=t.getElementById("custom-line-chart-sample-three").getContext("2d"),l=n.createLinearGradient(500,0,0,200);l.addColorStop(0,"#8133ff"),l.addColorStop(1,"#ff4bac");var p=n.createLinearGradient(500,0,0,200);p.addColorStop(0,"#8133ff"),p.addColorStop(1,"#ff4bac");new Chart(n,{type:"line",data:{labels:["January","February","March","April","May","June"],datasets:[{label:"My Second dataset",borderColor:l,pointColor:"#fff",pointBorderColor:l,pointBackgroundColor:"#fff",pointHoverBackgroundColor:l,pointHoverBorderColor:l,pointRadius:4,pointBorderWidth:1,pointHoverRadius:4,pointHoverBorderWidth:1,fill:!0,backgroundColor:p,borderWidth:1,data:[24,18,20,30,40,43]}]},options:{responsive:!0,maintainAspectRatio:!0,datasetStrokeWidth:3,pointDotStrokeWidth:4,tooltipFillColor:"rgba(0,0,0,0.6)",legend:{display:!1,position:"bottom"},hover:{mode:"label"},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]},title:{display:!1,fontColor:"#FFF",fullWidth:!1,fontSize:40,text:"82%"}}});o(".logo-wrapper .navbar-toggler").on("click",function(){setTimeout(function(){a.update(),r.update(),s.update(),i.update()},200)})}(window,document,jQuery);
