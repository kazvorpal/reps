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

