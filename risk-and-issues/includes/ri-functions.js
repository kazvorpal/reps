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
const warning = `Complete these items to close the risk/issue
Enter the resolution of the risk/issue as the final entry in the Action Plan field
Complete the Associated CR ID field.   
Enter the CIR # (e.g. SN-394) or N/A 
If the project risk/issue became a program risk/issue, click the button that says Transfer to Program Manager.
Any Fields Still Empty are in RED, Above`;
const validateForm = () => {
  let invalid = 0;
  if (document.getElementById("DateClosed").value != '') {
    if (document.getElementById("ActionPlan").value == '') {
      document.getElementById("ActionPlan").style.backgroundColor = "#fdd";
      invalid++;
    }
    if (document.getElementById("assCRID").value == '') {
      document.getElementById("assCRID").parentElement.style.backgroundColor = "#fdd";
      invalid++;
    }
    if(invalid > 0){
      alert(warning);
    return false;
    }
  } else {
    console.log("pass")
    console.log(document.getElementById("ActionPlan").value)
    return true;
  }
}

// yes it was.  warning for back button  on all forms