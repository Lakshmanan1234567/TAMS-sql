if(MonthlyCount.legend==0){
    MonthlyCount.push("Loading.....")
}
if(WorkStart.legend==0){
    WorkStart.push(0);
}
if(ActiveWork.legend==0){
    ActiveWork.push(0);
}
if(CompletedWork.legend==0){
    CompletedWork.push(0);
}


function getChartColorsArray(e){if(null!==document.getElementById(e)){var t=document.getElementById(e).getAttribute("data-colors");if(t)return(t=JSON.parse(t)).map(function(e){var t=e.replace(" ","");if(-1===t.indexOf(",")){var r=getComputedStyle(document.documentElement).getPropertyValue(t);return r||t}var a=e.split(",");return 2!=a.length?t:"rgba("+getComputedStyle(document.documentElement).getPropertyValue(a[0])+","+a[1]+")"})}}setTimeout(function(){$("#subscribeModal").modal("show")},2e3);var linechartBasicColors=getChartColorsArray("stacked-column-chart");linechartBasicColors&&(options={chart:{height:1000,type:"bar",stacked:true,toolbar:{show:false},zoom:{enabled:true}},plotOptions:{bar:{horizontal:true,columnWidth:"20%",endingShape:"rounded",borderRadius:10}},dataLabels:{enabled:false},series:[{name:"Start Work",data:WorkStart},{name:"Active Work",data:ActiveWork},{name:"Completed Work",data:CompletedWork}],xaxis:{categories:District},scrollbar:{enabled: true,offsetY: 0,offsetX: 0,height: 5,width: 5,position: 'bottom',track:{background: '#f2f2f2',opacity: 0.6}},colors:linechartBasicColors,legend:{position:"bottom"},fill:{opacity:1}},(chart=new ApexCharts(document.querySelector("#stacked-column-chart"),options)).render());var options,chart,radialbarColors=getChartColorsArray("radialBar-chart");radialbarColors&&(options={chart:{height:200,type:"radialBar",offsetY:-10},plotOptions:{radialBar:{startAngle:-135,endAngle:135,dataLabels:{name:{fontSize:"13px",color:void 0,offsetY:60},value:{offsetY:22,fontSize:"16px",color:void 0,formatter:function(e){return e+"%"}}}}},colors:radialbarColors,fill:{type:"gradient",gradient:{shade:"dark",shadeIntensity:.40,inverseColors:!1,opacityFrom:1,opacityTo:1,stops:[0,50,65,91]}},stroke:{dashArray:4},series:[67],labels:["Start Work"]},(chart=new ApexCharts(document.querySelector("#radialBar-chart"),options)).render());
