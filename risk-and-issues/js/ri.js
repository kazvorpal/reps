

//   const mapper = (mode == "project") ? "EPSProject_Key" : "MLMProgram_Nm";
const mapper = "RiskAndIssue_Key";
// const key = (mode == "project") ? "EPSProject_Key" : "EPSProject_Key";

const fieldempty = (field) => (document.getElementById(field).value == '');
const isincluded = (filter, field) => {
  console.log(filter, field)
  return ($(filter).val().includes(field));
}

const exporter = () => {
    document.workbook.xlsx.writeBuffer().then((buf) => {
      saveAs(new Blob([buf]), 'ri-' + ((mode == "portfolio") ? "raid-log" : mode) + "-dashboard-" + formatDate(new Date()) + "-" + formattime(new Date()) + '.xlsx');
    });
}

const getuniques = (list, field) => list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index).sort();
const getwholeuniques = (list, field) => list.filter((value, index, self) => Object.values(self).findIndex(v => v[field] === value[field]) === index);
const removenullproperty = (list, field) => list.filter((value) => value[field] != null);

function fixEncodingIssues(text) {
  return text
    .replace(/^\uFEFF|Ã¯ » ¿|Ã¯»¿/g, '')
    .replace(/Ã¯.*¿/g, '')
    .replace(/Ã¢â¬Â¢|Ã¢â¬â¢/g, '•')
    .replace(/Â|Ã/g, ' ')
    .replace(/Ã¢ââ¹|Ã¢â¬â/g, '—');
}
const getuniqueobjects = (list, field) => {
    const objectlist = list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index);
    let returnlist = [];
    for (item in objectlist) {
        if (objectlist[item].MLMProgram_Nm != null) {
            let hold = getribykey(objectlist[item])
            returnlist.push(hold);
        }
    }
    return(returnlist);
}

document.cbrun = false;
const colorboxschtuff = () => {
  $(".miframe").colorbox({
    iframe:true, 
    width:"80%", 
    height:"70%", 
    scrolling:true,
    fixed: true
  });
  $(".callbacks").colorbox({
      onOpen:function(){ alert('onOpen: colorbox is about to open'); },
      onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
      onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
      onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
      onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
  });

  $('.non-retina').colorbox({rel:'group5', transition:'none'})
  $('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
  
  //Example of preserving a JavaScript event for inline calls.
  $("#click").click(function(){ 
      $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
      return false;
  });
  if (!document.cbrun) {
    var originalClose = $.colorbox.close;
    $.colorbox.close = function(){
      if (confirm('You are about to close this window.  Incomplete Risks/Issues will not be saved.')) {
        originalClose();
      }
    };
  }
  document.cbrun = true;
}

// Pure function returns either the string without HTML tags in it, or the non-string intact as it was
const striptags = (html) => 
  typeof html == "string" 
  ? ((div) => {
    div.innerHTML = html;
    return div.textContent;
  })(document.createElement("div")) 
  : html;

