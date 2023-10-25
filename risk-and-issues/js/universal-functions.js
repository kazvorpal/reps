
var debugmode = false;
const debug = (...params) => (debugmode) && console.log(...params);


const sortby = (list, property) => {
    rv = list.sort((a, b) => {
      return ((a[property] > b[property]) ? 1 : ((a[property] < b[property]) ? -1 : 0));
    });
    return rv;
  }

  const isempty = (target) => ([undefined, null, "null", ""].includes(target));
  const capitalize = (target) => target.charAt(0).toUpperCase() + target.slice(1);
  // Sanitize a string
  const makesafe = (target) => (target in ["null", null]) ? "makesafedatamissing" : target.replace(/[.\s&]/g,'');
  const padTo2Digits = (num) => num.toString().padStart(2, '0');
  const padder = (target, character, size) => character.repeat(target.toString().length) + target.toString();
  const splitdate = (datestring) => datestring.split(" - ");
  const makedate = (dateobject) => dateobject.getFullYear() + "-" + (dateobject.getMonth()+1) + "-" + dateobject.getDate();
  
  
  const makeelement = (options = {}) => {
    const defaults = { a: "", c: "", d: "", e: "", i: "", j: undefined, l: [], m: "", n: "", s: "", t: "", v: "", w: "", y: "", href: undefined, style: undefined };
    const o = Object.assign({}, defaults, options);

    // o is an (o)bject with these optional properties:
    // o.a is title text, sorta like (a)lt text
    // o.c is the (c)lasses, separated by spaces like usual
    // o.d is (d)efault, or selected, or checked
    // o.e is the (e)lement, like "td" or "tr"
    // o.i is the (i)d
    // o.j is onclick event code in (j)avascript ((c)lick or at least (e)vent was taken)
    // o.l is the (l)ist for a dropdown or other form input
    // o.m is whether it's (m)ulti or the like
    // o.n is the (n)ame of the element
    // o.s is the col(s)pan
    // o.t is the innerHTML (t)ext or such
    // o.v is any necessary (v)alue, like input.value
    // o.w is the (w)idth
    // o.y is t(y)pe
    
    let t;
    if (["radio", "checkbox"].includes(o.e)) {
      console.log("radio");
      ts = [];
      let d = document.createElement("div");
      o.l.forEach(l => {
        let x = document.createElement("input");
        x.value = l.value;
        x.name = l.name;
        x.text = l.text;
        x.type = o.e;
        let label = document.createElement("label");
        label.textContent = " " + l.text + " ";
        // ts.push(x);
        d.appendChild(x);
        d.appendChild(label);
      });
      t = d;
      console.log(d)
    } else {
      // console.log(["radio", "checkbox"].includes[o.e])
      t = document.createElement(o.e);
      Object.assign(t, {
        id: o.i,
        name: o.n + "[]",
        className: o.c,
        innerHTML: o.t,
        colSpan: o.s,
        multiple: o.m,
        value: o.v,
        selected: o.d,
        onclick: o.j,
        href: o.href,
        style: o.style
      });

      t.title = o.a;
      t.type = o.y;
      if (o.w != "") {
        t.width =  o.w + "%";
      }
      if (typeof o.j != "undefined") {
        t.onclick = o.j;
      }
      if (typeof o.href != "undefined") {
        t.href = o.href;
      }
      if (typeof o.style != "undefined") {
        t.style = o.style;
      }
      if (o.e == "select") {
        list = o.l;
        for (option in list) 
          if(list[option] != null && list[option].name != ""&& list[option].name != null)
            t.appendChild(makeelement({e: "option", v: list[option].value, t: list[option].name}));
      }
    }
    return t;
  }
  


  const makeselects = (o) => {
    const select = makeelement(o);
      list = o.l;
      // select.appendChild(makeelement({e: "option", v: "", t: "None selected"}));
      for (option in list) 
        if(list[option].Program_Nm != ""&& list[option].Program_Nm != null)
          select.appendChild(makeelement({e: "option", v: list[option].Program_Nm, t: list[option].Program_Nm}));
    return select;
  }



const formatDate = (date) => {
  return [
    padTo2Digits(date.getMonth() + 1),
    padTo2Digits(date.getDate()),
    date.getFullYear(),
  ].join('-');
}

const formattime = (date) => {
  return [
    padTo2Digits(date.getHours()),
    padTo2Digits(date.getMinutes()),
    padTo2Digits(date.getSeconds()),
  ].join('');
}

const makestringdate = (dateobject) => {
  if (dateobject != null) {
    const m = padder(new Date(dateobject.date).getMonth()+1, "0", 2);
    const d = padder(new Date(dateobject.date).getDate(), "0", 2);
    const y = (new Date(dateobject.date).getFullYear()).toString().substring(2);
    r = (dateobject == null) ? "" : m + "-" + d + "-" + y;
    return r;
  } else 
    return "";
}

const betweendate = (dates, tween) => {
  let s = splitdate(dates);
  r = ((middle >= new Date(s[0]) && middle <= new Date(s[1])));
  return r;
}  

// get start and end date from a date range set via Bootstrap date range picker
const ranger = (daterange) => ({
  start: daterange.substring(0, daterange.indexOf(" - ")+1), 
  end: daterange.substring(daterange.indexOf(" - ")+4)
}); 
