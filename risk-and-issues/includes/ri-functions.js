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
    if (document.querySelector("#program") && document.querySelector("#program").value == "") {
      alert("please select a program");
      document.querySelector("#program").parentElement.style.backgroundColor = "#fee";
      window.scrollTo(0, 0);
      return false;
    } else 
      return true
  }
}

const unknowndate = () => {
  console.log("running unknowndate")
  let $dateInput = $('input[name="date"]');
  let $unknownCheckbox = $('input[name="Unknown"]');

  // Set initial custom validity message
  $dateInput.get(0).setCustomValidity('You must select a date or check Unknown');

  // Check the initial state on page load
  if ($dateInput.val()) {
    $unknownCheckbox.prop('disabled', true);
    $dateInput.get(0).setCustomValidity('');
  } else {
    $dateInput.get(0).setCustomValidity('You must select a date or check Unknown');
  }

  if ($unknownCheckbox.is(':checked')) {
    $dateInput.prop('disabled', true).prop('required', false);
  }

  $dateInput.on('input', function() {
    console.log("invoking date event")
    if ($(this).val()) {
      $unknownCheckbox.prop('disabled', true);
    } else {
      $unknownCheckbox.prop('disabled', false);
    }
    this.setCustomValidity('');
  });

  $unknownCheckbox.on('change', function() {
    console.log("invoking unknown checkbox event")
    if ($(this).is(':checked')) {
      $dateInput.prop('disabled', true).prop('required', false);
    } else {
      $dateInput.prop('disabled', false).prop('required', true);
    }
  });
}

const checkprogram = () => {
}