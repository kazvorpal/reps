//BACK BUTTON WARNING

/*
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
  if (document.getElementById("DateClosed").value != '' && 
      (document.getElementById("ActionPlan").value == '' || document.getElementById("AssCRID").value == '')) {
      // (document.getElementById("ActionPlan").value == '' || document.getElementById("assCRID").value == '')) {
        console.log((document.getElementById("ActionPlan").value))
        console.log((document.getElementById("AssCRID").value))
    const warning = `Complete these items to close the risk/issue
    Enter the resolution of the risk/issue as the final entry in the Action Plan field
    Complete the Associated CR ID field.   Enter the CIR # or N/A 
    If the project risk/issue became a program risk/issue, click the button that says Transfer to Program Manager`;
    alert(warning);
    return false;
  } else {
    console.log("pass")
    console.log(document.getElementById("ActionPlan").value)
    return true;
  }
}

*/

// This wasn't even being used