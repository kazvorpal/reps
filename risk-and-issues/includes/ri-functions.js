//BACK BUTTON WARNING


function myConfirmation() {
    let text = "If you go back, you will lose the information you have entered or changed on this form.";
    if (confirm(text) == true) {
      text = "You pressed OK!";
      window.history.go(-1);
    } else {
      text = "You canceled!";
    }
    document.getElementById("demo").innerHTML = text;
  }
const closeWarning = () => {
  console.log(event);
  document.datechanged = true;
}
const validateForm = () => {
  console.log("validateform");
  let invalid = 0;
  let ap = ac = "";
  if (document.getElementById("DateClosed").value != '') {
    if (document.getElementById("ActionPlan").value == '') {
      document.getElementById("ActionPlan").style.backgroundColor = "#fdd";
      invalid++;
      ap = `Enter the resolution of the risk/issue as the final entry in the Action Plan field
`;
    }
    if (document.getElementById("assCRID").value == '') {
      document.getElementById("assCRID").parentElement.style.backgroundColor = "#fdd";
      invalid++;
      ac = `Complete the Associated CR ID field.   
Enter the CIR # (e.g. SN-394) or N/A `
    }
    let warning = `Complete these items to close the risk/issue:
    ${ap}${ac}If the project risk/issue became a program risk/issue, click the button that says Transfer to Program Manager.
Any Fields Still Empty are in RED, Above`;
    if(invalid > 0){
      alert(warning);
    return false;
    }
  } else {
    if (document.querySelector("#program").value == "") {
      alert("please select a program");
      document.querySelector("#program").parentElement.style.backgroundColor = "#fee";
      window.scrollTo(0, 0);
      return false;
    } else 
      return true
  }
A}

const checkprogram = () => {
}