<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="https://cdn.rawgit.com/crlcu/multiselect/v2.5.1/dist/js/multiselect.min.js"></script>

<div class="row">
  <div class="col-xs-5">
    <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple" data-maximum-selection-length="3" >
      <option value="1">Item 1</option>
      <option value="2">Item 2</option>
      <option value="3">Item 3</option>
      <option value="4">Item 4</option>
      <option value="5">Item 5</option>
    </select>
  </div>

  <div class="col-xs-2">
    <!--<button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>-->
    <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
    <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
    <!--<button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>-->
  </div>

  <div class="col-xs-5">
    <select name="to[]" id="multiselect_to" class="form-control" size="8" multiple="multiple" data-maximum-selection-length="3" ></select>

    <div class="row">
      <div class="col-xs-6">
        <button type="button" id="multiselect_move_up" class="btn btn-block"><i class="glyphicon glyphicon-arrow-up"></i></button>
      </div>
      <div class="col-xs-6">
        <button type="button" id="multiselect_move_down" class="btn btn-block col-sm-6"><i class="glyphicon glyphicon-arrow-down"></i></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#multiselect').multiselect();
    preselect = {};
    document.getElementById("multiselect").addEventListener('click', function(event) {
        if (this.selectedOptions.length <= 3) {
            preselect = $(this).val();
        } else {
            alert("You cain't select more than three");
            $(this).val(preselect);
            $("option:selected").removeAttr("selected"); //solution 1
            return false;
        }
    });
    document.getElementById("multiselect_rightSelected").addEventListener("click", function(event) {
        to = document.getElementById("multiselect_to");
        ms = document.getElementById("multiselect");
        if (to.options.length <= 3) {
            postselect = to.innerHTML;
            loadselect = ms.innerHTML;
        }
        else {
            alert("You cain't add more than three to this list")
            ms.innerHTML = loadselect;
            to.innerHTML = postselect;
        }
    });
});
</script>
