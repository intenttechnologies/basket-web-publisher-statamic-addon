const r=(t,s)=>`https://europe-west2-basket-${t}.cloudfunctions.net/${s}`,l=async({environment:t,apiKey:s,urls:n,basketName:o})=>{console.log("saveItems",n);const e=await fetch(r(t,"publisher-basket-save"),{method:"post",headers:new Headers({"Content-Type":"application/json"}),body:JSON.stringify({data:{basketName:o,apiKey:s,urls:n}})}).then(a=>a.json()).then(a=>a.result);return console.log("saveItems result",e),e},c=async({environment:t,userId:s,basketId:n})=>{console.log("getItems",n);const o=await fetch(r(t,"basket-retrieve"),{method:"post",headers:new Headers({"Content-Type":"application/json"}),body:JSON.stringify({data:{userId:s,basketId:n}})}).then(e=>e.json()).then(e=>e.result);return console.log("getItems result",JSON.stringify(o,null,2)),o};export{r as a,c as g,l as s};