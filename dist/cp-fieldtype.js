console.log(">> cp!");Statamic.$hooks.on("entry.saving",(o,e,c)=>{console.log(c),o()});
