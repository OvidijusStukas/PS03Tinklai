function deleteConfirmDialog(module, id) {
    var result = confirm('Ar tikrai norite pašalinti elementą?');
    if(result === true) {
        window.location.replace('index.php?module=' + module + '&remove=' + id);
    }
}

function addPlanRestriction() {
    var planTemplate =
        "<div data-target='restriction'>" +
            "<div class='form-group'>" +
                "<label>Kaina</label>" +
                "<input class='form-control' name='price[]' type='text' placeholder='Fiksuota kaina' value=''>" +
                "<span class='glyphicon glyphicon-remove remove-icon' onclick='removeNewPlanRestriction(this);'></span>" +
            "</div>" +
            "<div class='form-group'>"+
                "<label>MB viršytas kiekis</label>"+
            "   <input class='form-control' name='mbCount[]' type='text' placeholder='MB kiekis' value=''>"+
            "</div>"+
            "<input type='hidden' name='planRestrictionId[]' value='-1'>"+
            "</br>" +
        "</div>";

    $('#form').find('button').first().before(planTemplate);
}

function removePlanRestrictionById(id) {
    $('input[value='+id+']').closest('*[data-target="restriction"]').remove();
    var input = '<input type="hidden" name="plan_restriction_removed[]" value="'+id+'">';
    $('#form').append(input);
}

function removeNewPlanRestriction(element) {
    var $div = $(element).closest('*[data-target="restriction"]');
    $div.closest('br').remove();
    $div.remove();
}