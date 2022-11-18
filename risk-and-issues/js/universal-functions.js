const sortby = (list, property) => {
    rv = list.sort((a, b) => {
      return ((a[property] > b[property]) ? 1 : ((a[property] < b[property]) ? -1 : 0));
    });
    return rv;
  }

  const isempty = (target) => {
    return ([undefined, null, "null", ""].includes(target));
  }

  const capitalize = (target) => {
      return target.charAt(0).toUpperCase() + target.slice(1);
  }

  const makeelement = (o) => {

    // o is an (o)bject with these optional properties:
    // o.c is the (i)d
    // o.e is the (e)lement, like "td" or "tr"
    // o.n is the (n)ame of the element
    // o.c is the (c)lasses, separated by spaces like usual
    // o.t is the innerHTML (t)ext or such
    // o.s is the col(s)pan
    // o.w is the (w)idth
    // o.m is whether it's (m)ulti or the like
    // o.v is any necessary (v)alue, like input.value
    // o.j is onclick event code in (j)avascript ((c)lick or at least (e)vent was taken)
    // o.d is (d)efault, or selected, or checked
  
    const t = document.createElement(o.e);
    t.id = (typeof o.i == "undefined") ? "" : o.i;
    t.name = (typeof o.n == "undefined") ? "" : o.n + "[]";
    t.className = (typeof o.c == "undefined") ? "" : o.c;
    t.innerHTML = (typeof o.t == "undefined") ? "" : o.t;
    t.colSpan = (typeof o.s == "undefined") ? "" : o.s;
    if (typeof o.w != "undefined" && o.w != "") {
      t.width =  o.w + "%";
    }
    t.multiple = (typeof o.m == "undefined") ? "" : o.m;
    t.value = (typeof o.v == "undefined") ? "" : o.v;
    t.selected = (typeof o.d == "undefined") ? "" : o.d;
    if (typeof o.j != "undefined") {
      t.onclick = o.j;
    }
    if (typeof o.href != "undefined") {
      t.href = o.href;
    }
    if (typeof o.style != "undefined") {
      t.style = o.style;
    }
    return t;
  }
  
// Sanitize a string
const makesafe = (target) => {
  return  (target in ["null", null]) ? "datamissing" : target.replace(/[.\s]/g,'');
}

const padTo2Digits = (num) => num.toString().padStart(2, '0');

const formatDate = (date) => {
  return [
    padTo2Digits(date.getMonth() + 1),
    padTo2Digits(date.getDate()),
    date.getFullYear(),
  ].join('-');
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

const padder = (target, character, size) => {
  tl = target.toString();
  return character.repeat(size-tl.length) + target.toString();
}

const splitdate = (datestring) => {
  let newdate = datestring.split(" - ");
  return newdate;
}  

const betweendate = (dates, tween) => {
  let s = splitdate(dates);
  let m = new Date(tween)
  let first = new Date(s[0]);
  let middle = new Date(m.setDate(m.getDate()));
  let last = new Date(s[1]);
  if ((middle >= first && middle <= last)) {
    // console.log (first + ":" + middle + ":" + last);
  }
  r = ((middle >= first && middle <= last));
  return r;
}  

const makedate = (dateobject) => {
  return dateobject.getFullYear() + "-" + (dateobject.getMonth()+1) + "-" + dateobject.getDate();
}

const ranger = (daterange) => {
  // get start and end date from a date range set via Bootstrap date range picker
  const dates = {};
  dates.start = daterange.substring(0, daterange.indexOf(" - ")+1);
  dates.end = daterange.substring(daterange.indexOf(" - ")+4);
  return dates; } 
